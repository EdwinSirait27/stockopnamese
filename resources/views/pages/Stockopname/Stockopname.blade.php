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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center"
                    role="alert">
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn btn-sm btn-light border-0 ms-2" data-bs-dismiss="alert" aria-label="Close"
                        style="font-weight: bold;">X</button>
                </div>
            @endif
            <div class="section-body">

                <div class="card">
                    <div class="card-body">
                        {{-- Table --}}
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Kdtoko</th>
                                    <th class="text-center">Kettoko</th>
                                    <th class="text-center">Masuk</th>
                                    <th class="text-center">Personil</th>
                                    <th class="text-center">Inp Masuk</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card-footer">
                        <div class="action-buttons d-flex align-items-center gap-4">
                            {{-- Back --}}
                            <button type="button" onclick="window.location='{{ route('dashboard') }}'"
                                class="btn btn-danger btn-sm">
                                <i class="fas fa-users"></i> Back
                            </button>

                            {{-- Import --}}
                            <a href="{{ route('importso.use') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-import"></i> Import Stock Opname
                            </a>
                            <form id="refreshForm" action="{{ route('stockopname.refresh') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync-alt"></i> Refresh Mstock SOG
                                </button>
                            </form>

                        </div>
                    </div>
                </div> <!-- end card -->

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function() {
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
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Auto reload setiap 10 detik
            setInterval(function() {
                table.ajax.reload(null, false);
            }, 10000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('refreshForm').addEventListener('submit', function(e) {
            e.preventDefault(); // cegah submit langsung

            Swal.fire({
                title: 'R u Sure?',
                text: "The mstock_soglo data will be deleted and refreshed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, refresh!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit(); // submit form kalau user klik Ya
                }
            });
        });
    </script>
@endpush
