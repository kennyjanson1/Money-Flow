@extends('layouts.app')

@section('title', 'Dashboard - Casholve')

@section('content')
<!-- Top Section: Balance + Spending -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        @include('components.dashboard.balance-card')
    </div>
    <div>
        @include('components.dashboard.spending-category')
    </div>
</div>

<!-- Cash Flow Chart -->
<div class="mb-6">
    @include('components.dashboard.cashflow-chart')
</div>

<!-- Bottom Section: Transactions + Savings -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @include('components.dashboard.recent-transactions')
    </div>
    <div>
        @include('components.dashboard.savings-plan')
    </div>
</div>
@endsection
