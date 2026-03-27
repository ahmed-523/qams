@extends('layouts.app')
@section('title', 'Assignments')
@section('page-title', 'My Assignments')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>All Assignments</h5>
    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary"
       data-bs-toggle="tooltip" title="Create a new assignment">
        <i class="bi bi-plus-lg me-1"></i>Create Assignment
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Title</th><th>Subject</th><th>Class</th><th>Total Marks</th><th>Deadline</th><th>Submissions</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $a->title }}</strong></td>
                    <td>{{ $a->subject->name ?? '-' }}</td>
                    <td>{{ $a->class->name ?? '-' }}</td>
                    <td>{{ $a->total_marks }}</td>
                    <td class="{{ $a->isExpired() ? 'text-danger' : 'text-success' }}">{{ $a->deadline->format('d M Y, h:i A') }}</td>
                    <td><span class="badge bg-info">{{ $a->submissions->count() }}</span></td>
                    <td>
                        <a href="{{ route('teacher.assignments.submissions', $a) }}"
                           class="btn btn-sm btn-outline-primary"
                           data-bs-toggle="tooltip" title="View Submissions & Grade">
                            <i class="bi bi-list-check"></i>
                        </a>
                        <a href="{{ route('teacher.assignments.edit', $a) }}"
                           class="btn btn-sm btn-outline-warning"
                           data-bs-toggle="tooltip" title="Edit / Extend Deadline">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No assignments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endsection