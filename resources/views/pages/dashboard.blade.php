{{-- @extends('layouts.app')
@section('title', 'MJM')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
    </style>
@endpush
@role('Bos')
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

                                    <form id="filter-form" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="location_name">Filter Location</label>
                                                <select id="location_name" name="location_name" class="form-control">
                                                    <option value="">-- All --</option>
                                                    @foreach ($locations as $loc)
                                                        <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive">
                                        <table class="table-sm table" id="users-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col" class="text-center">Number</th>
                                                    <th scope="col" class="text-center">Location</th>
                                                    <th scope="col" class="text-center">Sub Location</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" class="text-center">Opname Type</th>
                                                    <th scope="col" class="text-center">Date</th>
                                                    <th scope="col" class="text-center">Note</th>

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
                    ajax: {
                        url: '{{ route('posopname.posopname') }}',
                        type: 'GET',
                        data: function(d) {
                            d.location_name = $('#location_name').val();
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
                            data: 'number',
                            name: 'number',
                            className: 'text-center'
                        },
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
                            data: 'status',
                            name: 'status',
                            className: 'text-center'
                        },
                        {
                            data: 'type',
                            name: 'type',
                            className: 'text-center'
                        },
                        {
                            data: 'date',
                            name: 'date',
                            className: 'text-center'
                        },
                        {
                            data: 'note',
                            name: 'note',
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
                $('#location_name').change(function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
@endrole
@role('Admin')
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

                                    <form id="filter-form" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="location_name">Filter Location</label>
                                                <select id="location_name" name="location_name" class="form-control">
                                                    <option value="">-- All --</option>
                                                    @foreach ($locations as $loc)
                                                        <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>

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
                    ajax: {
                        url: '{{ route('posopnameadmin.posopnameadmin') }}',
                        type: 'GET',
                        data: function(d) {
                            d.location_name = $('#location_name').val();
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
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }

                    ],
                });
                $('#location_name').change(function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
@endrole --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Choose Databases & Location</h1>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('dashboard') }}" method="GET">
    <div class="form-group">
        <label for="db">Choose Databases</label>
        <select name="db" id="db" class="form-control" onchange="this.form.submit()">
            <option value="mysql_third" {{ $db=='mysql_third' ? 'selected' : '' }}>SE 001</option>
            <option value="mysql_fourth" {{ $db=='mysql_fourth' ? 'selected' : '' }}>SE 005</option>
            <option value="mysql_fifth" {{ $db=='mysql_fifth' ? 'selected' : '' }}>SE 008</option>
        </select>
    </div>
</form>

            {{-- <button type="submit" class="btn btn-primary mt-3">Simpan</button> --}}
        </div>
    </section>
</div>
@endsection
