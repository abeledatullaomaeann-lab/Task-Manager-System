@extends('layouts.app')

@section('title', 'My tasks')

@section('content')
    <div id="alert-area"></div>

    <div class="page-top">
        <div>
            <div class="eyebrow">{{ now()->format('l, F j, Y') }}</div>
            <h1 class="greeting">Good morning, {{ auth()->user()->name }}</h1>
            <p class="subtext">A fresh week, a clear mind. Let's make it count.</p>
        </div>
                <x-button id="add-task" icon="plus-lg">Add task</x-button>
    </div>

    <div class="card focus">
        <div class="focus-label"><i class="bi bi-stars"></i> Your daily focus</div>
        <div class="focus-title" id="focus-title">&nbsp;</div>
        <p class="focus-text" id="focus-text">Loading your progress...</p>
        <div class="progress"><div class="progress-bar" id="progress-bar" style="width: 0%"></div></div>
        <div class="progress-meta">
            <span id="progress-percent">0% complete</span>
            <span id="progress-left">0 tasks to go</span>
        </div>
    </div>

    <div class="section-top">
        <div>
            <div class="eyebrow">Your day</div>
            <h2 class="section-title">My tasks</h2>
        </div>
        <div class="toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="search" id="search" placeholder="Search tasks...">
            </div>

            <div class="tool-btn tool-select">
                <i class="bi bi-folder"></i>
                <select id="category-filter">
                    <option value="">All categories</option>
                </select>
            </div>

            <button type="button" class="tool-btn" id="done-toggle">
                <i class="bi bi-check2-all"></i> Done <span class="count" id="done-count">0</span>
            </button>

            <div class="tool-btn tool-select">
                <i class="bi bi-sliders"></i>
                <select id="sort">
                    <option value="due_date">Sort: Due date</option>
                    <option value="newest">Sort: Newest</option>
                    <option value="title">Sort: Title</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card list" id="task-list"></div>
    <div class="pagination" id="pagination"></div>

    <x-task-row />

    {{-- Add / Edit modal --}}
    <x-modal id="task-modal" title="Add task">
        <form id="task-form" novalidate>
            <x-input name="title" label="Title" />

                        <x-textarea name="description" label="Description" />

            <x-select name="category_id" label="Category">
                <option value="">Choose a category</option>
            </x-select>

            <x-input name="due_date" label="Due date" type="date" />

            <div class="modal-actions">
                <x-button variant="ghost" data-close-modal>Cancel</x-button>
                <x-button type="submit">Save task</x-button>
            </div>
        </form>
    </x-modal>

    {{-- Delete modal --}}
    <x-modal id="delete-modal" title="Delete task">
        <form id="delete-form">
            <p>Are you sure you want to delete <strong id="delete-title"></strong>? This can't be undone.</p>
            <div class="modal-actions">
                <x-button variant="ghost" data-close-modal>Cancel</x-button>
                <x-button type="submit" variant="danger">Delete</x-button>
            </div>
        </form>
    </x-modal>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script>
    const api = axios.create({
        baseURL: '/api',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    });

    // If the session ended, go back to the login page
    api.interceptors.response.use(
        (response) => response,
        (error) => {
            if (error.response?.status === 401) window.location.href = '/login';
            return Promise.reject(error);
        }
    );

    const $ = (selector) => document.querySelector(selector);
    const list = $('#task-list');
    const taskModal = $('#task-modal');
    const taskForm = $('#task-form');
    const deleteModal = $('#delete-modal');
    const rowTemplate = $('#task-row-template');

    const state = { page: 1, search: '', category: '', status: 'pending', sort: 'due_date' };
    const tasksById = {};
    let editingId = null;
    let deletingId = null;
    let categoriesFilled = false;

    function flash(message, type = 'success') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.innerHTML = '<i class="bi bi-check-circle"></i> ';
        alert.append(message);
        $('#alert-area').replaceChildren(alert);
        setTimeout(() => alert.remove(), 3000);
    }

    function clearErrors() {
        document.querySelectorAll('[data-error]').forEach((el) => (el.textContent = ''));
    }

    function showErrors(error) {
        if (error.response?.status === 422) {
            Object.entries(error.response.data.errors).forEach(([field, messages]) => {
                const el = document.querySelector(`[data-error="${field}"]`);
                if (el) el.textContent = messages[0];
            });
        } else {
            flash('Something went wrong. Please try again.', 'error');
        }
    }

    async function loadCategories() {
        const { data } = await api.get('/categories');

        if (!categoriesFilled) {
            ['#category-filter', '#field-category_id'].forEach((selector) => {
                data.data.forEach((category) => $(selector).append(new Option(category.name, category.id)));
            });
            categoriesFilled = true;
        }

        data.data.forEach((category) => {
            const counter = document.querySelector(`[data-category-count="${category.id}"]`);
            if (counter) counter.textContent = category.tasks_count;
        });
    }

    async function loadTasks() {
        list.innerHTML = '<p class="empty">Loading tasks...</p>';

        try {
            const { data } = await api.get('/tasks', {
                params: {
                    page: state.page,
                    search: state.search,
                    category_id: state.category,
                    status: state.status,
                    sort: state.sort,
                },
            });

            // Deleted the last task on the last page? Go back one page.
            if (data.data.length === 0 && state.page > 1) {
                state.page = data.meta.last_page;
                return loadTasks();
            }

            renderTasks(data.data);
            renderPagination(data.meta);
            renderStats(data.stats);
        } catch (error) {
            list.innerHTML = '<p class="empty">Could not load your tasks. Please refresh the page.</p>';
        }
    }

    function emptyMessage() {
        if (state.search || state.category) return 'No tasks match your search.';
        return state.status === 'done'
            ? 'No completed tasks yet.'
            : 'No tasks yet. Add your first task to get started.';
    }

    function renderTasks(tasks) {
        list.replaceChildren();

               if (tasks.length === 0) {
            const filtered = state.search || state.category;
            const empty = document.createElement('div');
            empty.className = 'empty';
            empty.innerHTML = `<i class="bi ${filtered ? 'bi-search' : 'bi-check2-circle'}"></i><strong></strong><span></span>`;

            empty.querySelector('strong').textContent = filtered
                ? 'No matching tasks'
                : (state.status === 'done' ? 'Nothing completed yet' : 'No tasks yet');
            empty.querySelector('span').textContent = filtered
                ? 'Try a different search or category.'
                : (state.status === 'done' ? 'Completed tasks will show up here.' : 'Add your first task to get started.');

            if (!filtered && state.status === 'pending') {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn-primary';
                button.innerHTML = '<i class="bi bi-plus-lg"></i> Add task';
                button.addEventListener('click', () => openTaskModal());
                empty.append(button);
            }

            list.append(empty);
            return;
        }

        tasks.forEach((task) => {
            tasksById[task.id] = task;

            const row = rowTemplate.content.firstElementChild.cloneNode(true);
            row.dataset.id = task.id;
            row.querySelector('[data-title]').textContent = task.title;
            row.querySelector('[data-category]').textContent = task.category.name;
            row.querySelector('[data-dot]').style.background = task.category.color;
            row.querySelector('[data-due]').textContent = task.due_label ?? 'No date';

            if (task.status === 'done') {
                const check = row.querySelector('.check');
                row.classList.add('is-done');
                check.classList.add('checked');
                check.title = 'Mark as pending';
                check.innerHTML = '<i class="bi bi-check-lg"></i>';
            }

            list.append(row);
        });
    }

    function renderStats(stats) {
        $('#done-count').textContent = stats.done;
        $('#focus-title').textContent = stats.done === 0 ? "Let's get started." : "You're building great momentum.";
        $('#focus-text').textContent = `You've completed ${stats.done} of ${stats.total} tasks. Keep the momentum going.`;
        $('#progress-bar').style.width = `${stats.percent}%`;
        $('#progress-percent').textContent = `${stats.percent}% complete`;
        $('#progress-left').textContent = `${stats.total - stats.done} tasks to go`;
    }

    function renderPagination(meta) {
        const nav = $('#pagination');
        nav.replaceChildren();
        if (meta.last_page <= 1) return;

        const makeButton = (label, page, disabled) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'tool-btn';
            button.textContent = label;
            button.disabled = disabled;
            button.addEventListener('click', () => {
                state.page = page;
                loadTasks();
            });
            return button;
        };

        const info = document.createElement('span');
        info.textContent = `Page ${meta.current_page} of ${meta.last_page} · ${meta.total} tasks`;

        nav.append(
            makeButton('Previous', meta.current_page - 1, meta.current_page === 1),
            info,
            makeButton('Next', meta.current_page + 1, meta.current_page === meta.last_page)
        );
    }

    function openTaskModal(task = null) {
        editingId = task ? task.id : null;
        clearErrors();
        taskModal.querySelector('h3').textContent = task ? 'Edit task' : 'Add task';
        taskForm.elements.title.value = task?.title ?? '';
        taskForm.elements.description.value = task?.description ?? '';
        taskForm.elements.category_id.value = task?.category_id ?? '';
        taskForm.elements.due_date.value = task?.due_date ?? '';
        taskModal.showModal();
    }

    taskForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();
        const payload = Object.fromEntries(new FormData(taskForm));

        try {
            if (editingId) {
                await api.put(`/tasks/${editingId}`, payload);
                flash('Task updated.');
            } else {
                await api.post('/tasks', payload);
                flash('Task added.');
            }
            taskModal.close();
            await Promise.all([loadTasks(), loadCategories()]);
        } catch (error) {
            showErrors(error);
        }
    });

    $('#delete-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            await api.delete(`/tasks/${deletingId}`);
            deleteModal.close();
            flash('Task deleted.');
            await Promise.all([loadTasks(), loadCategories()]);
        } catch (error) {
            showErrors(error);
        }
    });

    list.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-action]');
        if (!button) return;

        const task = tasksById[button.closest('.task-row').dataset.id];

        if (button.dataset.action === 'toggle') {
            try {
                await api.patch(`/tasks/${task.id}/toggle`);
                await loadTasks();
            } catch (error) {
                showErrors(error);
            }
        }

        if (button.dataset.action === 'edit') openTaskModal(task);

        if (button.dataset.action === 'delete') {
            deletingId = task.id;
            $('#delete-title').textContent = task.title;
            deleteModal.showModal();
        }
    });

    let searchTimer;
    $('#search').addEventListener('input', (event) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            state.search = event.target.value.trim();
            state.page = 1;
            loadTasks();
        }, 300);
    });

    $('#category-filter').addEventListener('change', (event) => {
        state.category = event.target.value;
        state.page = 1;
        loadTasks();
    });

    $('#sort').addEventListener('change', (event) => {
        state.sort = event.target.value;
        state.page = 1;
        loadTasks();
    });

    $('#done-toggle').addEventListener('click', (event) => {
        state.status = state.status === 'done' ? 'pending' : 'done';
        state.page = 1;
        event.currentTarget.classList.toggle('active', state.status === 'done');
        loadTasks();
    });

    $('#add-task').addEventListener('click', () => openTaskModal());

    document.addEventListener('click', (event) => {
        const close = event.target.closest('[data-close-modal]');
        if (close) close.closest('dialog').close();
    });

    document.querySelectorAll('dialog').forEach((dialog) => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
    });

    loadCategories();
    loadTasks();
</script>
@endpush