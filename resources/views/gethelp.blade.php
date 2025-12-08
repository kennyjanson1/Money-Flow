@extends('layouts.app')

@section('title', 'Get Help - Casholve')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @include('components.gethelp.alert-messages')

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search Box -->
            @include('components.gethelp.search-box')

            <!-- FAQ Section -->
            @include('components.gethelp.faq-section')

            <!-- Video Tutorials -->
            {{-- @include('components.gethelp.video-tutorials') --}}
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Support Form -->
            @include('components.gethelp.contact-form')

            <!-- Quick Links -->
            {{-- @include('components.gethelp.quick-links') --}}

            <!-- Contact Info -->
            @include('components.gethelp.contact-info')
        </div>
    </div>
</div>

<!-- FAQ JavaScript -->
@include('components.gethelp.faq-script')
@endsection