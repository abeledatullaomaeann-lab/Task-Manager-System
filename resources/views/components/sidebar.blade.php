@php
    $user = auth()->user();
    $sidebarCategories = \App\Models\Category::withCount([
        'tasks' => fn ($q) => $q->where('user_id', $user->id),
    ])->get();
    $initials = collect(explode(' ', $user->name))
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<aside class="sidebar">
    <div class="brand">
        <span class="brand-logo"><i class="bi bi-check2"></i></span>
        Task Manager
    </div>


    {{-- <x-nav-link icon="grid" :href="route('home')" :active="request()->routeIs('home')">Dashboard</x-nav-link> --}}
    <x-nav-link icon="check-circle" :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">My tasks</x-nav-link>

    @foreach ($sidebarCategories as $category)
        <a href="{{ route('tasks.index') }}" class="nav-item">
            <span class="proj-icon" style="background: {{ $category->color }}22; color: {{ $category->color }}">
                <i class="bi bi-folder"></i>
            </span>
            {{ $category->name }}
            <small data-category-count="{{ $category->id }}">{{ $category->tasks_count }}</small>
        </a>
    @endforeach

    <div class="sidebar-bottom">
        <x-nav-link icon="gear">Settings</x-nav-link>
        <x-nav-link icon="question-circle">Help &amp; feedback</x-nav-link>

        <div class="user">
            <span class="avatar">{{ $initials }}</span>
            <div>
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-plan">Free plan</div>
            </div>

            <form method="POST" action="{{ route('logout') }}" style="margin-left: auto">
                @csrf
                <button type="submit" class="logout-btn" title="Log out"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</aside>