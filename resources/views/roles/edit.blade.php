@extends('layouts.app')

@section('title', 'Edit Role')
@push('style')
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Role</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                    <div class="breadcrumb-item">Edit Role</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Editing Role: {{ $role->name }}</h4>
                            </div>
                            <form id="roles-edit" action="{{ route('roles.update', $id) }}" method="POST">

                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Role Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $role->name) }}" readonly>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Permissions</label>
                                        @error('permissions')
                                            <div class="text-danger mb-2">{{ $message }}</div>
                                        @enderror

                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="permission-{{ $permission->id }}" name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @error('permissions')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Role
                                        </button>
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
    <script src="{{ asset('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
@endpush
