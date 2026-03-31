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

@php $totalQuestions = $quiz->questions->count(); @endphp

<style>
.quiz-wrapper { max-width: 750px; margin: 0 auto; }

.quiz-header {
    background: linear-gradient(135deg, #1e3a5f, #2d5491);
    border-radius: 16px;
    padding: 20px 24px;
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.quiz-meta { font-size: 0.9rem; opacity: 0.85; }

.timer-box {
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 10px 18px;
    text-align: center;
    min-width: 100px;
    backdrop-filter: blur(4px);
}
.timer-box .timer-digits {
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    line-height: 1;
}
.timer-box .timer-label { font-size: 0.7rem; opacity: 0.8; margin-top: 2px; }
.timer-box.warning .timer-digits { color: #ffc107; }
.timer-box.danger  .timer-digits { color: #ff6b6b; animation: pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }

.progress-thin { height: 6px; border-radius: 3px; background: rgba(255,255,255,0.2); margin-top: 14px; }
.progress-thin .bar { height: 100%; border-radius: 3px; background: #4ade80; transition: width 1s linear, background 0.5s; }

.question-counter {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.q-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    border: 2px solid #dee2e6;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 600;
    cursor: default;
    background: white;
    color: #6c757d;
}
.q-dot.current  { border-color: #0d6efd; background: #0d6efd; color: white; }
.q-dot.answered { border-color: #198754; background: #198754; color: white; }
.q-dot.locked   { border-color: #adb5bd; background: #e9ecef; color: #adb5bd; }

.question-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 28px;
    margin-bottom: 20px;
    display: none;
}
.question-card.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.question-number {
    font-size: 0.8rem;
    font-weight: 600;
    color: #0d6efd;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}
.question-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 20px;
    line-height: 1.5;
}

.option-label {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    margin-bottom: 10px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    cursor: pointer;
    transition: all 0.2s;
    background: #fafafa;
}
.option-label:hover { border-color: #0d6efd; background: #f0f4ff; }
.option-label.is-selected {
    border-color: #0d6efd;
    background: #e8f0fe;
    font-weight: 600;
}
.option-label.is-selected .option-circle {
    background: #0d6efd;
    border-color: #0d6efd;
}
.option-label.is-selected .option-circle::after { display: block; }
.option-label.locked-option {
    pointer-events: none;
    opacity: 0.7;
    cursor: not-allowed;
}

.option-circle {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 2px solid #adb5bd;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    position: relative;
}
.option-circle::after {
    content: '';
    width: 10px; height: 10px;
    border-radius: 50%;
    background: white;
    display: none;
}

.nav-buttons {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
}
.btn-nav {
    padding: 10px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 8px;
}
.btn-next { background: #0d6efd; color: white; }
.btn-next:hover { background: #0b5ed7; }
.btn-submit-final { background: #198754; color: white; }
.btn-submit-final:hover { background: #157347; }

.q-status {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    margin-right: auto;
}

.locked-notice {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.82rem;
    color: #856404;
    margin-top: 14px;
    display: none;
}
</style>

<form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quiz-form">
@csrf

<div class="quiz-wrapper">

    {{-- Header --}}
    <div class="quiz-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h5 class="mb-1 fw-bold">{{ $quiz->title }}</h5>
                <div class="quiz-meta">
                    <span><i class="bi bi-book me-1"></i>{{ $quiz->subject->name ?? '' }}</span>
                    &nbsp;&nbsp;
                    <span><i class="bi bi-star me-1"></i>{{ $quiz->total_marks }} Marks</span>
                    &nbsp;&nbsp;
                    <span><i class="bi bi-list-ol me-1"></i>{{ $totalQuestions }} Questions</span>
                </div>
            </div>
            <div class="timer-box" id="timer-box">
                <div class="timer-digits" id="timer-display">01:30</div>
                <div class="timer-label">Time per Question</div>
            </div>
        </div>
        <div class="progress-thin">
            <div class="bar" id="timer-bar" style="width:100%"></div>
        </div>
    </div>

    {{-- Question Dots --}}
    <div class="question-counter" id="question-dots"></div>

    {{-- Questions --}}
    @foreach($quiz->questions as $index => $question)
    <div class="question-card {{ $index === 0 ? 'active' : '' }}" id="qcard-{{ $index }}">

        <div class="question-number">Question {{ $index + 1 }} of {{ $totalQuestions }}</div>
        <div class="question-text">{{ $question->question_text }}</div>
        <span class="badge bg-light text-secondary border mb-3">{{ $question->marks }} mark(s)</span>

        @if($question->question_type === 'mcq')
            @foreach($question->options as $option)
            <label class="option-label" onclick="selectOption(this, {{ $index }})">
                <div class="option-circle"></div>
                <input type="radio"
                       name="answers[{{ $question->id }}]"
                       value="{{ $option }}"
                       class="option-radio"
                       style="display:none;">
                <span>{{ $option }}</span>
            </label>
            @endforeach

        @elseif($question->question_type === 'true_false')
            @foreach(['True', 'False'] as $option)
            <label class="option-label" onclick="selectOption(this, {{ $index }})">
                <div class="option-circle"></div>
                <input type="radio"
                       name="answers[{{ $question->id }}]"
                       value="{{ $option }}"
                       class="option-radio"
                       style="display:none;">
                <span>{{ $option }}</span>
            </label>
            @endforeach
        @endif

        <div class="locked-notice" id="locked-notice-{{ $index }}">
            <i class="bi bi-lock-fill me-1"></i> This question is locked. You cannot change your answer.
        </div>

        <div class="nav-buttons">
            <span class="q-status" id="status-{{ $index }}">Not answered</span>

            @if($index === $totalQuestions - 1)
                <button type="button" class="btn-nav btn-submit-final" onclick="submitQuiz()">
                    <i class="bi bi-check-circle me-1"></i> Submit Quiz
                </button>
            @else
                <button type="button" class="btn-nav btn-next" id="next-btn-{{ $index }}" onclick="nextQuestion({{ $index }})">
                    Next <i class="bi bi-arrow-right"></i>
                </button>
            @endif
        </div>
    </div>
    @endforeach

</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var totalQ      = {{ $totalQuestions }};
    var currentQ    = 0;
    var answered    = {};
    var locked      = {};
    var isSubmitted = false;
    var SECS_PER_Q  = 90;
    var remaining   = SECS_PER_Q;
    var timerInterval;

    // Build question dots — no click allowed
    var dotsContainer = document.getElementById('question-dots');
    for (var i = 0; i < totalQ; i++) {
        var dot = document.createElement('div');
        dot.className = 'q-dot' + (i === 0 ? ' current' : ' locked');
        dot.textContent = i + 1;
        dot.setAttribute('id', 'dot-' + i);
        dotsContainer.appendChild(dot);
    }

    function updateDots() {
        for (var i = 0; i < totalQ; i++) {
            var dot = document.getElementById('dot-' + i);
            dot.className = 'q-dot';
            if (i === currentQ)       dot.classList.add('current');
            else if (answered[i])     dot.classList.add('answered');
            else                      dot.classList.add('locked');
        }
    }

    // Move to next question only — NO going back
    function moveNext() {
        if (currentQ >= totalQ - 1) return;
        // Lock current question
        locked[currentQ] = true;
        lockCurrentQuestion();

        document.getElementById('qcard-' + currentQ).classList.remove('active');
        currentQ++;
        document.getElementById('qcard-' + currentQ).classList.add('active');
        updateDots();
        resetTimer();
    }

    window.nextQuestion = function(index) {
        moveNext();
    };

    function lockCurrentQuestion() {
        var card = document.getElementById('qcard-' + currentQ);
        // Lock all options
        card.querySelectorAll('.option-label').forEach(function(l) {
            l.classList.add('locked-option');
        });
        // Show locked notice
        var notice = document.getElementById('locked-notice-' + currentQ);
        if (notice) notice.style.display = 'block';
        // Hide next button
        var nextBtn = document.getElementById('next-btn-' + currentQ);
        if (nextBtn) nextBtn.style.display = 'none';
    }

    window.selectOption = function(label, qIndex) {
        if (locked[qIndex]) return; // already locked — cannot change
        var card = label.closest('.question-card');
        card.querySelectorAll('.option-label').forEach(function(l) {
            l.classList.remove('is-selected');
        });
        label.classList.add('is-selected');
        label.querySelector('.option-radio').checked = true;
        answered[qIndex] = true;
        var statusEl = document.getElementById('status-' + qIndex);
        if (statusEl) statusEl.textContent = '✓ Answered';
        updateDots();
    };

    window.submitQuiz = function() {
        if (isSubmitted) return;
        if (!confirm('Submit quiz? This cannot be undone.')) return;
        isSubmitted = true;
        clearInterval(timerInterval);
        document.getElementById('quiz-form').submit();
    };

    // ── Per-question Timer ───────────────────────────────────────
    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function resetTimer() {
        clearInterval(timerInterval);
        remaining = SECS_PER_Q;
        startTimer();
    }

    function startTimer() {
        var display  = document.getElementById('timer-display');
        var bar      = document.getElementById('timer-bar');
        var timerBox = document.getElementById('timer-box');

        function tick() {
            if (isSubmitted) return;
            display.textContent = pad(Math.floor(remaining / 60)) + ':' + pad(remaining % 60);
            bar.style.width = ((remaining / SECS_PER_Q) * 100) + '%';

            timerBox.className = 'timer-box';
            if (remaining <= 10) {
                timerBox.classList.add('danger');
                bar.style.background = '#ff6b6b';
            } else if (remaining <= 30) {
                timerBox.classList.add('warning');
                bar.style.background = '#ffc107';
            } else {
                bar.style.background = '#4ade80';
            }

            if (remaining <= 0) {
                clearInterval(timerInterval);
                // Time up — auto move next or submit
                if (currentQ < totalQ - 1) {
                    moveNext();
                } else {
                    if (!isSubmitted) {
                        isSubmitted = true;
                        alert('Time is up! Quiz is being submitted automatically.');
                        document.getElementById('quiz-form').submit();
                    }
                }
                return;
            }
            remaining--;
        }

        tick();
        timerInterval = setInterval(tick, 1000);
    }

    startTimer();
    updateDots();
});
</script>
@endsection