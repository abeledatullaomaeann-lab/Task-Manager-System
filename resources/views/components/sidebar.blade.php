@php
    $sidebarCategories = \App\Models\Category::withCount('tasks')->get();
@endphp

<aside class="sidebar">
    <div class="brand">
        <span class="brand-logo"><i class="bi bi-check2"></i></span>
        Task Manager
    </div>

    <div class="search"><i class="bi bi-search"></i> Search anything...</div>

    {{-- <x-nav-link icon="grid" :href="route('home')" :active="request()->routeIs('home')">Dashboard</x-nav-link> --}}
    <x-nav-link icon="check-circle" :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">My tasks</x-nav-link>

    @foreach ($sidebarCategories as $category)
        <a href="{{ route('tasks.index') }}" class="nav-item">
            <span class="proj-icon" style="background: {{ $category->color }}22; color: {{ $category->color }}">
                <i class="bi bi-folder"></i>
            </span>
            {{ $category->name }}
            <small>{{ $category->tasks_count }}</small>
        </a>
    @endforeach

    <div class="sidebar-bottom">
        <x-nav-link icon="gear">Settings</x-nav-link>
        <x-nav-link icon="question-circle">Help &amp; feedback</x-nav-link>

        <div class="user">
            <span class="avatar">MA</span>
            <div>
                <div class="user-name">Mae Ann</div>
                <div class="user-plan">Free plan</div>
            </div>
        </div>
    </div>
</aside>