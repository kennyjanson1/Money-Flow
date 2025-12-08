@extends('layouts.app')

@section('title', 'Cash Flow - Casholve')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    @include('components.cashflow.summary-cards')

    <!-- Monthly Cash Flow Chart -->
    {{-- @include('components.cashflow.monthly-chart') --}}

    <!-- Trend Analysis -->
    @include('components.cashflow.trend-chart')

    <!-- Expense Breakdown -->
    @include('components.cashflow.expense-breakdown')
</div>
@endsection