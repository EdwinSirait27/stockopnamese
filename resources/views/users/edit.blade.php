@extends('layouts.app')
@section('title', 'Edit Users')
@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 42px;
            /* beri ruang untuk ikon */
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1rem;
        }

        .toggle-password:hover {
            color: #343a40;
        }

        .pwindicator {
            margin-top: 5px;
        }

        select.form-control,
        select.form-select {
            font-size: 2rem;
            color: #212529;
        }
    </style>
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>User : {{ $user->name }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Users</a></div>
                    <div class="breadcrumb-item">Users</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form action="{{ route('users.update', $hashedId) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <h4>Edit Profile {{ $user->name }}</h4>
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
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
                                        <!-- Name Field -->
                                        <div class="form-group col-md-6 col-12">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $user->name) }}" disabled>
                                            <div class="invalid-feedback">
                                                Please fill in the Name
                                            </div>
                                        </div>

                                        <!-- Roles Field -->
                                        {{-- <div class="form-group col-md-6 col-12">
                                            <label for="role">Roles</label>
                                            <div class="@error('role') border border-danger rounded-3 @enderror">
                                                <select class="form-select @error('role') is-invalid @enderror"
                                                    name="role" id="role" required>
                                                    <option value="">Select Role</option>
                                                    @foreach ($roles as $name => $displayName)
                                                        <option value="{{ $name }}"
                                                            {{ $selectedRole == $name ? 'selected' : '' }}>
                                                            {{ $displayName ?? ucfirst($name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        <div class="form-group col-md-6 col-12">
                                            <label for="role">Roles</label>
                                            <div class="@error('role') border border-danger rounded-3 @enderror">
                                                <select class="form-select select2 @error('role') is-invalid @enderror"
                                                    name="role" id="role" required>
                                                    <option value="">Select Role</option>
                                                    @foreach ($roles as $name => $displayName)
                                                        <option value="{{ $name }}"
                                                            {{ $selectedRole == $name ? 'selected' : '' }}>
                                                            {{ $displayName ?? ucfirst($name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>





                                        <!-- New Password Field -->
                                        <div class="form-group col-md-6 col-12">
                                            <label for="password">New Password</label>
                                            <div class="password-wrapper position-relative">
                                                <input type="password" class="form-control pwstrength" id="password"
                                                    data-indicator="pwindicator" name="password" disabled>
                                                <span
                                                    class="toggle-password position-absolute top-50 end-0 translate-middle-y pe-3"
                                                    onclick="togglePassword()">
                                                    <i id="eyeIcon" class="fa fa-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    Please fill in the New Password
                                                </div>
                                                <div id="pwindicator" class="pwindicator mt-2">
                                                    <div class="bar"></div>
                                                    <div class="label"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label for="BO"></label>
                                            <label for="BO" class="form-label">Location</label>
                                            <select name="BO"
                                                class="form-select select2 @error('BO') is-invalid @enderror"
                                                required>
                                                <option value="">Choose Location</option>
                                                @foreach ($locationse as $id => $BO)
                                                    <option value="{{ $id }}"
                                                        {{ old('BO', $user->BO) == $id ? 'selected' : '' }}>
                                                        {{ $BO }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('BO')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>




                                        <div class="form-group col-md-6 col-12">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{ old('username', $user->username) }}" disabled>
                                            <div class="invalid-feedback">
                                                Please fill in the Username
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function togglePassword() {
                                            const passwordInput = document.getElementById("newPassword");
                                            const eyeIcon = document.getElementById("eyeIcon");

                                            if (passwordInput.type === "password") {
                                                passwordInput.type = "text";
                                                eyeIcon.classList.remove("fa-eye");
                                                eyeIcon.classList.add("fa-eye-slash");
                                            } else {
                                                passwordInput.type = "password";
                                                eyeIcon.classList.remove("fa-eye-slash");
                                                eyeIcon.classList.add("fa-eye");
                                            }
                                        }
                                    </script>

                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ url('/users') }}" class="btn btn-secondary">Back</a>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('js/page/features-profile.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#role').select2({
                placeholder: "Select Role",
                allowClear: true
            });
        });
    </script>
@endpush
