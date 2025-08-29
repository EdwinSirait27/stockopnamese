@extends('layouts.app')

@section('title', 'Detail Stock Opname - ' . $kdtoko)

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
        {{-- <div class="section-header">
            <h1>Detail Stock Opname (KDTOKO: {{ $kdtoko }})</h1>
            <h2>Detail Stock Opname name</h2>
            <div class="section-header-breadcrumb">
                <a href="{{ route('Stockopname.index', ['db' => $db]) }}" class="btn btn-secondary">← Kembali</a>
            </div>
        </div> --}}
<div class="section-header d-flex justify-between items-center">
    <div>
        <h1 class="mb-1">Detail Stock Opname : {{ $dbLabel }}</h1>
        <h5 class="text-muted text-sm font-normal">Fixture : {{ $kdtoko }} </h5>
        <h5 class="text-muted text-sm font-normal">Penghitung : {{ $store->personil ?? 'Kosong' }} </h5>
        <h5 class="text-muted text-sm font-normal">Penginput : {{ $store->inpmasuk ?? 'Kosong' }} </h5>
    </div>
    <div class="section-header-breadcrumb">
        <a href="{{ route('Stockopname.index', ['db' => $db]) }}" class="btn btn-secondary">← Kembali</a>
    </div>
</div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table id="detail-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                {{-- <th>No</th> --}}
                                <th>KDTOKO</th>
                                <th>Name</th>
                                <th>Bara</th>
                                {{-- <th>No Urut</th> --}}
                                <th>Qty Real</th>
                                <th>Barcode</th>
                                {{-- <th>ID</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($details as $i => $row)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $row->KDTOKO ?? 'empty' }}</td>
                                    <td>{{ $row->BARA ?? 'empty' }}</td>
                                    <td>{{ $row->NOURUT ?? 'empty' }}</td>
                                    <td>{{ $row->FISIK ?? 'empty' }}</td>
                                    <td>{{ $row->BARCODE ?? 'empty' }}</td>
                                    <td>{{ $row->ID ?? 'empty' }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
       <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script>
        $(function() {
            $('#detail-table').DataTable();
        });
    </script> --}}
      <script>
        $(function() {
            $('#detail-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stockopname.details.datatables', ['db' => $db, 'kdtoko' => $kdtoko]) }}",
                  lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                columns: [
                    // { data: 'KDTOKO', name: 'KDTOKO', className: 'text-center' },
                    // { data: 'BARA', name: 'BARA', className: 'text-center' },
                    // { data: 'NOURUT', name: 'NOURUT', className: 'text-center' },
                    // { data: 'FISIK', name: 'FISIK', className: 'text-center' },
                    // { data: 'BARCODE', name: 'BARCODE', className: 'text-center' },
                    // { data: 'ID', name: 'ID', className: 'text-center' },
                      { data: 'KDTOKO', name: 'KDTOKO', className: 'text-center' },
                      { data: 'nama_barang', name: 'nama_barang', className: 'text-center' }, // dari mstock
    { data: 'BARA', name: 'BARA', className: 'text-center' },
    // { data: 'NOURUT', name: 'NOURUT', className: 'text-center' },
    { data: 'FISIK', name: 'FISIK', className: 'text-center' },
    { data: 'BARCODE', name: 'BARCODE', className: 'text-center' },
    // { data: 'ID', name: 'ID', className: 'text-center' },
                ]
            });
              
        });
    </script>
@endpush
