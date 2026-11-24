@extends('layouts.app')

@section('title', 'My tasks')

@section('content')
    @if (session('success'))
        <x-alert>{{ session('success') }}</x-alert>
    @endif

    <div class="page-top">
        <div>
            <div class="eyebrow">{{ now()->format('l, F j, Y') }}</div>
            <h1 class="greeting">Good morning, Mae Ann</h1>
            <p class="subtext">A fresh week, a clear mind. Let's make it count.</p>
        </div>
        <button type="button" class="btn-primary" data-add-task>
            <i class="bi bi-plus-lg"></i> Add task
        </button>
    </div>

    <div class="card focus">
        <div class="focus-label"><i class="bi bi-stars"></i> Your daily focus</div>
        <div class="focus-title">{{ $headline }}</div>
        <p class="focus-text">You've completed {{ $done }} of {{ $total }} tasks. Keep the momentum going.</p>
        <div class="progress"><div class="progress-bar" style="width: {{ $percent }}%"></div></div>
        <div class="progress-meta">
            <span>{{ $percent }}% complete</span>
            <span>{{ $total - $done }} tasks to go</span>
        </div>
    </div>

    <div class="section-top">
        <div>
            <div class="eyebrow">Your day</div>
            <h2 class="section-title">My tasks</h2>
        </div>
        <div class="toolbar">
            <a href="{{ $showDone ? route('tasks.index', ['sort' => $sort]) : route('tasks.index', ['status' => 'done', 'sort' => $sort]) }}"
               class="tool-btn {{ $showDone ? 'active' : '' }}">
                <i class="bi bi-check2-all"></i> Done <span class="count">{{ $done }}</span>
            </a>

            <form method="GET" class="tool-btn tool-select">
                @if ($showDone)
                    <input type="hidden" name="status" value="done">
                @endif
                <i class="bi bi-sliders"></i>
                <select name="sort" onchange="this.form.submit()">
                    <option value="due_date" @selected($sort === 'due_date')>Sort: Due date</option>
                    <option value="newest" @selected($sort === 'newest')>Sort: Newest</option>
                    <option value="title" @selected($sort === 'title')>Sort: Title</option>
                </select>
            </form>
        </div>
    </div>

    <div class="card list">
        @forelse ($tasks as $task)
            <x-task-row :task="$task" />
        @empty
            <p class="empty">
                {{ $showDone ? 'No completed tasks yet.' : 'No tasks yet. Add your first task to get started.' }}
            </p>
        @endforelse
    </div>

    {{-- Add / Edit modal --}}
    <x-modal id="task-modal" title="Add task">
        <form method="POST" id="task-form" action="{{ route('tasks.store') }}">
            @csrf
            <input type="hidden" name="_method" value="PUT" id="task-method" disabled>
            <input type="hidden" name="_form" value="task">
            <input type="hidden" name="_task_id" id="task-id" value="">

            <x-input name="title" label="Title" />

            <div class="field">
                <label for="field-description">Description</label>
                <textarea name="description" id="field-description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="field-category_id">Category</label>
                <select name="category_id" id="field-category_id">
                    <option value="">Choose a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <x-input name="due_date" label="Due date" type="date" />

            <div class="modal-actions">
                <button type="button" class="btn-ghost" data-close-modal>Cancel</button>
                <button type="submit" class="btn-primary">Save task</button>
            </div>
        </form>
    </x-modal>

    {{-- Delete modal --}}
    <x-modal id="delete-modal" title="Delete task">
        <form method="POST" id="delete-form">
            @csrf
            @method('DELETE')
            <p>Are you sure you want to delete <strong id="delete-title"></strong>? This can't be undone.</p>
            <div class="modal-actions">
                <button type="button" class="btn-ghost" data-close-modal>Cancel</button>
                <button type="submit" class="btn-danger">Delete</button>
            </div>
        </form>
    </x-modal>
@endsection

@push('scripts')
<script>
    const baseUrl = "{{ url('/tasks') }}";
    const taskModal = document.getElementById('task-modal');
    const taskForm = document.getElementById('task-form');
    const deleteModal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const field = (name) => taskForm.querySelector(`[name="${name}"]`);

    function openTaskModal(task = null) {
        const isEdit = task && task.id;

        taskModal.querySelector('h3').textContent = isEdit ? 'Edit task' : 'Add task';
        taskForm.action = isEdit ? `${baseUrl}/${task.id}` : baseUrl;
        document.getElementById('task-method').disabled = !isEdit;
        document.getElementById('task-id').value = isEdit ? task.id : '';

        field('title').value = task?.title ?? '';
        field('description').value = task?.description ?? '';
        field('category_id').value = task?.category ?? '';
        field('due_date').value = task?.due ?? '';

        taskModal.showModal();
    }

    document.addEventListener('click', (e) => {
        const add = e.target.closest('[data-add-task]');
        const edit = e.target.closest('[data-edit-task]');
        const del = e.target.closest('[data-delete-task]');
        const close = e.target.closest('[data-close-modal]');

        if (add) {
            document.querySelectorAll('.field-error').forEach((el) => el.remove());
            openTaskModal();
        }

        if (edit) {
            document.querySelectorAll('.field-error').forEach((el) => el.remove());
            const d = edit.dataset;
            openTaskModal({ id: d.id, title: d.title, description: d.description, category: d.category, due: d.due });
        }

        if (del) {
            deleteForm.action = `${baseUrl}/${del.dataset.id}`;
            document.getElementById('delete-title').textContent = del.dataset.title;
            deleteModal.showModal();
        }

        if (close) {
            close.closest('dialog').close();
        }
    });

    // Clicking the dark area outside a modal closes it
    document.querySelectorAll('dialog').forEach((dialog) => {
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) dialog.close();
        });
    });

    @if ($errors->any() && old('_form') === 'task')
        openTaskModal({
            id: @json(old('_task_id')),
            title: @json(old('title')),
            description: @json(old('description')),
            category: @json(old('category_id')),
            due: @json(old('due_date')),
        });
    @endif
</script>
@endpush