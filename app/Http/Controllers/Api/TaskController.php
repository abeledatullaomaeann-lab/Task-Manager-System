<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->tasks()
            ->with('category')
            ->where('status', $request->query('status') === 'done' ? 'done' : 'pending');

        if ($search = $request->query('search')) {
            $query->whereLike('title', "%{$search}%");
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        match ($request->query('sort')) {
            'newest' => $query->latest(),
            'title' => $query->orderBy('title'),
            default => $query->orderBy('due_date'),
        };

        $total = $user->tasks()->count();
        $done = $user->tasks()->where('status', 'done')->count();

        return TaskResource::collection($query->paginate(8)->withQueryString())
            ->additional(['stats' => [
                'total' => $total,
                'done' => $done,
                'percent' => $total > 0 ? round($done / $total * 100) : 0,
            ]]);
    }

    public function store(Request $request)
    {
        $task = $request->user()->tasks()->create($this->validated($request));

        return (new TaskResource($task->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Task $task)
    {
        $this->ownTask($task);

        return new TaskResource($task->load('category'));
    }

    public function update(Request $request, Task $task)
    {
        $this->ownTask($task);
        $task->update($this->validated($request));

        return new TaskResource($task->load('category'));
    }

    public function toggle(Task $task)
    {
        $this->ownTask($task);
        $task->update([
            'status' => $task->status === 'done' ? 'pending' : 'done',
        ]);

        return new TaskResource($task->load('category'));
    }

    public function destroy(Task $task)
    {
        $this->ownTask($task);
        $task->delete();

        return response()->json(['message' => 'Task deleted.']);
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