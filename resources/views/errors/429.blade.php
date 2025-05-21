@extends('layouts.error')

@section('title', '429')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            <h1>429</h1>
            <div class="page-description">
                Too Many Attempts login.
            </div>
            <div class="mt-3">
                <a>Please wait 1 minute. You will be redirected to the login page...</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- JS Libraries -->
    <script>
        setTimeout(function () {
            window.location.href = "{{ route('login') }}";
        }, 60000); // 60 detik = 1 menit
    </script>
@endpush