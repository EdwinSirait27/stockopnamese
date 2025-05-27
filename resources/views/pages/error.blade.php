@extends('layouts.error')

@section('title', 'SO')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            {{-- <h1>MJM</h1> --}}
            <div class="login-brand">
    <img src="{{ asset('img/abc.png') }}"
        alt="logo"
        width="100"
        class="bg-dark rounded">
   
</div>
            <div class="page-description">
                Stock Opname is closed.
            </div>
            



        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
