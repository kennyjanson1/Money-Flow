@extends('layouts.app')

@section('title', 'Transaction - Casholve')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    @include('components.transactions.stats-cards')

    <!-- Transactions Table -->
    @include('components.transactions.table')
</div>
@endsection