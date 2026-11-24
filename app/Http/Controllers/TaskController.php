<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $showDone = $request->query('status') === 'done';
        $sort = $request->query('sort', 'due_date');

        $query = Task::with('category')->where('status', $showDone ? 'done' : 'pending');

        match ($sort) {
            'newest' => $query->latest(),
            'title' => $query->orderBy('title'),
            default => $query->orderBy('due_date'),
        };

        $tasks = $query->get();
        $categories = Category::all();

        $total = Task::count();
        $done = Task::where('status', 'done')->count();
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
        $data = $this->validated($request);
        $data['user_id'] = User::first()->id; // temporary until login is added on Wednesday

        Task::create($data);

        return back()->with('success', 'Task added.');
    }

    public function update(Request $request, Task $task)
    {
        $task->update($this->validated($request));

        return back()->with('success', 'Task updated.');
    }

    public function toggle(Task $task)
    {
        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done',
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'due_date' => ['nullable', 'date'],
        ]);
    }
}