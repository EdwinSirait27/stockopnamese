{{-- @extends('layouts.app')

@section('title', 'Pilih dan Tampilkan Database')

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
            <h1>Database Viewer</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item">Database Viewer</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Database</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('DB.index') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="db">Pilih Database:</label>
                            <select class="form-control" name="db" id="db">
                                <option value="mysql" {{ old('db') == 'mysql' ? 'selected' : '' }}>Database Utama</option>
                                <option value="mysql2" {{ old('db') == 'mysql2' ? 'selected' : '' }}>Database Kedua</option>
                                <option value="mysql3" {{ old('db') == 'mysql3' ? 'selected' : '' }}>Database Ketiga</option>
                                <option value="mysql4" {{ old('db') == 'mysql4' ? 'selected' : '' }}>Database Keempat</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Tampilkan Data</button>
                    </form>
                </div>
            </div>

            @isset($data)
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Data dari Koneksi: <strong>{{ $db_yang_dipakai }}</strong></h4>
                    </div>
                    <div class="card-body table-responsive">
                     <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>BARA</th>
                                    <th>BARA2</th>
                                    <th>NAMA</th>
                                    <th>AVER</th>
                                    <th>SATUAN</th>
                                    <th>AWAL</th>
                                    <th>MASUK</th>
                                    <th>KELUAR</th>
                                    <th>SALDO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->BARA }}</td>
                                        <td>{{ $item->BARA2 }}</td>
                                        <td>{{ $item->NAMA }}</td>
                                        <td>{{ $item->AVER }}</td>
                                        <td>{{ $item->SATUAN }}</td>
                                        <td>{{ $item->AWAL }}</td>
                                        <td>{{ $item->MASUK }}</td>
                                        <td>{{ $item->KELUAR }}</td>
                                          <td>{{ $item->AWAL + $item->MASUK - $item->KELUAR }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endisset
        </div>
    </section>
</div>
@endsection --}}
@extends('layouts.app')

@section('title', 'Pilih dan Tampilkan Database')

@push('style')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> --}}
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Database Viewer</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="/dashboard">Dashboard</a></div>
                    <div class="breadcrumb-item">Database Viewer</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Choose Database</h4>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <form action="{{ route('DB.index') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="db">Choose Database:</label>
                                <select class="form-control" name="db" id="db">
                                    {{-- <option value="mysql" {{ old('db', request('db')) == 'mysql' ? 'selected' : '' }}>{{Database Utama}}</option>
                                <option value="mysql2" {{ old('db', request('db')) == 'mysql2' ? 'selected' : '' }}>Database Kedua</option> --}}
                                    <option value="mysql"
                                        {{ old('db', request('db', $db_yang_dipakai)) == 'mysql' ? 'selected' : '' }}>
                                        Main Database</option>
                                    <option value="mysql2"
                                        {{ old('db', request('db', $db_yang_dipakai)) == 'mysql2' ? 'selected' : '' }}>
                                        Second Database</option>

                                    {{-- <option value="mysql3" {{ old('db', request('db')) == 'mysql3' ? 'selected' : '' }}>Database Ketiga</option>
                                <option value="mysql4" {{ old('db', request('db')) == 'mysql4' ? 'selected' : '' }}>Database Keempat</option> --}}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Tampilkan Data</button>
                        </form>
                    </div>
                </div>
                @isset($koneksi)
                    <input type="hidden" id="db_koneksi" value="{{ $koneksi }}">
                    <div class="card mt-4">
                        {{-- <div class="card-header">
                            <h4>Data dari Koneksi: <strong>{{ $db_yang_dipakai }}</strong></h4>
                           
                            <form action="{{ route('stock.import') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="branch_id">Pilih Branch</label>
                                    <select name="branch_id" id="branch_id" class="form-control" required>
                                        <option value="" disabled selected>-- Pilih Branch --</option>
                                        @foreach ($branch as $item)
                                            <option value="{{ $item->id }}">{{ $item->BO }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="db" value="{{ $koneksi }}">
                                <button type="submit" class="btn btn-primary">Import ke DB Default (mysql)</button>
                            </form>
                            
                        </div> --}}
                      <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Data from connection: <strong>{{ $db_yang_dipakai }}</strong></h4>

    <form action="{{ route('stock.import') }}" method="POST" class="mb-0 d-flex align-items-center">
        @csrf

        <div class="form-group mb-0 me-3">
            <label for="bo_id" class="me-2 mb-0">Choose Branch</label>
            <select name="bo_id" id="bo_id" class="form-control" required style="min-width: 200px;">
                <option value="" disabled selected>-- Choose Branch --</option>
                @foreach ($branch as $item)
                    <option value="{{ $item->id }}">{{ $item->BO }} - {{ $item->CABANG }}</option>
                @endforeach
            </select>
        </div>

    
</div>

                        <div class="card-body table-responsive">
                            <table id="datatable-mstock" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>BARA</th>
                                        <th>BARA2</th>
                                        <th>NAMA</th>
                                        <th>AVER</th>
                                        <th>SATUAN</th>
                                        <th>AWAL</th>
                                        <th>MASUK</th>
                                        <th>KELUAR</th>
                                        <th>SALDO</th>
                                        <th>Cabang</th>

                                    </tr>
                                </thead>
                            </table>
    <input type="hidden" name="db" value="{{ $koneksi }}">
        <button type="submit" class="btn btn-primary">Import to DB Main (mysql)</button>
    </form>
                        </div>
                    </div>
                @endisset
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let db = $('#db_koneksi').val();
            if (db) {
                $('#datatable-mstock').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('DB.index') }}',
                        data: {
                            db: db
                        }
                    },

                    columns: [{
                            data: 'BARA',
                            name: 'BARA'
                        },
                        {
                            data: 'BARA2',
                            name: 'BARA2'
                        },
                        {
                            data: 'NAMA',
                            name: 'NAMA'
                        },
                        {
                            data: 'AVER',
                            name: 'AVER'
                        },
                        {
                            data: 'SATUAN',
                            name: 'SATUAN'
                        },
                        {
                            data: 'AWAL',
                            name: 'AWAL'
                        },
                        {
                            data: 'MASUK',
                            name: 'MASUK'
                        },
                        {
                            data: 'KELUAR',
                            name: 'KELUAR'
                        },
                        {
                            data: 'SALDO',
                            name: 'SALDO',
                            orderable: false,
                            searchable: false
                        }
                        ,
                        {
                            data: 'CABANG',
                            name: 'CABANG',
                            orderable: false,
                            searchable: false
                        },

                    ]
                });
            }
        });
    </script>
@endpush

 {{-- <form id="importForm" method="GET" action="{{ route('stock.import') }}">
                            <input type="hidden" name="db" value="{{ $koneksi }}">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin import dari DB {{ $koneksi }} ke mysql?')">
                                Import ke mysql
                            </button>
                            </form> --}}





{{-- <input type="hidden" name="db" value="{{ $koneksi }}">
    <button type="submit" class="btn btn-primary">Import ke DB Default (mysql)</button> --}}
