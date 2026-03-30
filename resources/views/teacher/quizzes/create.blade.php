@extends('layouts.app')
@section('title', 'Create Quiz')
@section('page-title', 'Create New Quiz')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.quizzes.store') }}" method="POST" id="quiz-form">
            @csrf

            <div class="mb-3">
                <label class="form-label">Quiz Title *</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Subject *</label>
                    <select name="subject_id" class="form-select" required id="subject-select">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                data-class="{{ $subject->class_id }}"
                                {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->class->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class *</label>
                    {{-- Hidden input to actually submit the value since disabled fields are not submitted --}}
                    <input type="hidden" name="class_id" id="class-id-hidden" value="{{ old('class_id') }}">
                    <select class="form-select" id="class-select" disabled>
                        <option value="">-- Auto-filled from Subject --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Class is automatically set based on selected subject.</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Number of Questions *</label>
                <div class="input-group">
                    <input type="number"
                           name="number_of_questions"
                           id="number-of-questions"
                           class="form-control @error('number_of_questions') is-invalid @enderror"
                           min="1"
                           placeholder="e.g. 5"
                           value="{{ old('number_of_questions') }}"
                           required>
                    <button type="button" class="btn btn-outline-secondary" id="check-btn">
                        <i class="bi bi-search me-1"></i> Check Bank
                    </button>
                </div>
                @error('number_of_questions')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <small class="text-muted">Questions will be picked randomly from your question bank.</small>
                <div id="bank-check-result" class="mt-2" style="display:none;"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline *</label>
                <input type="datetime-local" name="deadline" class="form-control" required value="{{ old('deadline') }}">
            </div>

            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                <i class="bi bi-check-lg me-1"></i>Create Quiz
            </button>
            <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var subjectSelect  = document.getElementById('subject-select');
    var classSelect    = document.getElementById('class-select');
    var classIdHidden  = document.getElementById('class-id-hidden');
    var numInput       = document.getElementById('number-of-questions');
    var checkBtn       = document.getElementById('check-btn');
    var resultBox      = document.getElementById('bank-check-result');
    var submitBtn      = document.getElementById('submit-btn');
    var CSRF_TOKEN     = '{{ csrf_token() }}';
    var CHECK_URL      = '{{ route("teacher.quizzes.checkQuestions") }}';
    var ADD_Q_URL      = '{{ route("teacher.questions.create") }}';

    subjectSelect.addEventListener('change', function () {
        var selected = this.options[this.selectedIndex];
        var classId  = selected ? selected.getAttribute('data-class') : null;

        if (classId) {
            // Set the visible disabled dropdown
            classSelect.value = classId;
            // Set the hidden input that actually submits
            classIdHidden.value = classId;
        } else {
            classSelect.value   = '';
            classIdHidden.value = '';
        }
        resetCheck();
    });

    numInput.addEventListener('input', resetCheck);

    checkBtn.addEventListener('click', function () {
        var subjectId = subjectSelect.value;
        var numQ      = parseInt(numInput.value, 10);

        if (!subjectId) { showResult('warning', '&#9888; Please select a subject first.'); return; }
        if (!numQ || numQ < 1) { showResult('warning', '&#9888; Please enter number of questions.'); return; }

        checkBtn.disabled    = true;
        checkBtn.textContent = 'Checking...';

        fetch(CHECK_URL, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept':           'application/json',
            },
            body: JSON.stringify({ subject_id: subjectId, number_of_questions: numQ }),
        })
        .then(function (r) {
            if (!r.ok) throw new Error('Status ' + r.status);
            return r.json();
        })
        .then(function (data) {
            if (data.enough) {
                showResult('success',
                    '&#10003; Bank has <strong>' + data.available + '</strong> questions. ' +
                    '<strong>' + data.requested + '</strong> will be randomly selected.');
                submitBtn.disabled = false;
            } else {
                showResult('danger',
                    '&#10007; Need <strong>' + data.requested + '</strong> but only <strong>' +
                    data.available + '</strong> exist. <a href="' + ADD_Q_URL + '" class="alert-link">Add more &rarr;</a>');
                submitBtn.disabled = true;
            }
        })
        .catch(function (err) {
            showResult('danger', '&#10007; ' + err.message);
            console.error(err);
        })
        .finally(function () {
            checkBtn.disabled   = false;
            checkBtn.innerHTML  = '<i class="bi bi-search me-1"></i> Check Bank';
        });
    });

    function showResult(type, html) {
        resultBox.className     = 'alert alert-' + type + ' py-2 mb-0';
        resultBox.innerHTML     = html;
        resultBox.style.display = 'block';
    }

    function resetCheck() {
        resultBox.style.display = 'none';
        submitBtn.disabled      = true;
    }
});
</script>
@endsection