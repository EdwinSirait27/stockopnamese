@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login</h4>
        </div>
        @if ($errors->has('throttle'))
            <div class="alert alert-danger">
                {{ $errors->first('throttle') }}
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

        <div class="card-body">
            <form action="{{ route('auth-login.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" placeholder="insert your name"
                        tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                        Please fill in your name
                    </div>
                </div>

                {{-- <div class="form-group">
                    <div class="d-block">
                        <label for="password"
                            class="control-label">Password</label>
                      
                    </div>
                    <input id="password"
                        type="password"
                        placeholder="insert your password"
                        class="form-control"
                        name="password"
                        tabindex="2"
                        required>
                    <div class="invalid-feedback">
                        please fill in your password
                    </div>
                </div> --}}
                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <div class="input-group">
                        <input id="password" type="password" placeholder="insert your password" class="form-control"
                            name="password" tabindex="2" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        please fill in your password
                    </div>
                </div>

                <!-- Script -->
                <script>
                    document.getElementById('togglePassword').addEventListener('click', function() {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eyeIcon');

                        const isPassword = passwordInput.type === 'password';
                        passwordInput.type = isPassword ? 'text' : 'password';
                        eyeIcon.classList.toggle('fa-eye');
                        eyeIcon.classList.toggle('fa-eye-slash');
                    });
                </script>


                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">

                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg btn-block" tabindex="4">
                        Login
                    </button>
                </div>
            </form>


        </div>
    </div>
    <div class="text-muted mt-5 text-center">
        Don't have an account? <a href="/auth-register">Create One</a>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
