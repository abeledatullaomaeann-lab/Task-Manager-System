<aside class="sidebar">
    <div class="brand">
        <span class="brand-logo"><i class="bi bi-check2"></i></span>
        daymark
    </div>

    <div class="search"><i class="bi bi-search"></i> Search anything...</div>

    <div class="nav-label">Workspace</div>
    <x-nav-link icon="inbox">Inbox</x-nav-link>
    <x-nav-link icon="calendar-check" :count="3">Today</x-nav-link>
    <x-nav-link icon="grid">Upcoming</x-nav-link>
    <x-nav-link icon="list-task" :href="route('tasks.index')" :active="request()->routeIs('tasks.index')">My tasks</x-nav-link>

    <div class="nav-label">Your projects</div>
    <a href="#" class="nav-item"><span class="dot" style="background:#6b6bd6"></span> Website redesign <small>8</small></a>
    <a href="#" class="nav-item"><span class="dot" style="background:#5b9a6b"></span> Product sprint <small>5</small></a>
    <a href="#" class="nav-item"><span class="dot" style="background:#d98e4a"></span> Marketing launch <small>3</small></a>

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