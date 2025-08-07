@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush
@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Register</h4>
        </div>
     
        <div class="card-body">
            <form id="departments-create" action="{{ route('auth-register.register') }}" method="POST">
                @csrf
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <div class="row">
                    <div class="form-group col-6">
                        <label for="Name">Name</label>
                        <input id="name" type="text" placeholder="Fill your name"
                            class="form-control text-capitalize" name="name" value="{{ old('name') }}" autofocus>

                    </div>
                    <div class="form-group col-6">
                        <label for="username">Username</label>
                        <input id="username" placeholder="create your username" type="number"
                            class="form-control"value="{{ old('username') }}" name="username" autofocus>
                        <small class="form-text text-muted">*min 8 character exp:08234729</small>
                    </div>

                    <script>
                        document.getElementById('name').addEventListener('blur', function() {
                            this.value = this.value.replace(/\b\w/g, c => c.toUpperCase());
                        });
                    </script>
                    <div class="form-group col-6">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input id="password" placeholder="create your password" type="password"
                                class="form-control pwstrength" data-indicator="pwindicator" name="password">
                                
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                            </div>
                            <small class="form-text text-muted">*min 8 character exp:08234729</small>
                        <div id="pwindicator" class="pwindicator">
                            <div class="bar"></div>
                            <div class="label"></div>
                        </div>
                    </div>
                       {{-- <div class="form-group col-6">
                        <label for="role">Role</label>
                        <select name="role" onchange="this.form.submit()">
        <option value="">-- All Role --</option>
        @foreach ($roles as $r)
            <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>
                {{ ucfirst($r->name) }}
            </option>
        @endforeach
    </select>
                    </div> --}}
                    <div class="form-group col-6">
    <label for="role">Role</label>
    <select name="role" id="role" class="form-control select2" required>
        <option value="">-- Pilih Role --</option>
        @foreach ($roles as $r)
            <option value="{{ $r->name }}" {{ old('role') === $r->name ? 'selected' : '' }}>
                {{ ucfirst($r->name) }}
            </option>
        @endforeach
    </select>
</div>


                    <script>
                        function togglePassword() {
                            const passwordInput = document.getElementById('password');
                            const eyeIcon = document.getElementById('eyeIcon');
                            const isPassword = passwordInput.type === 'password';

                            passwordInput.type = isPassword ? 'text' : 'password';
                            eyeIcon.classList.toggle('fa-eye');
                            eyeIcon.classList.toggle('fa-eye-slash');
                        }
                    </script>

                </div>
                {{-- <div class="form-group">
                    <label for="password2" class="d-block">Password Confirmation</label>
                    <input id="password2" type="password" class="form-control" name="password_confirmation">
                    
                </div> --}}
                {{-- <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg btn-block">
                        Register
                    </button>
                </div> --}}
                <div class="form-group d-flex justify-content-between">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                        Back
                    </a>
                    <button type="submit" id="create-btn" class="btn btn-success btn-lg">
                        Register
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- Select2 JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
       @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Failed!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
      document.getElementById('create-btn').addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah pengiriman form langsung
            Swal.fire({
                title: 'Are You Sure?',
                text: "Make sure the data you entered is correct!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Assign!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengkonfirmasi, submit form
                    document.getElementById('departments-create').submit();
                }
            });
        });
    $(document).ready(function() {
        $('#role').select2({
            placeholder: '-- All Role --',
            allowClear: false
        });
    });
</script>
@endpush
