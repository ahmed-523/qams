@extends('layouts.app')
@section('title', 'Add Question')
@section('page-title', 'Add Question to Bank')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}" class="active"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="card" style="max-width:680px">
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

        <form action="{{ route('teacher.questions.store') }}" method="POST" id="qform">
            @csrf
            <div class="row g-3">

                {{-- Subject --}}
                <div class="col-md-6">
                    <label class="form-label">Subject *</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Question Type: MCQ and True/False only --}}
                <div class="col-md-6">
                    <label class="form-label">Question Type *</label>
                    <select name="question_type" id="q-type" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        <option value="mcq"       {{ old('question_type') == 'mcq'       ? 'selected' : '' }}>MCQ (Multiple Choice)</option>
                        <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>True / False</option>
                    </select>
                </div>

                {{-- Question Text --}}
                <div class="col-12">
                    <label class="form-label">Question Text *</label>
                    <textarea name="question_text" class="form-control" rows="3" required>{{ old('question_text') }}</textarea>
                </div>

                {{-- MCQ Options --}}
                <div class="col-12" id="mcq-options" style="display:none">
                    <label class="form-label">MCQ Options <span class="text-danger">*</span></label>
                    @for($i = 0; $i < 4; $i++)
                        <input type="text"
                               name="options[]"
                               id="option-{{ $i }}"
                               class="form-control mb-2 mcq-option-input"
                               placeholder="Option {{ $i + 1 }}"
                               value="{{ old('options.' . $i) }}">
                    @endfor
                    <small class="text-muted">Fill all 4 options. Correct answer dropdown updates automatically.</small>
                </div>

                {{-- Correct Answer --}}
                <div class="col-md-6">
                    <label class="form-label">Correct Answer *</label>

                    {{-- MCQ: populated from option inputs --}}
                    <select name="correct_answer" id="correct-answer-mcq" class="form-select" style="display:none">
                        <option value="">-- Select correct option --</option>
                        @for($i = 0; $i < 4; $i++)
                            @if(old('options.' . $i))
                                <option value="{{ old('options.' . $i) }}"
                                    {{ old('correct_answer') == old('options.' . $i) ? 'selected' : '' }}>
                                    {{ old('options.' . $i) }}
                                </option>
                            @endif
                        @endfor
                    </select>

                    {{-- True/False --}}
                    <select name="correct_answer" id="correct-answer-tf" class="form-select" style="display:none">
                        <option value="">-- Select --</option>
                        <option value="True"  {{ old('correct_answer') == 'True'  ? 'selected' : '' }}>True</option>
                        <option value="False" {{ old('correct_answer') == 'False' ? 'selected' : '' }}>False</option>
                    </select>

                    <small class="text-muted" id="answer-hint"></small>
                </div>

                {{-- Marks --}}
                <div class="col-md-6">
                    <label class="form-label">Marks *</label>
                    <input type="number" name="marks" class="form-control" min="1"
                           value="{{ old('marks', 1) }}" required>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Add Question
                </button>
                <a href="{{ route('teacher.questions.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var qType        = document.getElementById('q-type');
    var mcqOptions   = document.getElementById('mcq-options');
    var answerMcq    = document.getElementById('correct-answer-mcq');
    var answerTf     = document.getElementById('correct-answer-tf');
    var answerHint   = document.getElementById('answer-hint');
    var optionInputs = document.querySelectorAll('.mcq-option-input');

    function toggleOptions() {
        var type = qType.value;

        [answerMcq, answerTf].forEach(function (el) {
            el.style.display = 'none';
            el.removeAttribute('required');
            el.name = '';
        });
        optionInputs.forEach(function (el) { el.removeAttribute('required'); });
        mcqOptions.style.display = 'none';
        answerHint.textContent   = '';

        if (type === 'mcq') {
            mcqOptions.style.display = 'block';
            answerMcq.style.display  = 'block';
            answerMcq.setAttribute('required', 'required');
            answerMcq.name = 'correct_answer';
            optionInputs.forEach(function (el) { el.setAttribute('required', 'required'); });
            answerHint.textContent = 'Select one of the 4 options as correct.';
            syncMcqDropdown();
        } else if (type === 'true_false') {
            answerTf.style.display = 'block';
            answerTf.setAttribute('required', 'required');
            answerTf.name = 'correct_answer';
            answerHint.textContent = 'Choose True or False.';
        }
    }

    function syncMcqDropdown() {
        var previous = answerMcq.value;
        answerMcq.innerHTML = '<option value="">-- Select correct option --</option>';
        optionInputs.forEach(function (input, idx) {
            var val = input.value.trim();
            if (val) {
                var opt = document.createElement('option');
                opt.value       = val;
                opt.textContent = 'Option ' + (idx + 1) + ': ' + val;
                if (val === previous) opt.selected = true;
                answerMcq.appendChild(opt);
            }
        });
    }

    qType.addEventListener('change', toggleOptions);
    optionInputs.forEach(function (input) {
        input.addEventListener('input', syncMcqDropdown);
    });

    toggleOptions();
});
</script>
@endsection