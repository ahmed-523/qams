@extends('layouts.app')
@section('title', 'Student Profile')
@section('page-title', 'Student Profile')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="active"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <i class="bi bi-person-circle" style="font-size:4rem;color:#1e3a5f"></i>
            <h5 class="mt-2">{{ $student->user->name }}</h5>
            <p class="text-muted mb-1"><code>{{ $student->user->username }}</code></p>
            <span class="badge {{ $student->user->is_blocked ? 'bg-danger' : 'bg-success' }}">{{ $student->user->is_blocked ? 'Blocked' : 'Active' }}</span>
            <hr>
            <p class="mb-1"><strong>Admission No:</strong> {{ $student->admission_number }}</p>
            <p class="mb-1"><strong>Father's Name:</strong> {{ $student->father_name ?? '-' }}</p>
            <p class="mb-0"><strong>Class:</strong> {{ $student->class->name ?? '-' }}</p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white"><strong>Quiz Results</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Quiz</th><th>Score</th><th>Total</th><th>%</th></tr></thead>
                    <tbody>
                        @forelse($student->quizAttempts as $attempt)
                        <tr>
                            <td>{{ $attempt->quiz->title ?? '-' }}</td>
                            <td>{{ $attempt->score }}</td>
                            <td>{{ $attempt->total_marks }}</td>
                            <td>{{ $attempt->total_marks > 0 ? round(($attempt->score/$attempt->total_marks)*100,1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No quiz attempts.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white"><strong>Assignment Results</strong></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Assignment</th><th>Grade</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($student->assignmentSubmissions as $sub)
                        <tr>
                            <td>{{ $sub->assignment->title ?? '-' }}</td>
                            <td>{{ $sub->grade ?? 'Not graded' }}</td>
                            <td>
                                @if($sub->is_zero_marked)<span class="badge bg-danger">Late/Zero</span>
                                @elseif($sub->is_late)<span class="badge bg-warning">Late</span>
                                @else<span class="badge bg-success">On Time</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">No submissions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
