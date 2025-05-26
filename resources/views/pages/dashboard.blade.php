{{-- @extends('layouts.app')
@section('title', 'MJM')
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
                <h1>Stock Opname</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Stock Opname</a></div>
                </div>
              
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>mtokosoglo</h4>

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
                                                <th scope="col" class="text-center">Kode Toko</th>
                                                <th scope="col" class="text-center">Ket Toko</th>
                                                <th scope="col" class="text-center">Personil</th>
                                                <th scope="col" class="text-center">Inp Masuk</th>
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
                    url: '{{ route('mtokosoglo.mtokosoglo') }}',
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
                        data: 'kdtoko',
                        name: 'kdtoko',
                        className: 'text-center'

                    },
                    {
                        data: 'kettoko',
                        name: 'kettoko',
                        className: 'text-center'
                    },

                    {
                        data: 'personil',
                        name: 'personil',
                        className: 'text-center'
                    },
                    {
                        data: 'inpmasuk',
                        name: 'inpmasuk',
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
@endpush --}}
@extends('layouts.app')
@section('title', 'MJM')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
        
        /* Mobile specific styles */
        @media (max-width: 768px) {
            .card-header h4 {
                font-size: 1.1rem;
            }
            
            .section-header h1 {
                font-size: 1.5rem;
            }
            
            .breadcrumb-item {
                font-size: 0.9rem;
            }
            
            /* Hide less important columns on mobile */
            .mobile-hide {
                display: none !important;
            }
            
            /* Stack table cells vertically on very small screens */
            @media (max-width: 480px) {
                .table-responsive {
                    font-size: 0.8rem;
                }
                
                .btn {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.8rem;
                }
            }
        }
        
        /* Improve table responsiveness */
        .table-responsive {
            border: none;
        }
        
        .table-sm th, .table-sm td {
            padding: 0.5rem 0.25rem;
            vertical-align: middle;
        }
        
        /* Better button styling for mobile */
        .action-buttons {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            flex: 1;
            min-width: auto;
        }
        
        /* Responsive alert */
        .alert {
            margin: 1rem;
            border-radius: 0.5rem;
        }
        
        /* Card improvements */
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Stock Opname</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Stock Opname</a></div>
                </div>
            </div>
            
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>mtokosoglo</h4>
                            </div>
                            
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
                                    <div>{{ session('success') }}</div>
                                    <button type="button" class="btn btn-sm btn-light border-0 ms-2" data-bs-dismiss="alert" aria-label="Close" style="font-weight: bold;">X</button>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped" id="users-table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col" style="width: 50px;">No</th>
                                                <th scope="col" class="text-center">Kode Toko</th>
                                                <th scope="col" class="text-center mobile-hide">Ket Toko</th>
                                                <th scope="col" class="text-center mobile-hide">Personil</th>
                                                <th scope="col" class="text-center mobile-hide">Inp Masuk</th>
                                                <th scope="col" class="text-center" style="width: 120px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            // Check if mobile device
            var isMobile = window.innerWidth <= 768;
            
            var columnDefs = [
                {
                    data: null,
                    name: 'rownum',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    width: '50px'
                },
                {
                    data: 'kdtoko',
                    name: 'kdtoko',
                    className: 'text-center'
                },
                {
                    data: 'kettoko',
                    name: 'kettoko',
                    className: 'text-center',
                    visible: !isMobile // Hide on mobile
                },
                {
                    data: 'personil',
                    name: 'personil',
                    className: 'text-center',
                    visible: !isMobile // Hide on mobile
                },
                {
                    data: 'inpmasuk',
                    name: 'inpmasuk',
                    className: 'text-center',
                    visible: !isMobile // Hide on mobile
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '120px',
                    render: function(data, type, row) {
                        // Ensure buttons are mobile-friendly
                        return '<div class="action-buttons">' + data + '</div>';
                    }
                }
            ];

            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('mtokosoglo.mtokosoglo') }}',
                    type: 'GET'
                },
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                lengthMenu: isMobile ? 
                    [[5, 10, 25], [5, 10, 25]] : // Shorter menu for mobile
                    [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: isMobile ? 5 : 10, // Show fewer items on mobile
                columns: columnDefs,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    processing: "Memproses..."
                },
                dom: isMobile ? 
                    '<"row"<"col-sm-12"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' :
                    '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });

            // Handle window resize
            $(window).resize(function() {
                table.columns.adjust().responsive.recalc();
            });
        });
    </script>
@endpush
