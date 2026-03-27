@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}" class="active"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-4 text-center">
            <i class="bi bi-people" style="font-size:3rem;color:#1e3a5f"></i>
            <h5 class="mt-3">Student Reports</h5>
            <p class="text-muted">View quiz scores, assignment grades, and overall performance per student.</p>
            <a href="{{ route('admin.reports.students') }}" class="btn btn-primary">View Student Reports</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 text-center">
            <i class="bi bi-person-badge" style="font-size:3rem;color:#1e3a5f"></i>
            <h5 class="mt-3">Teacher Reports</h5>
            <p class="text-muted">View quizzes created, assignments uploaded, and activity per teacher.</p>
            <a href="{{ route('admin.reports.teachers') }}" class="btn btn-primary">View Teacher Reports</a>
        </div>
    </div>
</div>
@endsection
