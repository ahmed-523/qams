@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-primary">{{ $stats['total_students'] }}</h2><p class="mb-0 text-muted">Students</p></div></div>
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-success">{{ $stats['total_teachers'] }}</h2><p class="mb-0 text-muted">Teachers</p></div></div>
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-info">{{ $stats['total_classes'] }}</h2><p class="mb-0 text-muted">Classes</p></div></div>
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-warning">{{ $stats['total_subjects'] }}</h2><p class="mb-0 text-muted">Subjects</p></div></div>
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-danger">{{ $stats['total_quizzes'] }}</h2><p class="mb-0 text-muted">Quizzes</p></div></div>
    <div class="col-md-2"><div class="card text-center p-3"><h2 class="text-secondary">{{ $stats['total_assignments'] }}</h2><p class="mb-0 text-muted">Assignments</p></div></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><strong><i class="bi bi-journal-check me-2"></i>Recent Quizzes</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($recent_quizzes as $quiz)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $quiz->title }}</span>
                        <small class="text-muted">{{ $quiz->deadline->format('d M Y') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No quizzes yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><strong><i class="bi bi-file-earmark-text me-2"></i>Recent Assignments</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($recent_assignments as $a)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $a->title }}</span>
                        <small class="text-muted">{{ $a->deadline->format('d M Y') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No assignments yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
