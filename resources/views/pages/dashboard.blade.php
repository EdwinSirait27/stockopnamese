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
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>mtokosoglo</h4>

                            </div>
                            @if (isset($success))
                                <div class="alert alert-success">
                                    {{ $success }}
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
@endpush
