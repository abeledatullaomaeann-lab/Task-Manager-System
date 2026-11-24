<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;

class HomeController extends Controller
{
    public function index()
    {
        $total = Task::count();
        $done = Task::where('status', 'done')->count();
        $open = $total - $done;
        $percent = $total > 0 ? round($done / $total * 100) : 0;

        $todayTasks = Task::with('category')
            ->where('status', 'pending')
            ->whereDate('due_date', today())
            ->get();

        $upcoming = Task::with('category')
            ->where('status', 'pending')
            ->whereDate('due_date', '>', today())
            ->orderBy('due_date')
            ->take(3)
            ->get();

        $categories = Category::withCount([
            'tasks',
            'tasks as done_count' => fn ($q) => $q->where('status', 'done'),
        ])->get();

        return view('home', compact('total', 'done', 'open', 'percent', 'todayTasks', 'upcoming', 'categories'));
    }
}