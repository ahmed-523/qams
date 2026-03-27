@extends('layouts.app')
@section('title', 'Create Assignment')
@section('page-title', 'Create Assignment')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">
        <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Assignment Title *</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title') }}" placeholder="e.g. Chapter 3 Exercise">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Subject *</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->class->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Class *</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Deadline *</label>
                    <input type="datetime-local" name="deadline" class="form-control" required value="{{ old('deadline') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Total Marks *</label>
                    <input type="number" name="total_marks" class="form-control" min="1" value="{{ old('total_marks', 100) }}" required>
                </div>
            </div>

            {{-- Submission Type --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Student Submission Type *</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="submission_type" id="type_text" value="text"
                            {{ old('submission_type', 'text') === 'text' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_text">
                            <i class="bi bi-pencil-square me-1"></i> Written Answer (Text Box)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="submission_type" id="type_file" value="file"
                            {{ old('submission_type') === 'file' ? 'checked' : '' }}>
                        <label class="form-check-label" for="type_file">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i> File Upload (Word/PDF)
                        </label>
                    </div>
                </div>
                <div class="form-text">Choose how students will submit their answers for this assignment.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Brief Instructions</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Short instructions shown to students...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Assignment Document <span class="text-muted">(Word / PDF)</span></label>
                <input type="file" name="document" class="form-control" accept=".doc,.docx,.pdf">
                <div class="form-text">Upload a <strong>.doc</strong>, <strong>.docx</strong>, or <strong>.pdf</strong> file containing the full assignment questions. Students will be able to download this file. Max size: 10 MB.</div>
            </div>

            <div class="alert alert-warning py-2 mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Students who submit after the deadline automatically receive <strong>0 marks</strong>.
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>Create Assignment</button>
            <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection