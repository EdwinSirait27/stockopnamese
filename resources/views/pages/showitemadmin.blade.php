@extends('layouts.app')
@section('title', 'Show Item')
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

                                {{-- @if ($posopname->isNotEmpty())
                              
                                    @endif --}}
                                <h4>Pos Sub Opname : </h4>
                                    {{-- {{ $posopname->first()->ambildarisublocation->name ?? 'Tidak diketahui' }}</h4> --}}
                                    {{ Auth::user()->location->name ?? '-' }}
                                {{-- <h4>Detail Item - Form Number: {{ $form_number }}</h4> --}}
 {{-- <p><strong>Lokasi User:</strong> {{ Auth::user()->location->name ?? '-' }}</p> --}}



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
                                                <th scope="col">SKU</th>
                                                <th scope="col" class="text-center">Barcode</th>
                                                <th scope="col" class="text-center">Name </th>
                                                <th scope="col" class="text-center">Uom</th>
                                                <th scope="col" class="text-center">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    </table>
                                </div>
                                <div class="action-buttons d-flex align-items-center gap-2">
                                    <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
                                </div>
                                <div class="alert alert-secondary mt-4" role="alert">
                                    <span class="text-dark">
                                        <strong>Important Note:</strong> <br>
                                        - Fitur search hanya bisa search SKU yaa
                                        {{-- If you want to print payroll, ignore the day, just look at the year and month, you can only print payrolls once a month, okay.<br><br> --}}
                                    </span>
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
                    url: '{{ route('getshowitemadmin') }}',
                    type: 'GET',
                    data: {
                       opname_id: "{{ $opname_id }}",
                form_number: "{{ $form_number }}"
                    }
                },
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                columns: [
                    //                    {
                    //     data: 'opname_item_id',
                    //     name: 'opname_item_id',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'item.code',
                        name: 'item.code',
                        className: 'text-center'
                    },
                    //                    {
                    //     data: 'item.barcode',
                    //     name: 'item.barcode',
                    //     className: 'text-center'
                    // },
                    {
                        data: null,
                        name: 'note_or_barcode',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return row.note ?? (row.item?.barcode ?? '');
                        }
                    },
                    {
                        data: 'item.name',
                        name: 'item.name',
                        className: 'text-center'
                    },
                    {
                        data: 'item.posunit.unit',
                        name: 'item.posunit.unit',
                        className: 'text-center'
                    },
                    {
                        data: 'qty_real',
                        name: 'qty_real',
                        className: 'text-center'
                    }

                    // ,
                    //                    {
                    //     data: 'qty_real',
                    //     name: 'qty_real',
                    //     className: 'text-center'
                    // }
                    //                   {
                    //     data: 'opname_item_id',
                    //     name: 'opname_item_id',
                    //     className: 'text-center'
                    // },



                    // {
                    //     data: 'opname.location.name',
                    //     name: 'opname.location.name',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'sublocation.name',
                    //     name: 'sublocation.name',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'qty_real',
                    //     name: 'qty_real',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'users.name',
                    //     name: 'users.name',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'form_number',
                    //     name: 'form_number',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'date',
                    //     name: 'date',
                    //     className: 'text-center'
                    // }

                ],
            });
        });
    </script>
@endpush
