@extends('layouts.app')
@section('title', 'Create Quiz')
@section('page-title', 'Create Quiz')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width:800px; margin: 0 auto; border-radius: 20px;">
    <div class="card-body">
        <h5 class="fw-normal text-primary mb-4 text-start"><i class="bi bi-plus-circle me-2 text-primary"></i>Create New Quiz</h5>

        @if($errors->any())
            <div class="alert alert-soft-danger border-0 mb-4" style="border-radius: 12px;">
                <ul class="mb-0 small fw-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.quizzes.store') }}" method="POST" id="quiz-form" class="text-start">
            @csrf

            <input type="hidden" name="class_id" id="class-id-hidden" value="{{ old('class_id') }}">

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Quiz Title *</label>
                <input type="text" name="title" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" required value="{{ old('title') }}" placeholder="Enter quiz title...">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Subject *</label>
                <select name="subject_id" class="form-select border-0 bg-light p-3" style="border-radius: 12px;" required id="subject-select">
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            data-class="{{ $subject->class_id }}"
                            {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->class->name ?? '' }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> Class will be automatically set from subject.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase">Number of Questions *</label>
                <div class="input-group">
                    <input type="number"
                           name="number_of_questions"
                           id="number-of-questions"
                           class="form-control border-0 bg-light p-3 @error('number_of_questions') is-invalid @enderror"
                           style="border-top-left-radius: 12px; border-bottom-left-radius: 12px;"
                           min="1"
                           placeholder="e.g. 5"
                           value="{{ old('number_of_questions') }}"
                           required>
                    <button type="button" class="btn btn-primary px-4" id="check-btn" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;">
                        <i class="bi bi-search me-1"></i> Check Question Bank
                    </button>
                </div>
                @error('number_of_questions')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="form-text mt-2"><i class="bi bi-shuffle me-1"></i> Questions will be picked randomly from your question bank.</div>

                <div id="bank-check-result" class="mt-3 p-3" style="display:none; border-radius: 12px;"></div>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold text-muted small text-uppercase">Deadline *</label>
                <input type="datetime-local" name="deadline" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" required value="{{ old('deadline') }}">
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-sm" id="submit-btn" style="border-radius: 12px;" disabled>
                    Create Quiz
                </button>
                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-light px-4 py-3 fw-bold text-muted" style="border-radius: 12px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var subjectSelect = document.getElementById('subject-select');
    var classIdHidden = document.getElementById('class-id-hidden');
    var numInput      = document.getElementById('number-of-questions');
    var checkBtn      = document.getElementById('check-btn');
    var resultBox     = document.getElementById('bank-check-result');
    var submitBtn     = document.getElementById('submit-btn');
    var CSRF_TOKEN    = '{{ csrf_token() }}';
    var CHECK_URL     = '{{ route("teacher.quizzes.checkQuestions") }}';
    var ADD_Q_URL     = '{{ route("teacher.questions.create") }}';

    subjectSelect.addEventListener('change', function () {
        var selected = this.options[this.selectedIndex];
        var classId  = selected ? selected.getAttribute('data-class') : null;
        classIdHidden.value = classId ? classId : '';
        resetCheck();
    });

    numInput.addEventListener('input', resetCheck);

    checkBtn.addEventListener('click', function () {
        var subjectId = subjectSelect.value;
        var numQ      = parseInt(numInput.value, 10);

        if (!subjectId) {
            showResult('warning', '⚠️ Please select a subject first.');
            return;
        }
        if (!numQ || numQ < 1) {
            showResult('warning', '⚠️ Please enter number of questions.');
            return;
        }

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
                    '✅ <strong>Question Bank</strong> has <strong>' + data.available + '</strong> questions. ' +
                    '<strong>' + data.requested + '</strong> will be randomly selected.');
                submitBtn.disabled = false;
            } else {
                showResult('danger',
                    '❌ Your <strong>Question Bank</strong> needs at least <strong>' + data.requested + '</strong> questions, but only <strong>' +
                    data.available + '</strong> exist. <a href="' + ADD_Q_URL + '" class="alert-link ms-1 text-decoration-underline">Add more questions &rarr;</a>');
                submitBtn.disabled = true;
            }
        })
        .catch(function (err) {
            showResult('danger', '❌ Error: ' + err.message);
            console.error(err);
        })
        .finally(function () {
            checkBtn.disabled   = false;
            checkBtn.innerHTML  = '<i class="bi bi-search me-1"></i> Check Question Bank';
        });
    });

    function showResult(type, html) {
        resultBox.className     = 'alert alert-soft-' + type + ' py-3 mb-0';
        resultBox.innerHTML     = '<div class="small fw-medium">' + html + '</div>';
        resultBox.style.display = 'block';
    }

    function resetCheck() {
        resultBox.style.display = 'none';
        submitBtn.disabled      = true;
    }
});
</script>
@endsection