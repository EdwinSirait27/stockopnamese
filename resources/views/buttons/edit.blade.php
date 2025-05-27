@extends('layouts.app')
@section('title', 'Edit mstocksoglo')
@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Dashboard</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>
            <div class="section-body">
                {{-- <h2 class="section-title">Hai, {{ Auth::user()->name }}</h2> --}}
                <p class="section-lead">
                    {{-- Bring out your morning spirit --}}
                </p>
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form action="{{ route('buttons.update', $id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <h4>Edit</h4>
                                    {{-- @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif --}}
                                    @if (isset($success))
                                        <div class="alert alert-success">
                                            {{ $success }}
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">

                                            <label>Start Date</label>
                                           <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                        value="{{ old('start_date', $button->start_date) }}" required>
                                            <div class="invalid-feedback">
                                                Please fill in the start date
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">

                                            <label>End Date</label>
                                           <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                        value="{{ old('end_date', $button->end_date) }}" required>
                                            <div class="invalid-feedback">
                                                Please fill in the End date
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">

                                            <label>Ket</label>
                                            <input type="text" class="form-control" id="ket" name="ket"
                                                value="{{ old('ket', $button->ket) }}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div> --}}
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('js/page/features-profile.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
