@extends('layouts.app')
@section('title', 'Show Dashboard')
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

                @if ($posopname->isNotEmpty())
                    <h1>Pos Opname : {{ $posopname->first()->ambildarisublocation->location->name ?? 'Tidak diketahui' }}
                    </h1>
                @endif


                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Stock Opname</a></div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">

                                @if ($posopname->isNotEmpty())
                                    <h4>Pos Sub Opname :
                                        {{ $posopname->first()->ambildarisublocation->name ?? 'Tidak diketahui' }}</h4>
                                @endif



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
                                                <th scope="col" class="text-center">Form Number</th>
                                                <th scope="col" class="text-center">Location</th>
                                                <th scope="col" class="text-center">Sub Location </th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-center">User</th>
                                                <th scope="col" class="text-center">date</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    </table>
                                </div>
                                <div class="action-buttons d-flex align-items-center gap-2">
    <button 
        type="button" 
        onclick="window.location='{{ route('dashboard') }}'" 
        class="btn btn-danger btn-sm"
    >
        <i class="fas fa-users"></i> Back
    </button>

    <a 
        href="{{ route('importso.use', $opname_id) }}" 
        class="btn btn-primary btn-sm"
    >
        <i class="fas fa-file-import"></i> Import Stock Opname
    </a>
    <form id="approved"
        action="{{ route('opname.approveAll', $opname_id) }}" 
        method="POST">
        @csrf
        <button type="submit" id="approved-button" class="btn btn-warning btn-sm">
            <i class="fas fa-check"></i> Approve Semua PRINTED
        </button>
    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        jQuery(document).ready(function($) {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('posopnamesublocations.posopnamesublocations') }}',
                    type: 'GET',
                    data: function(d) {
                        d.opname_id = '{{ $opname_id }}';
                    }
                },
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                columns: [
                    // {
                    //     data: 'opname_sub_location_id',
                    //     name: 'opname_sub_location_id',
                    //       orderable: false,
                    //     searchable: false,
                    //     className: 'text-center'
                    // },
                    {
                        data: 'form_number',
                        name: 'form_number',

                        className: 'text-center'
                    },
                    {
                        data: 'opname.location.name',
                        name: 'opname.location.name',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'sublocation.name',
                        name: 'sublocation.name',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'users.name',
                    //     name: 'users.name',
                    //     orderable: false,
                    //     searchable: false,

                    //     className: 'text-center'
                    // },
                    {
                        data: null, // ambil semua supaya bisa cek relasi
                        name: 'oxy.full_name', // tetap set name untuk sorting/search server-side
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.oxy && row.oxy.full_name) {
                                return row.oxy.full_name;
                            } else if (row.users && row.users.name) {
                                return row.users.name;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'date',
                        name: 'date',
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
            setInterval(function() {
                table.ajax.reload(null, false); // false = biar tetap di halaman yang sama
            }, 5000);
        });
    </script>
    <script>
         document.getElementById('approved-button').addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah pengiriman form langsung
            Swal.fire({
                title: 'Are You Sure?',
                text: "Change status from Printed to Approved!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Assign!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengkonfirmasi, submit form
                    document.getElementById('approved').submit();
                }
            });
        });
          @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endpush
