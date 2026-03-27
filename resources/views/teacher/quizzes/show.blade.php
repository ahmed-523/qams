@extends('layouts.app')
@section('title', 'Quiz Details')
@section('page-title', $quiz->title)
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Subject:</strong> {{ $quiz->subject->name ?? '-' }}</p>
                <p><strong>Class:</strong> {{ $quiz->class->name ?? '-' }}</p>
                <p><strong>Total Marks:</strong> {{ $quiz->total_marks }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Deadline:</strong> <span class="{{ $quiz->isExpired() ? 'text-danger' : 'text-success' }}">{{ $quiz->deadline->format('d M Y, h:i A') }}</span></p>
                <p><strong>Results:</strong> @if($quiz->is_result_published)<span class="badge bg-success">Published</span>@else<span class="badge bg-secondary">Hidden</span>@endif</p>
                <p><strong>Attempts:</strong> {{ $quiz->attempts->count() }}</p>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header bg-white"><strong>Questions ({{ $quiz->questions->count() }})</strong></div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>#</th><th>Question</th><th>Type</th><th>Correct Answer</th><th>Marks</th></tr></thead>
            <tbody>
                @foreach($quiz->questions as $q)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $q->question_text }}</td>
                    <td><span class="badge bg-secondary">{{ strtoupper($q->question_type) }}</span></td>
                    <td><code>{{ $q->correct_answer }}</code></td>
                    <td>{{ $q->marks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
