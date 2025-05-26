{{-- @extends('layouts.app')
@section('title', 'Permissions')
@push('styles')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
<style>
    .text-center {
        text-align: center;
    }
</style>
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permissions Management</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-user-shield"></i> List Permissions</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Create Permission
                                    </a>

                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="permissions-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Permission Name</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            var table = $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('permissions.permissions') }}',
                    type: 'GET'
                },
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                },
                columns: [{
                        data: null,
                        name: 'no',
                        orderable: false,
                        className: 'text-center align-middle',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-center align-middle'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });

            
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: '{{ session('error') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endpush --}}
@extends('layouts.app')
@section('title', 'Stock Opname')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
       

    </style>
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Permissions</a></div>
                </div>
              
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Permissions</h4>

                            </div>
                          @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
        <div>{{ session('success') }}</div>
        <button type="button" class="btn btn-sm btn-light border-0 ms-2" data-bs-dismiss="alert" aria-label="Close" style="font-weight: bold;">X</button>
    </div>
@endif




                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-sm table" id="users-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col" class="text-center">Permissions</th>
                                                <th scope="col" class="text-center">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                // scrollX: true,
                ajax: {
                    url: '{{ route('permissions.permissions') }}',
                    type: 'GET'
                },
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                columns: [{
                        data: null,
                        name: 'rownum',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-center'

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
            });
        });
    </script>
@endpush
