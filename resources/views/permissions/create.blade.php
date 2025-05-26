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
            <h1>Create Permissions</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></div>
                <div class="breadcrumb-item">Create Permissions</div>
            </div>
        </div>

        <!-- Section Body -->
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Permissions</h4>
                        </div>
                        <div class="card-body pt-4 p-3">
                            @if(session('error'))
                            <div class="alert alert-danger">
                                <strong>{{ __('error') }}</strong><br>
                                {{ session('error') }}
                        
                                @if(session('error_details'))
                                    <br><small>{{ session('error_details') }}</small>
                                @endif
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <form  method="POST" action="{{ route('permissions.store') }}">
                                @csrf
                                
                                <!-- Role Name Input -->
                                <div class="form-group row mb-3">
                                    <label for="name" class="col-md-2 col-form-label">Permissions Name</label>
                                    <div class="col-md-10">
                                        <input id="name" type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" 
                                               required autofocus
                                               placeholder="Enter role name (letters, numbers, underscore, hyphen only)">
                                        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>  
                                <!-- Form Buttons -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-10 offset-md-2">
                                        <button id="create-btn" type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Create Permission
                                        </button>
                                        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
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