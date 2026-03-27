@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Student Performance Report')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}" class="active"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Student</th><th>Class</th><th>Quizzes Attempted</th><th>Avg Quiz %</th><th>Assignments</th><th>Avg Assignment Grade</th></tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                    $attempts   = $student->quizAttempts;
                    $avgQuiz    = $attempts->count() > 0 ? round($attempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score/$a->total_marks)*100 : 0),1) : '-';
                    $subs       = $student->assignmentSubmissions->whereNotNull('grade');
                    $avgAssign  = $subs->count() > 0 ? round($subs->avg('grade'),1) : '-';
                @endphp
                <tr>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->class->name ?? '-' }}</td>
                    <td>{{ $attempts->count() }}</td>
                    <td>{{ $avgQuiz }}{{ is_numeric($avgQuiz) ? '%' : '' }}</td>
                    <td>{{ $student->assignmentSubmissions->count() }}</td>
                    <td>{{ $avgAssign }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No students in your classes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
