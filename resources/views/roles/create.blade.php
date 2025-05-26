@extends('layouts.app')
@section('title', 'Create Roles')
@push('style')
  <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <!-- Section Header -->
            <div class="section-header">
                <h1>Create Role</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                    <div class="breadcrumb-item">Create Role</div>
                </div>
            </div>

            <!-- Section Body -->
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Create Role</h4>
                            </div>

                            <div class="card-body">
                                <form  method="POST" action="{{ route('roles.store') }}">
                                    @csrf

                                    <!-- Role Name Input -->
                                    <div class="form-group row mb-3">
                                        <label for="name" class="col-md-2 col-form-label">Role Name</label>
                                        <div class="col-md-10">
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autofocus
                                                placeholder="Enter role name (letters, numbers, underscore, hyphen only)">

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label">Permissions</label>
                                        <div class="col-md-10">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" required
                                                            name="permissions[]" id="permission-{{ $permission->id }}"
                                                            value="{{ $permission->id }}" {{-- THIS MUST BE UUID --}}
                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <!-- Menampilkan pesan error untuk permissions jika ada -->
                                            @error('permissions')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Form Buttons -->
                                    <div class="form-group row mb-0">
                                        <div class="col-md-10 offset-md-2">
                                            <button type="submit"  class="btn btn-primary">
                                                <i class="fas fa-save"></i> Create Role
                                            </button>
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
@endpush