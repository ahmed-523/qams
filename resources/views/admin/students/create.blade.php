@extends('layouts.app')
@section('title', 'Register Student')
@section('page-title', 'Register New Student')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.students.index') }}" class="active"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="card" style="max-width:640px">
    <div class="card-body">
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admission Number *</label>
                    <input type="text" name="admission_number" class="form-control" value="{{ old('admission_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="picture" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Register Student</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
