@extends('layouts.app')
@section('title', 'Add Subject')
@section('page-title', 'Add New Subject')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}" class="active"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Subject Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Mathematics">
            </div>
            <div class="mb-4">
                <label class="form-label">Class *</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Subject</button>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
