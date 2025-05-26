{{-- <!DOCTYPE html>
<html>
<head>
    <title>Pilih dan Tampilkan Database</title>
</head>
<body>
    <h2>Pilih Database</h2>

    <form action="{{ route('DB.index') }}" method="POST">
        @csrf
        <label>
            <input type="radio" name="db" value="utama" {{ old('db') == 'utama' ? 'checked' : '' }}> Utama
        </label>
        <label>
            <input type="radio" name="db" value="kedua" {{ old('db') == 'kedua' ? 'checked' : '' }}> Kedua
        </label>
        <br><br>
        <button type="submit">Tampilkan Data</button>
    </form>

    <br><hr><br>

    @isset($data)
        <h3>Data dari koneksi: {{ $db_yang_dipakai }}</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>BARA</th>
                <th>BARA2</th>
                <th>NAMA</th>
                <th>AWAL</th>
                <th>MASUK</th>
                <th>KELUAR</th>
                <th>SALDO</th>
                <th>AVER</th>
                <th>HBELI</th>
                <th>HJUAL</th>
                <th>STATUS</th>
                <th>KDGOL</th>
                <th>KDTOKO</th>
                <th>HPP</th>
                <th>SATUAN</th>
                
            </tr>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->BARA }}</td>
                    <td>{{ $item->BARA2 }}</td>
                    <td>{{ $item->NAMA }}</td>
                    <td>{{ $item->AWAL }}</td>
                    <td>{{ $item->MASUK }}</td>
                    <td>{{ $item->KELUAR }}</td>
                    <td>{{ $item->AVER }}</td>
                    <td>{{ $item->HBELI }}</td>
                    <td>{{ $item->HJUAL }}</td>
                    <td>{{ $item->STATUS }}</td>
                    <td>{{ $item->KDGOL }}</td>
                    <td>{{ $item->KDTOKO }}</td>
                    <td>{{ $item->HPP }}</td>
                    <td>{{ $item->SATUAN }}</td>
                    
                </tr>
            @endforeach
        </table>
    @endisset
</body>
</html> --}}
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
                <h1>Databases</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Stock Opname</a></div>
                </div>
              
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Datavbases</h4>

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
                                                <th scope="col" class="text-center">BARA</th>
                                                <th scope="col" class="text-center">BARA2</th>
                                                <th scope="col" class="text-center">NAMA</th>
                                                <th scope="col" class="text-center">AWAL</th>
                                                <th scope="col" class="text-center">MASUK</th>
                                                <th scope="col" class="text-center">KELUAR</th>
                                                <th scope="col" class="text-center">AVER</th>
                                                <th scope="col" class="text-center">HBELI</th>
                                                <th scope="col" class="text-center">HJUAL</th>
                                                <th scope="col" class="text-center">STATUS</th>
                                                <th scope="col" class="text-center">KDGOL</th>
                                                <th scope="col" class="text-center">KDTOKO</th>
                                                <th scope="col" class="text-center">HPP</th>
                                                <th scope="col" class="text-center">SATUAN</th>
                                            
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
                    url: '{{ route('mstock.mstock') }}',
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
                        data: 'AVER',
                        name: 'AVER',
                        className: 'text-center'
                    },
                    {
                        data: 'HBELI',
                        name: 'HBELI',
                        className: 'text-center'
                    },
                    {
                        data: 'HJUAL',
                        name: 'HJUAL',
                        className: 'text-center'
                    },
                    {
                        data: 'STATUS',
                        name: 'STATUS',
                        className: 'text-center'
                    },
                    {
                        data: 'KDGOL',
                        name: 'KDGOL',
                        className: 'text-center'
                    },
                    {
                        data: 'KDTOKO',
                        name: 'KDTOKO',
                        className: 'text-center'
                    },
                    {
                        data: 'HPP',
                        name: 'HPP',
                        className: 'text-center'
                    },
                    {
                        data: 'SATUAN',
                        name: 'SATUAN',
                        className: 'text-center'
                    }
                ],
            });
        });
    </script>
@endpush

