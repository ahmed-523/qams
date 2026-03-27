@extends('layouts.app')
@section('title', 'Subjects')
@section('page-title', 'Subjects Management')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}" class="active"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>All Subjects</h5>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary"
       data-bs-toggle="tooltip" title="Add a new subject">
        <i class="bi bi-plus-lg me-1"></i>Add Subject
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Subject Name</th><th>Class</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $subject->name }}</strong></td>
                    <td>{{ $subject->class->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.subjects.edit', $subject) }}"
                           class="btn btn-sm btn-outline-warning"
                           data-bs-toggle="tooltip" title="Edit Subject">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Delete this subject?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="tooltip" title="Delete Subject">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No subjects added yet.</td></tr>
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