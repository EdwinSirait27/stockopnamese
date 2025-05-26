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
{{-- @extends('layouts.app')
@section('title', 'MJM')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
        
        .store-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .store-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .store-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .store-code {
            font-size: 1.5rem;
            font-weight: bold;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .store-name {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .store-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .info-item {
            flex: 1;
            min-width: 120px;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 1rem;
            color: #495057;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        } 
        .entries-info {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .entries-select select {
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .loading-spinner {
            text-align: center;
            padding: 40px;
            display: none;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            display: none;
        }
        @media (max-width: 768px) {
            .store-info {
                flex-direction: column;
                align-items: stretch;
            }
            
            .info-item {
                min-width: auto;
            }
            
            .entries-info {
                flex-direction: column;
                align-items: stretch;
            }
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
                                <div class="entries-info d-flex justify-content-between align-items-center mb-3">
                                    <div class="entries-select">
                                        <label for="entries-select">Show 
                                            <select id="entries-select" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="-1">All</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                    
                                    <!-- Search Box -->
                                    <div class="search-container">
                                        <label for="search-input">Search:
                                            <input type="text" id="search-input" class="form-control form-control-sm d-inline-block ms-2" placeholder="Search stores..." style="width: 200px;">
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Loading Spinner -->
                                <div class="loading-spinner text-center" id="loading-spinner" style="display: none;">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p>Loading data...</p>
                                </div>
                                
                                <!-- No Data Message -->
                                <div class="no-data text-center" id="no-data" style="display: none;">
                                    <p>No data available</p>
                                </div>
                                
                                <!-- Cards Container -->
                                <div id="cards-container">
                                    <!-- Cards will be loaded here -->
                                </div>
                                
                                <!-- Pagination -->
                                <div class="pagination-container d-flex justify-content-center" id="pagination-container">
                                    <!-- Pagination will be loaded here -->
                                </div>
                                
                                <!-- Data Info -->
                                <div id="data-info" class="mt-3 text-muted">
                                    <!-- Data info will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .store-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        
        .store-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .store-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .store-code {
            font-weight: bold;
            color: #5a5c69;
            font-size: 14px;
        }
        
        .store-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-top: 5px;
        }
        
        .store-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        
        .info-item {
            flex: 1;
            min-width: 150px;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 14px;
            color: #495057;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .loading-spinner {
            padding: 50px 0;
        }
        
        .no-data {
            padding: 50px 0;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .entries-info {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start !important;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-container label {
                display: flex;
                align-items: center;
                width: 100%;
            }
            
            .search-container input {
                width: 100% !important;
                margin-left: 10px !important;
            }
            
            .store-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .info-item {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        jQuery(document).ready(function($) {
            let currentPage = 1;
            let currentLength = 10;
            let currentSearch = '';
            let totalRecords = 0;
            let filteredRecords = 0;
            let searchTimeout;
            
            // Load initial data
            loadData();
            
            // Entries per page functionality
            $('#entries-select').on('change', function() {
                currentLength = parseInt($(this).val());
                currentPage = 1;
                loadData();
            });
            
            // Search functionality with debounce
            $('#search-input').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentSearch = $('#search-input').val().trim();
                    currentPage = 1;
                    loadData();
                }, 300); // 300ms delay for debounce
            });
            
            // Clear search on ESC key
            $('#search-input').on('keydown', function(e) {
                if (e.keyCode === 27) { // ESC key
                    $(this).val('');
                    currentSearch = '';
                    currentPage = 1;
                    loadData();
                }
            });
            
            function loadData() {
                showLoading();
                
                $.ajax({
                    url: '{{ route('mtokosoglo.mtokosoglo') }}',
                    type: 'GET',
                    data: {
                        start: (currentPage - 1) * currentLength,
                        length: currentLength,
                        search: {
                            value: currentSearch
                        },
                        draw: 1
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            totalRecords = response.recordsTotal;
                            filteredRecords = response.recordsFiltered;
                            renderCards(response.data);
                            renderPagination();
                            renderDataInfo();
                            $('#no-data').hide();
                        } else {
                            $('#cards-container').empty();
                            $('#pagination-container').empty();
                            $('#data-info').empty();
                            $('#no-data').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        console.error('AJAX Error:', error);
                        $('#cards-container').html('<div class="alert alert-danger">Error loading data. Please try again.</div>');
                    }
                });
            }
            
            function renderCards(data) {
                let html = '';
                
                data.forEach(function(item, index) {
                    let startIndex = (currentPage - 1) * currentLength;
                    let rowNumber = startIndex + index + 1;
                    
                    // Highlight search terms
                    let kdtoko = highlightSearchTerm(item.kdtoko, currentSearch);
                    let kettoko = highlightSearchTerm(item.kettoko, currentSearch);
                    let personil = highlightSearchTerm(item.personil, currentSearch);
                    
                    html += `
                        <div class="store-card">
                            <div class="store-header">
                                <div class="store-code">#${rowNumber} - ${kdtoko}</div>
                                <div class="store-name">${kettoko}</div>
                            </div>
                            <div class="store-info">
                                <div class="info-item">
                                    <div class="info-label">Personil</div>
                                    <div class="info-value">${personil}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Inp Masuk</div>
                                    <div class="info-value">${item.inpmasuk || '-'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Actions</div>
                                    <div class="action-buttons">
                                        ${item.action || ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                $('#cards-container').html(html);
            }
            
            function highlightSearchTerm(text, searchTerm) {
                if (!searchTerm || !text) return text;
                
                const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
                return text.replace(regex, '<mark>$1</mark>');
            }
            
            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }
            
            function renderPagination() {
                if (currentLength === -1) {
                    $('#pagination-container').empty();
                    return;
                }
                
                let totalPages = Math.ceil(filteredRecords / currentLength);
                
                if (totalPages <= 1) {
                    $('#pagination-container').empty();
                    return;
                }
                
                let html = '<nav><ul class="pagination">';
                
                // Previous button
                if (currentPage > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
                } else {
                    html += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
                }
                
                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);
                
                if (startPage > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }
                
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
                }
                
                // Next button
                if (currentPage < totalPages) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
                } else {
                    html += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
                }
                
                html += '</ul></nav>';
                
                $('#pagination-container').html(html);
                
                // Pagination click handlers
                $('.page-link[data-page]').on('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt($(this).data('page'));
                    loadData();
                });
            }
            
            function renderDataInfo() {
                let start = (currentPage - 1) * currentLength + 1;
                let end = Math.min(currentPage * currentLength, filteredRecords);
                
                if (currentLength === -1) {
                    start = 1;
                    end = filteredRecords;
                }
                
                let info = `Showing ${start} to ${end} of ${filteredRecords} entries`;
                if (filteredRecords !== totalRecords) {
                    info += ` (filtered from ${totalRecords} total entries)`;
                }
                
                if (currentSearch) {
                    info += ` - searching for "${currentSearch}"`;
                }
                
                $('#data-info').html(info);
            }
            
            function showLoading() {
                $('#loading-spinner').show();
                $('#cards-container').hide();
                $('#pagination-container').hide();
                $('#no-data').hide();
            }
            
            function hideLoading() {
                $('#loading-spinner').hide();
                $('#cards-container').show();
                $('#pagination-container').show();
            }
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
                                <div class="entries-info d-flex justify-content-between align-items-center mb-3">
                                    <div class="entries-select">
                                        <label for="entries-select">Show 
                                            <select id="entries-select" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="-1">All</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                    
                                    <!-- Search Box -->
                                    <div class="search-container">
                                        <label for="search-input">Search:
                                            <input type="text" id="search-input" class="form-control form-control-sm d-inline-block ms-2" placeholder="Search ..." style="width: 200px;">
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Loading Spinner -->
                                <div class="loading-spinner text-center" id="loading-spinner" style="display: none;">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p>Loading data...</p>
                                </div>
                                
                                <!-- No Data Message -->
                                <div class="no-data text-center" id="no-data" style="display: none;">
                                    <p>No data available</p>
                                </div>
                                
                                <!-- Cards Container -->
                                <div id="cards-container">
                                    <!-- Cards will be loaded here -->
                                </div>
                                
                                <!-- Pagination -->
                                <div class="pagination-container d-flex justify-content-center" id="pagination-container">
                                    <!-- Pagination will be loaded here -->
                                </div>
                                
                                <!-- Data Info -->
                                <div id="data-info" class="mt-3 text-muted">
                                    <!-- Data info will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .store-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        
        .store-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .store-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .store-code {
            font-weight: bold;
            color: #5a5c69;
            font-size: 14px;
        }
        
        .store-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-top: 5px;
        }
        
        .store-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        
        .info-item {
            flex: 1;
            min-width: 150px;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 14px;
            color: #495057;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .loading-spinner {
            padding: 50px 0;
        }
        
        .no-data {
            padding: 50px 0;
            color: #6c757d;
        }
        
        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 6px 10px;
            font-size: 14px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }
        
        .mobile-page-info {
            font-size: 12px !important;
            margin-top: 8px;
        }
        
        @media (max-width: 768px) {
            /* Mobile responsive adjustments */
            /* Mobile pagination adjustments */
            .pagination {
                flex-wrap: nowrap;
                justify-content: center;
            }
            
            .pagination .page-link {
                padding: 6px 8px;
                font-size: 12px;
                margin: 0 1px;
            }
            
            .pagination-container {
                overflow-x: auto;
                padding: 0 10px;
            }
            
            /* Ensure pagination doesn't overflow */
            .pagination-container nav {
                display: flex;
                justify-content: center;
                min-width: fit-content;
            }
            .entries-info {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start !important;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-container label {
                display: flex;
                align-items: center;
                width: 100%;
            }
            
            .search-container input {
                width: 100% !important;
                margin-left: 10px !important;
            }
            
            .store-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .info-item {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        jQuery(document).ready(function($) {
            let currentPage = 1;
            let currentLength = 10;
            let currentSearch = '';
            let totalRecords = 0;
            let filteredRecords = 0;
            let searchTimeout;
            
            // Load initial data
            loadData();
            
            // Entries per page functionality
            $('#entries-select').on('change', function() {
                currentLength = parseInt($(this).val());
                currentPage = 1;
                loadData();
            });
            
            // Search functionality with debounce
            $('#search-input').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentSearch = $('#search-input').val().trim();
                    currentPage = 1;
                    loadData();
                }, 300); // 300ms delay for debounce
            });
            
            // Clear search on ESC key
            $('#search-input').on('keydown', function(e) {
                if (e.keyCode === 27) { // ESC key
                    $(this).val('');
                    currentSearch = '';
                    currentPage = 1;
                    loadData();
                }
            });
            
            function loadData() {
                showLoading();
                
                $.ajax({
                    url: '{{ route('mtokosoglo.mtokosoglo') }}',
                    type: 'GET',
                    data: {
                        start: (currentPage - 1) * currentLength,
                        length: currentLength,
                        search: {
                            value: currentSearch
                        },
                        draw: 1
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            totalRecords = response.recordsTotal;
                            filteredRecords = response.recordsFiltered;
                            renderCards(response.data);
                            renderPagination();
                            renderDataInfo();
                            $('#no-data').hide();
                        } else {
                            $('#cards-container').empty();
                            $('#pagination-container').empty();
                            $('#data-info').empty();
                            $('#no-data').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        console.error('AJAX Error:', error);
                        $('#cards-container').html('<div class="alert alert-danger">Error loading data. Please try again.</div>');
                    }
                });
            }
            
            function renderCards(data) {
                let html = '';
                
                data.forEach(function(item, index) {
                    let startIndex = (currentPage - 1) * currentLength;
                    let rowNumber = startIndex + index + 1;
                    
                    // Highlight search terms
                    let kdtoko = highlightSearchTerm(item.kdtoko, currentSearch);
                    let kettoko = highlightSearchTerm(item.kettoko, currentSearch);
                    let personil = highlightSearchTerm(item.personil, currentSearch);
                    
                    html += `
                        <div class="store-card">
                            <div class="store-header">
                                <div class="store-code">#${rowNumber} - ${kdtoko}</div>
                                <div class="store-name">${kettoko}</div>
                            </div>
                            <div class="store-info">
                                <div class="info-item">
                                    <div class="info-label">Personil</div>
                                    <div class="info-value">${personil}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Inp Masuk</div>
                                    <div class="info-value">${item.inpmasuk || '-'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Actions</div>
                                    <div class="action-buttons">
                                        ${item.action || ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                $('#cards-container').html(html);
            }
            
            function highlightSearchTerm(text, searchTerm) {
                if (!searchTerm || !text) return text;
                
                const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
                return text.replace(regex, '<mark>$1</mark>');
            }
            
            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }
            
            function renderPagination() {
                if (currentLength === -1) {
                    $('#pagination-container').empty();
                    return;
                }
                
                let totalPages = Math.ceil(filteredRecords / currentLength);
                
                if (totalPages <= 1) {
                    $('#pagination-container').empty();
                    return;
                }
                
                // Check if mobile
                let isMobile = window.innerWidth <= 768;
                let maxVisiblePages = isMobile ? 3 : 5;
                
                let html = '<nav><ul class="pagination pagination-sm justify-content-center">';
                
                // Previous button
                if (currentPage > 1) {
                    html += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>`;
                } else {
                    html += `<li class="page-item disabled">
                        <span class="page-link" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>`;
                }
                
                // Calculate page range for mobile
                let startPage, endPage;
                if (isMobile) {
                    // For mobile, show current page and 1 page on each side
                    startPage = Math.max(1, currentPage - 1);
                    endPage = Math.min(totalPages, currentPage + 1);
                    
                    // Adjust if at the beginning or end
                    if (currentPage === 1) {
                        endPage = Math.min(totalPages, 3);
                    } else if (currentPage === totalPages) {
                        startPage = Math.max(1, totalPages - 2);
                    }
                } else {
                    // Desktop version
                    startPage = Math.max(1, currentPage - 2);
                    endPage = Math.min(totalPages, currentPage + 2);
                }
                
                // First page and ellipsis (only for desktop or if needed)
                if (startPage > 1 && !isMobile) {
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }
                
                // Page numbers
                for (let i = startPage; i <= endPage; i++) {
                    if (i === currentPage) {
                        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                    }
                }
                
                // Last page and ellipsis (only for desktop or if needed)
                if (endPage < totalPages && !isMobile) {
                    if (endPage < totalPages - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
                }
                
                // Next button
                if (currentPage < totalPages) {
                    html += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>`;
                } else {
                    html += `<li class="page-item disabled">
                        <span class="page-link" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>`;
                }
                
                html += '</ul></nav>';
                
                // Add mobile pagination info
                if (isMobile) {
                    html += `<div class="mobile-page-info text-center mt-2 small text-muted">
                        Page ${currentPage} of ${totalPages}
                    </div>`;
                }
                
                $('#pagination-container').html(html);
                
                // Pagination click handlers
                $('.page-link[data-page]').on('click', function(e) {
                    e.preventDefault();
                    currentPage = parseInt($(this).data('page'));
                    loadData();
                });
            }
            
            function renderDataInfo() {
                let start = (currentPage - 1) * currentLength + 1;
                let end = Math.min(currentPage * currentLength, filteredRecords);
                
                if (currentLength === -1) {
                    start = 1;
                    end = filteredRecords;
                }
                
                // Mobile-friendly data info
                let isMobile = window.innerWidth <= 768;
                let info;
                
                if (isMobile) {
                    info = `${start}-${end} of ${filteredRecords}`;
                    if (currentSearch) {
                        info += ` (search: "${currentSearch}")`;
                    }
                } else {
                    info = `Showing ${start} to ${end} of ${filteredRecords} entries`;
                    if (filteredRecords !== totalRecords) {
                        info += ` (filtered from ${totalRecords} total entries)`;
                    }
                    if (currentSearch) {
                        info += ` - searching for "${currentSearch}"`;
                    }
                }
                
                $('#data-info').html(info);
            }
            
            function showLoading() {
                $('#loading-spinner').show();
                $('#cards-container').hide();
                $('#pagination-container').hide();
                $('#no-data').hide();
            }
            
            function hideLoading() {
                $('#loading-spinner').hide();
                $('#cards-container').show();
                $('#pagination-container').show();
            }
        });
    </script>
@endpush