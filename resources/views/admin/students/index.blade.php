@extends('layouts.app')
@section('title', 'Students')
@section('page-title', 'Students Management')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}" class="active"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>All Students</h5>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary"
       data-bs-toggle="tooltip" title="Register a new student">
        <i class="bi bi-plus-lg me-1"></i>Register Student
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Name</th><th>Username</th><th>Admission No.</th><th>Father's Name</th><th>Class</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td><code>{{ $student->user->username }}</code></td>
                    <td>{{ $student->admission_number }}</td>
                    <td>{{ $student->father_name ?? '-' }}</td>
                    <td>{{ $student->class->name ?? '-' }}</td>
                    <td>
                        @if($student->user->is_blocked)
                            <span class="badge bg-danger">Blocked</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.students.show', $student) }}"
                           class="btn btn-sm btn-outline-info"
                           data-bs-toggle="tooltip" title="View Student Profile">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.students.edit', $student) }}"
                           class="btn btn-sm btn-outline-warning"
                           data-bs-toggle="tooltip" title="Edit Student Info">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.students.block', $student) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm {{ $student->user->is_blocked ? 'btn-outline-success' : 'btn-outline-danger' }}"
                                    data-bs-toggle="tooltip"
                                    title="{{ $student->user->is_blocked ? 'Unblock this student account' : 'Block this student account' }}">
                                {{ $student->user->is_blocked ? 'Unblock' : 'Block' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No students registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endsection