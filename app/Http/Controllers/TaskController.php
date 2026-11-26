<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $showDone = $request->query('status') === 'done';
        $sort = $request->query('sort', 'due_date');

        $query = $user->tasks()->with('category')->where('status', $showDone ? 'done' : 'pending');

        match ($sort) {
            'newest' => $query->latest(),
            'title' => $query->orderBy('title'),
            default => $query->orderBy('due_date'),
        };

        $tasks = $query->get();
        $categories = Category::all();

        $total = $user->tasks()->count();
        $done = $user->tasks()->where('status', 'done')->count();
        $percent = $total > 0 ? round($done / $total * 100) : 0;

        $headline = $done === 0
            ? "Let's get started."
            : "You're building great momentum.";

        return view('tasks.index', compact(
            'tasks', 'categories', 'total', 'done', 'percent', 'headline', 'showDone', 'sort'
        ));
    }

    public function store(Request $request)
    {
        $request->user()->tasks()->create($this->validated($request));

        return back()->with('success', 'Task added.');
    }

    public function update(Request $request, Task $task)
    {
        $this->ownTask($task);
        $task->update($this->validated($request));

        return back()->with('success', 'Task updated.');
    }

    public function toggle(Task $task)
    {
        $this->ownTask($task);
        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done',
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        $this->ownTask($task);
        $task->delete();

        return back()->with('success', 'Task deleted.');
    }

    private function ownTask(Task $task): void
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'due_date' => ['nullable', 'date'],
        ], [
            'title.required' => 'Please give your task a title.',
            'category_id.required' => 'Please choose a category.',
            'due_date.date' => "That doesn't look like a valid date.",
        ]);
    }
}