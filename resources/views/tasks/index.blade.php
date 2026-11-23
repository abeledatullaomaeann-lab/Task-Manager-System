@extends('layouts.app')

@section('title', 'My tasks')

@section('content')
    <div class="page-top">
        <div>
            <div class="eyebrow">{{ now()->format('l, F j, Y') }}</div>
            <h1 class="greeting">Good morning, Mae Ann <i class="bi bi-asterisk"></i></h1>
            <p class="subtext">A fresh week, a clear mind. Let's make it count.</p>
        </div>
        <a href="#" class="btn-primary"><i class="bi bi-plus-lg"></i> Add task <kbd>N</kbd></a>
    </div>

    <div class="card focus">
        <div class="focus-label"><i class="bi bi-stars"></i> Your daily focus</div>
        <div class="focus-title">You're right on track.</div>
        <p class="focus-text">You've completed 1 of 6 tasks. Keep the momentum going.</p>
        <div class="progress"><div class="progress-bar" style="width: 17%"></div></div>
        <div class="progress-meta"><span>17% complete</span><span>5 tasks to go</span></div>
    </div>

    <div class="section-top">
        <div>
            <div class="eyebrow">Your day</div>
            <h2 class="section-title">My tasks</h2>
        </div>
        <div class="toolbar">
            <button class="tool-btn"><i class="bi bi-funnel"></i> Filter</button>
            <button class="tool-btn">Sort: Due date <i class="bi bi-chevron-down"></i></button>
        </div>
    </div>
@endsection