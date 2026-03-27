@extends('layouts.app')
@section('title', 'Assignment')
@section('page-title', $assignment->title)
@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection
@section('content')

{{-- Assignment Info --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="mb-1"><strong>Subject:</strong> {{ $assignment->subject->name ?? '-' }}</p>
                <p class="mb-1"><strong>Total Marks:</strong> {{ $assignment->total_marks }}</p>
                <p class="mb-1">
                    <strong>Submission Type:</strong>
                    @if($assignment->isTextSubmission())
                        <span class="badge bg-info"><i class="bi bi-pencil-square me-1"></i>Written Answer</span>
                    @else
                        <span class="badge bg-primary"><i class="bi bi-file-earmark-arrow-up me-1"></i>File Upload</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-1">
                    <strong>Deadline:</strong>
                    <span class="{{ $assignment->isExpired() ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                        {{ $assignment->deadline->format('d M Y, h:i A') }}
                    </span>
                </p>
                @if($assignment->isExpired() && !$submission)
                    <div class="badge bg-danger">Deadline Passed — Late submission = 0 marks</div>
                @endif
            </div>
        </div>

        {{-- Instructions --}}
        @if($assignment->description)
        <div class="mb-3">
            <strong>Instructions from Teacher:</strong>
            <div class="border rounded p-3 bg-light mt-1">{{ $assignment->description }}</div>
        </div>
        @endif

        {{-- Assignment Document Download (using secure route) --}}
        @if($assignment->document_path)
        <div class="alert alert-primary d-flex align-items-center gap-3">
            <i class="bi bi-file-earmark-word fs-2 text-primary"></i>
            <div>
                <strong>Assignment Document</strong><br>
                <span class="text-muted small">Download the document to see the full assignment questions.</span><br>
                <a href="{{ route('download.assignment', $assignment) }}"
                   class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-download me-1"></i>Download Assignment Document
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- If already submitted --}}
@if($submission)
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Your Submission</strong>
        @if($submission->is_zero_marked)
            <span class="badge bg-danger">Late — 0 Marks</span>
        @elseif($submission->grade !== null)
            <span class="badge bg-success">Graded: {{ $submission->grade }}/{{ $assignment->total_marks }}</span>
        @else
            <span class="badge bg-warning text-dark">Awaiting Grade</span>
        @endif
    </div>
    <div class="card-body">
        @if($submission->solution_text)
            <strong>Your Written Answer:</strong>
            <div class="border rounded p-3 bg-light mb-3">{{ $submission->solution_text }}</div>
        @endif

        @if($submission->file_path)
            <a href="{{ route('download.submission', $submission) }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-download me-1"></i>Download Your Submitted File
            </a>
        @endif

        @if(!$submission->solution_text && !$submission->file_path)
            <div class="text-muted mb-3">No submission content.</div>
        @endif

        @if($submission->feedback)
        <div class="alert alert-info mb-0">
            <strong><i class="bi bi-chat-left-text me-1"></i>Teacher Feedback:</strong><br>
            {{ $submission->feedback }}
        </div>
        @endif

        @if($submission->is_zero_marked)
        <div class="alert alert-danger mb-0 mt-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Your submission was received after the deadline. <strong>0 marks</strong> have been assigned automatically.
        </div>
        @endif
    </div>
</div>

{{-- If not yet submitted --}}
@else
<div class="card">
    <div class="card-header bg-white"><strong>Submit Your Answer</strong></div>
    <div class="card-body">
        <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Show ONLY the relevant submission field based on teacher's choice --}}
            @if($assignment->isTextSubmission())
                <div class="mb-3">
                    <label class="form-label fw-semibold">Your Written Answer *</label>
                    <textarea name="solution_text" class="form-control" rows="7" required
                        placeholder="Type your answer here...">{{ old('solution_text') }}</textarea>
                    @error('solution_text')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="mb-4">
                    <label class="form-label fw-semibold">Upload Your Answer File *</label>
                    <input type="file" name="file" class="form-control" required accept=".doc,.docx,.pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Accepted formats: Word (.doc, .docx), PDF (.pdf), Image (.jpg, .png) — Max 10 MB</div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <button type="submit" class="btn btn-success"
                onclick="return confirm('Submit assignment? You cannot change it after submission.')">
                <i class="bi bi-send me-1"></i>Submit Assignment
            </button>
        </form>
    </div>
</div>
@endif

@endsection