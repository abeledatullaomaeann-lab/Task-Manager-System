@props(['task'])

@php $done = $task->status === 'done'; @endphp

<div class="task-row {{ $done ? 'is-done' : '' }}">
    <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="check-form">
        @csrf
        @method('PATCH')
        <button type="submit" class="check {{ $done ? 'checked' : '' }}"
                title="{{ $done ? 'Mark as pending' : 'Mark as done' }}">
            @if ($done)<i class="bi bi-check-lg"></i>@endif
        </button>
    </form>

    <span class="task-title">{{ $task->title }}</span>

    <div class="task-meta">
        <span class="meta-project">
            <span class="dot" style="background: {{ $task->category->color }}"></span>
            {{ $task->category->name }}
        </span>
        <span class="meta-due">
            <i class="bi bi-calendar3"></i> {{ $task->due_date?->format('M j') }}
        </span>
    </div>

    <div class="task-actions">
        <button type="button" class="icon-btn" title="Edit"
                data-edit-task
                data-id="{{ $task->id }}"
                data-title="{{ $task->title }}"
                data-description="{{ $task->description }}"
                data-category="{{ $task->category_id }}"
                data-due="{{ $task->due_date?->format('Y-m-d') }}">
            <i class="bi bi-pencil"></i>
        </button>
        <button type="button" class="icon-btn" title="Delete"
                data-delete-task
                data-id="{{ $task->id }}"
                data-title="{{ $task->title }}">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>