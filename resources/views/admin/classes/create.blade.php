@extends('layouts.app')
@section('title', 'Add Class')
@section('page-title', 'Add New Class')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}" class="active"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Class Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Class 10-A">
            </div>
            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional description">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add Class</button>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
