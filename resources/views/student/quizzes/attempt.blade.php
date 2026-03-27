@extends('layouts.app')
@section('title', 'Attempt Quiz')
@section('page-title', 'Attempt: ' . $quiz->title)

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection

@section('content')

{{-- Quiz Info + Timer --}}
<div class="card mb-3 border-primary">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1">{{ $quiz->title }}</h5>
                <p class="text-muted mb-0">
                    Subject: <strong>{{ $quiz->subject->name ?? '' }}</strong>
                    &nbsp;|&nbsp; Total Marks: <strong>{{ $quiz->total_marks }}</strong>
                    &nbsp;|&nbsp; Questions: <strong>{{ $quiz->questions->count() }}</strong>
                </p>
            </div>
            <div class="text-center">
                <div class="fs-4 fw-bold text-white rounded px-3 py-1" id="timer-display"
                     style="background:#198754;min-width:90px;">--:--</div>
                <small class="text-muted">Time Remaining</small>
            </div>
        </div>
        <div class="progress mt-3" style="height:8px;">
            <div class="progress-bar bg-success" id="timer-bar" role="progressbar" style="width:100%"></div>
        </div>
    </div>
</div>

<form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quiz-form">
    @csrf
    @foreach($quiz->questions as $index => $question)
    <div class="card mb-3">
        <div class="card-body">
            <p class="fw-semibold mb-3">
                Q{{ $index + 1 }}. {{ $question->question_text }}
                <span class="badge bg-secondary ms-1">{{ $question->marks }} mark(s)</span>
            </p>

            @if($question->question_type === 'mcq')
                @foreach($question->options as $option)
                <label class="option-label d-flex align-items-center gap-3 p-3 mb-2 rounded border"
                       style="cursor:pointer;transition:all 0.15s;">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $option }}"
                           class="option-radio shrink-0"
                           style="width:20px;height:20px;accent-color:#0d6efd;cursor:pointer;">
                    <span>{{ $option }}</span>
                </label>
                @endforeach

            @elseif($question->question_type === 'true_false')
                @foreach(['True', 'False'] as $option)
                <label class="option-label d-flex align-items-center gap-3 p-3 mb-2 rounded border"
                       style="cursor:pointer;transition:all 0.15s;">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $option }}"
                           class="option-radio shrink-0"
                           style="width:20px;height:20px;accent-color:#0d6efd;cursor:pointer;">
                    <span>{{ $option }}</span>
                </label>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach

    <div class="text-end mb-4">
        <button type="submit" class="btn btn-success btn-lg" id="submit-btn"
            onclick="return confirm('Submit quiz? You cannot change your answers after submission.')">
            <i class="bi bi-check-circle me-1"></i>Submit Quiz
        </button>
    </div>
</form>

<style>
.option-label:hover { background-color:#f0f4ff; }
.option-label.is-selected {
    background-color:#e0ebff;
    border-color:#0d6efd !important;
    font-weight:600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Highlight selected option
    document.querySelectorAll('.option-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const name = this.getAttribute('name');
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.option-label').classList.remove('is-selected');
            });
            this.closest('.option-label').classList.add('is-selected');
        });
    });

    // ── Timer Logic (Fixed) ─────────────────────────────────────
    let totalSeconds = {{ $quiz->questions->count() * 90 }};
    let remaining    = totalSeconds;
    let isSubmitted  = false;                    // ← prevents double submit

    const display = document.getElementById('timer-display');
    const bar     = document.getElementById('timer-bar');
    const form    = document.getElementById('quiz-form');

    function pad(n) { return n < 10 ? '0' + n : n; }

    function tick() {
        if (isSubmitted) return;

        const mins = Math.floor(remaining / 60);
        const secs = remaining % 60;
        display.textContent = pad(mins) + ':' + pad(secs);

        const pct = totalSeconds > 0 ? (remaining / totalSeconds) * 100 : 100;
        bar.style.width = pct + '%';

        // Color change
        if (remaining <= 30) {
            display.style.background = '#dc3545';
            bar.className = 'progress-bar bg-danger';
        } else if (remaining <= totalSeconds * 0.4) {
            display.style.background = '#fd7e14';
            bar.className = 'progress-bar bg-warning';
        } else {
            display.style.background = '#198754';
            bar.className = 'progress-bar bg-success';
        }

        if (remaining <= 0) {
            clearInterval(timer);
            display.textContent = '00:00';
            isSubmitted = true;
            alert('Time is up! Your quiz is being submitted automatically.');
            form.submit();
            return;
        }
        remaining--;
    }

    // Start timer
    if (totalSeconds > 0) {
        var timer = setInterval(tick, 1000);
        tick();                    // immediate first tick
    } else {
        display.textContent = 'No time limit';
    }
});
</script>
@endsection