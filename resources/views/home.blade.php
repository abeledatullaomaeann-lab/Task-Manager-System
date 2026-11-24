@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-top">
        <div>
            <div class="eyebrow">{{ now()->format('l, F j, Y') }}</div>
            <h1 class="greeting">Good morning, Mae Ann</h1>
            <p class="subtext">A fresh week, a clear mind. Let's make it count.</p>
        </div>
        <a href="#" class="btn-primary"><i class="bi bi-plus-lg"></i> Add task</a>
    </div>

    <div class="eyebrow-plain">Your overview</div>
    <h2 class="overview-title">A little progress goes a long way.</h2>
    <p class="subtext">Here's what's happening across your tasks today.</p>

    <div class="stats">
        <x-stat-card label="Open tasks" :value="$open" hint="Ready to make progress" icon="list-check" color="#5b9a6b" />
        <x-stat-card label="Completed" :value="$done" :hint="$percent . '% of all tasks'" icon="check2-all" color="#8a8a84" />
        <x-stat-card label="Due today" :value="$todayTasks->count()" hint="Keep these moving" icon="calendar3" color="#d98e4a" />
        <x-stat-card label="Projects" :value="$categories->count()" hint="Across your workspace" icon="folder" color="#6b6bd6" />
    </div>

    <div class="grid-2">
        <div class="card panel">
            <div class="panel-top">
                <div>
                    <div class="eyebrow-plain">{{ now()->format('l, F j') }}</div>
                    <h3 class="panel-title">Today's focus</h3>
                </div>
                <span class="pill">{{ $todayTasks->count() }} {{ Str::plural('task', $todayTasks->count()) }}</span>
            </div>

            @forelse ($todayTasks as $task)
                <div class="mini-row">
                    <span class="check"></span>
                    <span class="task-title">{{ $task->title }}</span>
                    <span class="mini-cat">{{ $task->category->name }}</span>
                </div>
            @empty
                <p class="empty">Nothing due today. Enjoy the breathing room.</p>
            @endforelse

            <a href="{{ route('tasks.index') }}" class="panel-link">View all tasks <i class="bi bi-arrow-up-right"></i></a>
        </div>

        <div class="card panel">
            <div class="panel-top">
                <div>
                    <div class="eyebrow-plain">Coming up</div>
                    <h3 class="panel-title">Next on your list</h3>
                </div>
                <i class="bi bi-calendar3" style="color: var(--muted)"></i>
            </div>

            @forelse ($upcoming as $task)
                <div class="mini-row">
                    <span class="dot" style="background: {{ $task->category->color }}"></span>
                    <span class="task-title">{{ $task->title }}</span>
                    <span class="pill pill-date">{{ $task->due_date->format('M j') }}</span>
                </div>
            @empty
                <p class="empty">No upcoming tasks.</p>
            @endforelse

            <a href="{{ route('tasks.index') }}" class="panel-link">Open task list <i class="bi bi-arrow-up-right"></i></a>
        </div>
    </div>

    <div class="card panel">
        <div class="panel-top">
            <div>
                <div class="eyebrow-plain">Your workspace</div>
                <h3 class="panel-title">Projects</h3>
            </div>
            <a href="{{ route('tasks.index') }}" class="panel-link" style="margin-top: 0">See tasks</a>
        </div>

        <div class="projects">
            @foreach ($categories as $category)
                @php
                    $pct = $category->tasks_count > 0 ? round($category->done_count / $category->tasks_count * 100) : 0;
                @endphp
                <div class="project-card">
                    <div class="project-name">
                        <span class="dot" style="background: {{ $category->color }}"></span>
                        {{ $category->name }}
                    </div>
                    <div class="project-meta">
                        {{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }} · {{ $category->done_count }} completed
                    </div>
                    <div class="progress full">
                        <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $category->color }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection