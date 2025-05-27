{{-- @extends('layouts.app')

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
@endpush --}}
@extends('layouts.app')

@section('title', 'Edit Role')
@push('style')
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Mobile responsive improvements */
        @media (max-width: 767.98px) {
            .section-header {
                padding: 15px 0;
            }
            
            .section-header h1 {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }
            
            .section-header-breadcrumb {
                font-size: 0.875rem;
            }
            
            .card-header h4 {
                font-size: 1.1rem;
                word-break: break-word;
            }
            
            .form-group label {
                font-weight: 600;
                font-size: 0.9rem;
            }
            
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .permissions-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .permission-item {
                padding: 12px;
                border: 1px solid #e9ecef;
                border-radius: 6px;
                background-color: #f8f9fa;
            }
            
            .permission-item:hover {
                background-color: #e9ecef;
            }
            
            .form-check {
                margin-bottom: 0;
            }
            
            .form-check-label {
                font-size: 0.9rem;
                padding-left: 5px;
                cursor: pointer;
                word-break: break-word;
            }
            
            .form-check-input {
                transform: scale(1.2);
                margin-top: 2px;
            }
            
            .card-footer {
                padding: 15px;
                text-align: center !important;
            }
            
            .btn {
                min-width: 120px;
                margin: 5px;
                padding: 10px 15px;
            }
            
            .btn-group-mobile {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
        }
        
        @media (min-width: 768px) {
            .permissions-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 10px;
            }
            
            .permission-item {
                padding: 10px;
                border: 1px solid #e9ecef;
                border-radius: 4px;
                background-color: #f8f9fa;
            }
        }
        
        @media (min-width: 992px) {
            .permissions-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 1200px) {
            .permissions-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Enhanced checkbox styling */
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .permission-item.checked {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }
    </style>
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
                                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                                        @enderror

                                        <div class="permissions-grid">
                                            @foreach ($permissions as $permission)
                                                <div class="permission-item {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox"
                                                               id="permission-{{ $permission->id }}" 
                                                               name="permissions[]"
                                                               value="{{ $permission->id }}"
                                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                               for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="btn-group-mobile d-md-block">
                                        <div class="d-md-flex justify-content-end">
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-md-2">
                                                <i class="fas fa-arrow-left"></i> 
                                                <span class="d-none d-sm-inline">Cancel</span>
                                                <span class="d-sm-none">Back</span>
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> 
                                                <span class="d-none d-sm-inline">Update Role</span>
                                                <span class="d-sm-none">Update</span>
                                            </button>
                                        </div>
                                    </div>
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
    <!-- JS Libraries -->
    <script src="{{ asset('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced checkbox interaction for mobile
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const permissionItem = this.closest('.permission-item');
                    if (this.checked) {
                        permissionItem.classList.add('checked');
                    } else {
                        permissionItem.classList.remove('checked');
                    }
                });
                
                // Allow clicking on the entire permission item to toggle checkbox
                const permissionItem = checkbox.closest('.permission-item');
                permissionItem.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox' && e.target.tagName !== 'LABEL') {
                        checkbox.click();
                    }
                });
            });
        });
    </script>
@endpush