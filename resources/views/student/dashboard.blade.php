@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('page-title', 'My Dashboard')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection

@section('content')

@php
    /** @var \App\Models\User $student */
    /** @var \Illuminate\Support\Collection $all_quizzes */
    /** @var \Illuminate\Support\Collection $attempted_quiz_ids */
    /** @var \Illuminate\Support\Collection $upcoming_assignments */
    /** @var \Illuminate\Support\Collection $submitted_assignment_ids */

    // Pending = deadline not passed AND not yet attempted
    $pendingQuizzesCount = $all_quizzes
        ->filter(fn($quiz) => $quiz->deadline > now() && ! $attempted_quiz_ids->contains($quiz->id))
        ->count();

    $pendingAssignmentsCount = $upcoming_assignments
        ->filter(fn($a) => ! $submitted_assignment_ids->contains($a->id))
        ->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm border-0">
            <h2 class="text-primary fw-bold">{{ $pendingQuizzesCount }}</h2>
            <p class="mb-0 text-muted">Pending Quizzes</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm border-0">
            <h2 class="text-warning fw-bold">{{ $pendingAssignmentsCount }}</h2>
            <p class="mb-0 text-muted">Pending Assignments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3 shadow-sm border-0">
            <h2 class="text-success fw-bold">{{ $student->class->name ?? 'N/A' }}</h2>
            <p class="mb-0 text-muted">My Class</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><strong><i class="bi bi-journal-check me-2"></i>All Quizzes</strong></div>
            <div style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse($all_quizzes as $quiz)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $quiz->title }}</strong><br>
                                <small class="text-muted">{{ $quiz->subject->name ?? '' }}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-danger d-block">Due: {{ $quiz->deadline->format('d M') }}</small>
                                @if($attempted_quiz_ids->contains($quiz->id))
                                    <span class="badge bg-success">Attempted</span>
                                @elseif($quiz->deadline < now())
                                    <span class="badge bg-secondary">Expired</span>
                                @else
                                    <a href="{{ route('student.quizzes.attempt', $quiz) }}" class="btn btn-sm btn-primary">Attempt</a>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No quizzes available.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><strong><i class="bi bi-file-earmark-text me-2"></i>Upcoming Assignments</strong></div>
            <div style="max-height: 400px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse($upcoming_assignments as $assignment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $assignment->title }}</strong><br>
                                <small class="text-muted">{{ $assignment->subject->name ?? '' }}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-danger d-block">Due: {{ $assignment->deadline->format('d M') }}</small>
                                @if($submitted_assignment_ids->contains($assignment->id))
                                    <span class="badge bg-success">Submitted</span>
                                @else
                                    <a href="{{ route('student.assignments.show', $assignment) }}" class="btn btn-sm btn-warning">Submit</a>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No upcoming assignments.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection