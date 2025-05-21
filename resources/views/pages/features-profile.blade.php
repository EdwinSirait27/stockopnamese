@extends('layouts.app')
@section('title', 'Profile')
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
                <h1>Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Profile</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Hai, {{ Auth::user()->name }}</h2>
                <p class="section-lead">
                    Bring out your morning spirit
                </p>
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form action="{{ route('features-profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <h4>Edit Password Only</h4>
                                    <div class="row">
                                        {{-- <div class="form-group col-md-6 col-12">
                                            <label>Old Password</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                            <div class="invalid-feedback">
                                                Please fill in the Old Password
                                            </div>
                                        </div> --}}
                                        <div class="form-group col-md-6 col-12">
    <label>Old Password</label>
    <div class="input-group">
        <input type="password" class="form-control" name="current_password" id="current_password" required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePasswordVisibility('current_password', this)">
                <i class="fa fa-eye-slash"></i>
            </button>
        </div>
        <div class="invalid-feedback">
            Please fill in the Old Password
        </div>
    </div>
</div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>New Password</label>
                                            <input type="password" class="form-control pwstrength"
                                                data-indicator="pwindicator" name="new_password" required>
                                            <div class="invalid-feedback">
                                                Please fill in the New Password
                                            </div>
                                            <div id="pwindicator" class="pwindicator">
                                                <div class="bar"></div>
                                                <div class="label"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Password Confirmation</label>
                                            <input type="password" class="form-control" name="new_password_confirmation"
                                                required>
                                            <div class="invalid-feedback">
                                                Please fill in the Password Confirmation
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
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
