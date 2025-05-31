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
@endpush
