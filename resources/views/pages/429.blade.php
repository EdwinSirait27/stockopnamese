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
            <div class="page-search">
                <form>
                    <div class="form-group floating-addon floating-addon-not-append">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <input type="text"
                                class="form-control"
                                placeholder="Search">
                            {{-- <div class="input-group-append">
                                <button class="btn btn-primary btn-lg">
                                    Search
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
