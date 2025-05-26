@extends('layouts.app')

@section('title', 'Edit Permissions')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Roles</a></div>
                    <div class="breadcrumb-item">Edit Permissions</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Editing Permissions: {{ $permission->name }}</h4>
                            </div>
                            <form action="{{ route('permissions.update', $id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name" class="col-md-2 col-form-label">Permissions Name</label>
                                        <div class="col-md-10">
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name', $permission->name) }}" required autofocus
                                                placeholder="Enter role name (letters, numbers, underscore, hyphen only)">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left"></i> Cancel
                                        </a>
                                        <button type="submit"class="btn btn-primary">
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
