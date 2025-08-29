@extends('layouts.app')

@section('title', 'Stock Opname')

@push('style')
    <!-- CSS Libraries -->
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
                <h1>Stock Opname : {{ $dbLabel }}</h1>
            </div>

            <div class="section-body">
                <div class="mb-3">
                    {{-- <form method="GET" action="{{ route('Stockopname.index') }}">
                        <label for="db">Choose Databases:</label>
                        <select name="db" id="db" class="form-control d-inline-block w-auto">
                            <option value="mysql_third" {{ $db == 'mysql_third' ? 'selected' : '' }}>SE 001</option>
                            <option value="mysql_fourth" {{ $db == 'mysql_fourth' ? 'selected' : '' }}>SE 005</option>
                            <option value="mysql_fifth" {{ $db == 'mysql_fifth' ? 'selected' : '' }}>SE 008</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Load</button>
                    </form> --}}
                </div>

                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Kdtoko</th>
                                    <th scope="col" class="text-center">Kettoko</th>
                                    <th scope="col" class="text-center">Masuk</th>
                                    <th scope="col" class="text-center">Personil</th>
                                    <th scope="col" class="text-center">Inp Masuk</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons d-flex align-items-center gap-2">
                        <button type="button" onclick="window.location='{{ route('dashboard') }}'"
                            class="btn btn-danger btn-sm">
                            <i class="fas fa-users"></i> Back
                        </button>
    
                        <a href="{{ route('importso.use') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-import"></i> Import Stock Opname
                        </a>
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
        $(function() {
            // $('#datatable').DataTable({
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('stockopname.stockopname') }}",
                    data: function(d) {
                        d.db = "{{ $db }}";
                    }
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                columns: [{
                        data: 'kdtoko',
                        name: 'kdtoko',
                        className: 'text-center'
                    },
                    {
                        data: 'kettoko',
                        name: 'kettoko',
                        className: 'text-center',
                        defaultContent: 'empty'
                    },
                    {
                        data: 'masuk',
                        name: 'masuk',
                        className: 'text-center',
                        defaultContent: 'empty'
                    },
                    {
                        data: 'personil',
                        name: 'personil',
                        className: 'text-center',
                        defaultContent: 'empty'
                    },
                    {
                        data: 'inpmasuk',
                        name: 'inpmasuk',
                        className: 'text-center',
                        defaultContent: 'empty'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 10000);
        });
    </script>
@endpush
