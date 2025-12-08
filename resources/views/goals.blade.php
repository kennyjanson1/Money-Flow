@extends('layouts.app')

@section('title', 'Goals - Casholve')

@section('content')
<div class="space-y-6">
    <!-- Overview Cards -->
    @include('components.goals.overview-cards')

    <!-- Active Goals -->
    @include('components.goals.active-goals')

    <!-- Completed Goals -->
    @include('components.goals.completed-goals')
</div>
@endsection