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
                    <div class="breadcrumb-item active"><a href="/dashboardadmin">Stock Opname</a></div>
                </div>

            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Pos Opname</h4>

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
                                                <th scope="col" class="text-center">Opname ID</th>
                                                <th scope="col" class="text-center">Date</th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-center">Location</th>
                                                <th scope="col" class="text-center">Sub Location</th>
                                                <th scope="col" class="text-center">Opname Type</th>
                                                <th scope="col" class="text-center">Note</th>
                                                {{-- <th scope="col" class="text-center">Note</th>
                                                <th scope="col" class="text-center">Counter</th>
                                                <th scope="col" class="text-center">Number</th>
                                                <th scope="col" class="text-center">Approval 1</th>
                                                <th scope="col" class="text-center">Approval 2</th>
                                                <th scope="col" class="text-center">Approval 3</th>
                                                <th scope="col" class="text-center">Prefix Number</th>
                                                <th scope="col" class="text-center">Approval 1 Date</th>
                                                <th scope="col" class="text-center">Approval 2 Date</th>
                                                <th scope="col" class="text-center">Approval 3 Date</th>
                                                <th scope="col" class="text-center">Type</th>
                                                <th scope="col" class="text-center">Company ID</th>
                                                <th scope="col" class="text-center">Opane</th>
                                                <th scope="col" class="text-center">User ID</th>
                                                <th scope="col" class="text-center">Name</th>
                                                <th scope="col" class="text-center">Type Opname</th> --}}
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
                    url: '{{ route('posopnameadmin.posopnameadmin') }}',
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
                        data: 'opname_id',
                        name: 'opname_id',
                        className: 'text-center'

                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'text-center'
                    },

                  
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'location_id',
                    //     name: 'location_id',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'ambildarisublocation.location.name',
                        name: 'ambildarisublocation.location.name',
                        className: 'text-center'
                    },
                    {
                        data: 'ambildarisublocation.name',
                        name: 'ambildarisublocation.name',
                        className: 'text-center'
                    },
                    {
                        data: 'type',
                        name: 'type',
                        className: 'text-center'
                    },
                    {
                        data: 'note',
                        name: 'note',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'status',
                    //     name: 'status',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }

                ],
            });
                 $('#location_name').change(function () {
    table.ajax.reload();
});
        });
   
    </script>
@endpush