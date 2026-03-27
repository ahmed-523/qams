@extends('layouts.app')
@section('title', 'Teachers')
@section('page-title', 'Teachers Management')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="active"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>All Teachers</h5>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"
       data-bs-toggle="tooltip" title="Register a new teacher">
        <i class="bi bi-plus-lg me-1"></i>Register Teacher
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Name</th><th>Username</th><th>Education</th><th>Assigned Subjects</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $teacher->user->name }}</strong></td>
                    <td><code>{{ $teacher->user->username }}</code></td>
                    <td>{{ $teacher->education ?? '-' }}</td>
                    <td>
                        @forelse($teacher->subjects->take(3) as $s)
                            <span class="badge bg-secondary me-1">{{ $s->name }}</span>
                        @empty
                            <span class="text-danger small"><i class="bi bi-exclamation-circle me-1"></i>None assigned</span>
                        @endforelse
                        @if($teacher->subjects->count() > 3)
                            <span class="text-muted small">+{{ $teacher->subjects->count()-3 }} more</span>
                        @endif
                    </td>
                    <td>
                        @if($teacher->user->is_blocked)
                            <span class="badge bg-danger">Blocked</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.teachers.show', $teacher) }}"
                           class="btn btn-sm btn-success mb-1"
                           data-bs-toggle="tooltip" title="Assign or remove subjects">
                            <i class="bi bi-book me-1"></i>Assign Subjects
                        </a>
                        <a href="{{ route('admin.teachers.edit', $teacher) }}"
                           class="btn btn-sm btn-outline-warning mb-1"
                           data-bs-toggle="tooltip" title="Edit teacher information">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.teachers.block', $teacher) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm mb-1 {{ $teacher->user->is_blocked ? 'btn-outline-success' : 'btn-outline-danger' }}"
                                    data-bs-toggle="tooltip"
                                    title="{{ $teacher->user->is_blocked ? 'Unblock this teacher account' : 'Block this teacher account' }}">
                                {{ $teacher->user->is_blocked ? 'Unblock' : 'Block' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No teachers registered yet.
                        <a href="{{ route('admin.teachers.create') }}">Register the first teacher</a>.
                    </td>
                </tr>
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