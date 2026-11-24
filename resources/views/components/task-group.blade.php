@props(['label', 'tasks'])

@if ($tasks->isNotEmpty())
    <div class="group-label">{{ $label }} <span>{{ $tasks->count() }}</span></div>
    @foreach ($tasks as $task)
        <x-task-row :task="$task" />
    @endforeach
@endif