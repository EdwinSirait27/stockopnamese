@extends('layouts.app')
@section('title', 'MJM')
{{-- @push('style')
    <style>
        .text-center {
            text-align: center;
        }
    </style>
@endpush --}}
@push('styles')

    <style>
        /* Custom Pagination Styling */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
            text-align: center;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 2px;
            background: #fff;
            border: 1px solid #dee2e6;
            color: #495057;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 14px;
            min-width: 40px;
            text-align: center;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #007bff;
            border-color: #007bff;
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,123,255,0.3);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #6c757d;
            background: #f8f9fa;
            border-color: #dee2e6;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            transform: none;
            box-shadow: none;
        }
        
        /* Page Size Selector Styling */
        .dataTables_length {
            margin-bottom: 15px;
        }
        
        .dataTables_length select {
            padding: 6px 30px 6px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background: #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='m2 0-2 2h4zm0 5 2-2h-4z'/%3E%3C/svg%3E") no-repeat right 8px center;
            background-size: 8px 10px;
            appearance: none;
        }
        
        /* Quick Jump Pagination */
        .pagination-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .page-jump {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .page-jump input {
            width: 60px;
            padding: 6px 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            text-align: center;
        }
        
        .page-jump button {
            padding: 6px 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .page-jump button:hover {
            background: #0056b3;
        }
        
        .page-size-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-size-btn {
            padding: 4px 8px;
            border: 1px solid #dee2e6;
            background: #fff;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }
        
        .page-size-btn:hover {
            background: #e9ecef;
        }
        
        .page-size-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .pagination-controls {
                flex-direction: column;
                gap: 10px;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 6px 8px;
                margin: 0 1px;
                font-size: 12px;
                min-width: 32px;
            }
        }
    </style>
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Current Database</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Stock Opname</a></div>
                </div>

            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Current Database</h4>

                              <select id="filter-cabang" class="form-control" style="width: 200px;">
    <option value="">-- ALL BRANCH --</option>
    @foreach ($listCabang as $CABANG)
        <option value="{{ $CABANG->CABANG }}">{{ $CABANG->CABANG }}</option>
    @endforeach
</select>


                            </div>
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center"
                                    role="alert">
                                    <div>{{ session('success') }}</div>
                                    <button type="button" class="btn btn-sm btn-light border-0 ms-2"
                                        data-bs-dismiss="alert" aria-label="Close" style="font-weight: bold;">X</button>
                                </div>
                            @endif




                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-sm table" id="users-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col" class="text-center">BARA</th>
                                                <th scope="col" class="text-center">BARA2</th>
                                                <th scope="col" class="text-center">NAMA</th>
                                                <th scope="col" class="text-center">AVER</th>
                                                <th scope="col" class="text-center">AWAL</th>
                                                <th scope="col" class="text-center">MASUK</th>
                                                <th scope="col" class="text-center">KELUAR</th>
                                                <th scope="col" class="text-center">SATUAN</th>
                                                <th scope="col" class="text-center">SALDO</th>
                                                <th scope="col" class="text-center">CABANG</th>
                                                
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
{{-- 
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
                    url: '{{ route('currentdb.currentdb') }}',
                data: function(d) {
                    d.CABANG = $('#filter-cabang').val(); // kirim nama CABANG ke backend
                }
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
                            data: 'BARA',
                            name: 'BARA',
                            className: 'text-center'
                        },
                        {
                            data: 'BARA2',
                            name: 'BARA2',
                            className: 'text-center'

                        },
                        {
                            data: 'NAMA',
                            name: 'NAMA',
                            className: 'text-center'

                        },
                        {
                            data: 'AVER',
                            name: 'AVER',
                            className: 'text-center'

                        },
                        {
                            data: 'SATUAN',
                            name: 'SATUAN',
                            className: 'text-center'

                        },
                        {
                            data: 'AWAL',
                            name: 'AWAL',
                            className: 'text-center'

                        },
                        {
                            data: 'MASUK',
                            name: 'MASUK',
                            className: 'text-center'

                        },
                        {
                            data: 'KELUAR',
                            name: 'KELUAR',
                            className: 'text-center'

                        },
                        {
                            data: 'SALDO',
                            name: 'SALDO',
                            className: 'text-center',

                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'CABANG',
                            name: 'CABANG',
                            className: 'text-center'

                         
                        }


                ],
            });
              $('#filter-cabang').on('change', function() {
            table.draw();
        });
        });
    </script>
@endpush --}}
{{-- @push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                
                // Optimasi untuk data besar
                deferRender: true,      // Render hanya baris yang terlihat
                scroller: true,         // Virtual scrolling
                scrollY: '60vh',        // Tinggi scroll area
                scrollCollapse: true,   // Collapse jika data sedikit
                
                // Paging configuration
                pageLength: 50,         // Kurangi default page length
                lengthMenu: [
                    [25, 50, 100, 200],
                    [25, 50, 100, 200]
                ],
                
                // AJAX configuration
                ajax: {
                    url: '{{ route('currentdb.currentdb') }}',
                    data: function(d) {
                        d.CABANG = $('#filter-cabang').val();
                    },
                    // Tambahkan error handling
                    error: function(xhr, error, code) {
                        console.log('Error:', error);
                        $('#users-table_processing').hide();
                        alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    }
                },
                
                // UI Optimizations
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                
                // Search dengan debounce
                searchDelay: 500,       // Delay 500ms sebelum search
                
                // Column definitions
                columns: [
                    {
                        data: null,
                        name: 'rownum',
                        title: 'No',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        width: '50px',
                        className: 'text-center'
                    },
                    {
                        data: 'BARA',
                        name: 'BARA',
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'BARA2',
                        name: 'BARA2',
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'NAMA',
                        name: 'NAMA',
                        className: 'text-center',
                        width: '200px'
                    },
                    {
                        data: 'AVER',
                        name: 'AVER',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : '0.00';
                        }
                    },
                    {
                        data: 'SATUAN',
                        name: 'SATUAN',
                        className: 'text-center',
                        width: '80px'
                    },
                    {
                        data: 'AWAL',
                        name: 'AWAL',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'MASUK',
                        name: 'MASUK',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'KELUAR',
                        name: 'KELUAR',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'SALDO',
                        name: 'SALDO',
                        className: 'text-center font-weight-bold',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var saldo = parseFloat(data) || 0;
                            var formatted = saldo.toLocaleString('id-ID');
                            var color = saldo < 0 ? 'text-danger' : 'text-success';
                            return '<span class="' + color + '">' + formatted + '</span>';
                        }
                    },
                    {
                        data: 'CABANG',
                        name: 'CABANG',
                        className: 'text-center',
                        width: '100px'
                    }
                ],
                
                // Language settings
                language: {
                    processing: '<div class="d-flex justify-content-center">' +
                               '<div class="spinner-border" role="status">' +
                               '<span class="sr-only">Loading...</span>' +
                               '</div>' +
                               '</div>',
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Search:",
                    // paginate: {
                    //     first: "Pertama",
                    //     last: "Terakhir",
                    //     next: "Selanjutnya",
                    //     previous: "Sebelumnya"
                    // }
                },
                
                // Event handlers
                initComplete: function() {
                    console.log('DataTable initialized successfully');
                },
                
                drawCallback: function(settings) {
                    // Optional: Add any custom styling after each draw
                    console.log('Table redrawn with', settings.fnRecordsDisplay(), 'records');
                }
            });

            // Filter handler dengan debounce
            var filterTimeout;
            $('#filter-cabang').on('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    table.draw();
                }, 300);
            });
            
            // Optional: Export functionality
            
            // Optional: Refresh data
            $('#refresh-data').on('click', function() {
                table.ajax.reload(null, false); // false = stay on current page
            });
        });
    </script>
@endpush --}}
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        jQuery(document).ready(function($) {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                
                // Optimasi untuk data besar
                deferRender: true,
                scroller: false,        // Disable virtual scrolling untuk pagination
                scrollY: false,         // Disable scroll untuk pagination
                scrollCollapse: true,
                
                // Enhanced Paging configuration
                paging: true,           // Enable pagination
                pageLength: 25,         // Default page length
                lengthMenu: [
                    [10, 25, 50, 100, 200, 500],
                    [10, 25, 50, 100, 200, 500]
                ],
                
                // Pagination type - dapat diubah sesuai kebutuhan
                pagingType: "full_numbers", // Options: simple, simple_numbers, full, full_numbers
                
                // AJAX configuration
                ajax: {
                    url: '{{ route('currentdb.currentdb') }}',
                    data: function(d) {
                        d.CABANG = $('#filter-cabang').val();
                    },
                    error: function(xhr, error, code) {
                        console.log('Error:', error);
                        $('#users-table_processing').hide();
                        alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
                    }
                },
                
                // Enhanced UI configuration
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
                     '<"row"<"col-sm-12"<"pagination-controls">>>',
                
                // Search dengan debounce
                searchDelay: 500,
                
                // Column definitions
                columns: [
                    {
                        data: null,
                        name: 'rownum',
                        title: 'No',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        width: '50px',
                        className: 'text-center'
                    },
                    {
                        data: 'BARA',
                        name: 'BARA',
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'BARA2',
                        name: 'BARA2',
                        className: 'text-center',
                        width: '100px'
                    },
                    {
                        data: 'NAMA',
                        name: 'NAMA',
                        className: 'text-center',
                        width: '200px'
                    },
                    {
                        data: 'AVER',
                        name: 'AVER',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) : '0.00';
                        }
                    },
                    {
                        data: 'SATUAN',
                        name: 'SATUAN',
                        className: 'text-center',
                        width: '80px'
                    },
                    {
                        data: 'AWAL',
                        name: 'AWAL',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'MASUK',
                        name: 'MASUK',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'KELUAR',
                        name: 'KELUAR',
                        className: 'text-center',
                        render: function(data) {
                            return data ? parseFloat(data).toLocaleString('id-ID') : '0';
                        }
                    },
                    {
                        data: 'SALDO',
                        name: 'SALDO',
                        className: 'text-center font-weight-bold',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var saldo = parseFloat(data) || 0;
                            var formatted = saldo.toLocaleString('id-ID');
                            var color = saldo < 0 ? 'text-danger' : 'text-success';
                            return '<span class="' + color + '">' + formatted + '</span>';
                        }
                    },
                    {
                        data: 'CABANG',
                        name: 'CABANG',
                        className: 'text-center',
                        width: '100px'
                    }
                ],
                
                // Enhanced Language settings
                language: {
                    processing: '<div class="d-flex justify-content-center">' +
                               '<div class="spinner-border text-primary" role="status">' +
                               '<span class="sr-only">Loading...</span>' +
                               '</div>' +
                               '</div>',
                    lengthMenu: "Displaying _MENU_ data pages",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Displaying _START_ to _END_ from _TOTAL_ data",
                    infoEmpty: "Displaying 0 sampai 0 dari 0 data",
                    infoFiltered: "(filtered from _MAX_ total data)",
                    search: "Search:",
                    paginate: {
                        first: "« First",
                        last: "Last »",
                        next: "Next",
                        previous: "Previously"
                    }
                },
                
                // Event handlers
                initComplete: function() {
                    console.log('DataTable initialized successfully');
                    
                    // Add custom pagination controls
                    addCustomPaginationControls(this);
                },
                
                drawCallback: function(settings) {
                    console.log('Table redrawn with', settings.fnRecordsDisplay(), 'records');
                    
                    // Update custom controls
                    updateCustomControls(this);
                }
            });

            // Filter handler dengan debounce
            var filterTimeout;
            $('#filter-cabang').on('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    table.draw();
                }, 300);
            });
            
            // Refresh data
            $('#refresh-data').on('click', function() {
                table.ajax.reload(null, false);
            });
            
            // Function to add custom pagination controls
            function addCustomPaginationControls(tableApi) {
                var paginationControls = $('.pagination-controls');
                
                // Quick page size buttons
                // var pageSizeControls = $('<div class="page-size-controls">' +
                //     '<span style="margin-right: 10px;">Quick:</span>' +
                //     '<button class="page-size-btn" data-size="25">25</button>' +
                //     '<button class="page-size-btn" data-size="50">50</button>' +
                //     '<button class="page-size-btn" data-size="100">100</button>' +
                //     '<button class="page-size-btn" data-size="200">200</button>' +
                //     '</div>');
                
                // // Page jump controls
                // var pageJumpControls = $('<div class="page-jump">' +
                //     '<span>To the page:</span>' +
                //     '<input type="number" id="page-jump-input" min="1" placeholder="1">' +
                //     '<button id="page-jump-btn">Go</button>' +
                //     '</div>');
                
                // paginationControls.append(pageSizeControls).append(pageJumpControls);
                
                // Page size button handlers
                $('.page-size-btn').on('click', function() {
                    var size = $(this).data('size');
                    table.page.len(size).draw();
                    
                    $('.page-size-btn').removeClass('active');
                    $(this).addClass('active');
                });
                
                // Page jump handler
                $('#page-jump-btn').on('click', function() {
                    var pageNum = parseInt($('#page-jump-input').val());
                    var maxPage = table.page.info().pages;
                    
                    if (pageNum && pageNum >= 1 && pageNum <= maxPage) {
                        table.page(pageNum - 1).draw('page');
                    } else {
                        alert('Halaman tidak valid. Masukkan nomor halaman antara 1 dan ' + maxPage);
                    }
                });
                
                // Enter key handler for page jump
                $('#page-jump-input').on('keypress', function(e) {
                    if (e.which == 13) {
                        $('#page-jump-btn').click();
                    }
                });
            }
            
            // Function to update custom controls
            function updateCustomControls(tableApi) {
                var info = table.page.info();
                var currentLength = table.page.len();
                
                // Update active page size button
                $('.page-size-btn').removeClass('active');
                $('.page-size-btn[data-size="' + currentLength + '"]').addClass('active');
                
                // Update page jump input max attribute
                $('#page-jump-input').attr('max', info.pages);
                $('#page-jump-input').attr('placeholder', (info.page + 1));
            }
            
            // Keyboard shortcuts for pagination
            $(document).on('keydown', function(e) {
                // Only if no input is focused
                if (!$('input, textarea, select').is(':focus')) {
                    switch(e.which) {
                        case 37: // Left arrow - Previous page
                            if (e.ctrlKey) {
                                e.preventDefault();
                                table.page('previous').draw('page');
                            }
                            break;
                        case 39: // Right arrow - Next page
                            if (e.ctrlKey) {
                                e.preventDefault();
                                table.page('next').draw('page');
                            }
                            break;
                        case 36: // Home - First page
                            if (e.ctrlKey) {
                                e.preventDefault();
                                table.page('first').draw('page');
                            }
                            break;
                        case 35: // End - Last page
                            if (e.ctrlKey) {
                                e.preventDefault();
                                table.page('last').draw('page');
                            }
                            break;
                    }
                }
            });
        });
    </script>
@endpush