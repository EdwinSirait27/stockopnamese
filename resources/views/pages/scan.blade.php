{{-- @extends('layouts.app')

@section('title', 'Scan Barcode')
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
                <h1>Rak {{ $posopnamesublocation->form_number }}</h1>
                <small>Lokasi: {{ $posopnamesublocation->sublocation->location->name ?? '-' }}</small>
            </div>

            <div class="section-body">
                <div class="mb-3">
                    <strong>Opname ID:</strong> {{ $opname_id }} <br>
                    <strong>Nama Lokasi:</strong> {{ $posopname->location->name ?? '-' }} <br>
                    <strong>Tanggal:</strong> {{ $posopnamesublocation->date ?? '-' }}
                </div>

                <form id="scanForm" method="POST" action="{{ route('posopname.scanBarcode', $posopnamesublocation->opname_sub_location_id) }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan atau ketik barcode di sini..." autofocus>
                        <button class="btn btn-primary" type="submit">Scan</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="itemsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 80px;">Barcode</th>
                                <th style="width: 150px;">Nama Item</th>
                                <th style="width: 80px;">Qty</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#itemsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{{ route('posopname.items', $posopnamesublocation->opname_sub_location_id) }}',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'item.barcode', defaultContent: '-' },
                    { data: 'item.name', defaultContent: '-' },
                    { data: 'qty_real', defaultContent: '0' }
                ]
            });

            $('#scanForm').on('submit', function(e) {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res) {
                    $('#barcode').val('').focus();
                    table.ajax.reload();
                }).fail(function() {
                    alert('Barcode tidak ditemukan!');
                    $('#barcode').val('').focus();
                });
            });
        });
    </script>
@endpush --}}
{{-- @extends('layouts.app')

@section('title', 'Scan Barcode')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
        #preview-container {
            margin-bottom: 1rem;
            display: none;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rak {{ $posopnamesublocation->form_number }}</h1>
                <small>Lokasi: {{ $posopnamesublocation->sublocation->location->name ?? '-' }}</small>
            </div>

            <div class="section-body">
                <div class="mb-3">
                    <strong>Opname ID:</strong> {{ $opname_id }} <br>
                    <strong>Nama Lokasi:</strong> {{ $posopname->location->name ?? '-' }} <br>
                    <strong>Tanggal:</strong> {{ $posopnamesublocation->date ?? '-' }}
                </div>

                <form id="scanForm" method="POST" action="{{ route('posopname.scanBarcodePreview', $posopnamesublocation->opname_sub_location_id) }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan atau ketik barcode di sini..." autofocus autocomplete="off">
                        <button class="btn btn-primary" type="submit">Preview</button>
                    </div>
                </form>

                <div id="preview-container" class="table-responsive card p-3">
                    <h5>Preview Item</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Item</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="preview-barcode"></td>
                                <td id="preview-name"></td>
                                <td><input type="number" id="preview-qty" min="1" value="1" class="form-control" style="width: 80px;"></td>
                                <td><button id="btn-save" class="btn btn-success">Save</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <table id="itemsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 80px;">Barcode</th>
                                <th style="width: 150px;">Nama Item</th>
                                <th style="width: 80px;">Qty</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    const opnameSubLocationId = '{{ $posopnamesublocation->opname_sub_location_id }}';

    let scannedItem = null;

    var table = $('#itemsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ route("posopname.items", $posopnamesublocation->opname_sub_location_id) }}',
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        columns: [
            { data: null, render: (data, type, row, meta) => meta.row + 1 },
            { data: 'item.barcode', defaultContent: '-' },
            { data: 'item.name', defaultContent: '-' },
            { data: 'qty_real', defaultContent: '0' }
        ]
    });

    $('#preview-container').hide();

    $('#scanForm').on('submit', function(e) {
        e.preventDefault();
        let barcode = $('#barcode').val();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: { barcode: barcode, _token: $('input[name="_token"]').val() },
            success: function(res) {
                scannedItem = res.data;

                $('#preview-barcode').text(scannedItem.barcode);
                $('#preview-name').text(scannedItem.name);
                $('#preview-qty').val(1);
                $('#preview-container').show();

                $('#barcode').val('').focus();
            },
            error: function() {
                alert('Barcode tidak ditemukan!');
                $('#preview-container').hide();
                $('#barcode').val('').focus();
            }
        });
    });

    $('#btn-save').on('click', function() {
        if (!scannedItem) {
            alert('Tidak ada item untuk disimpan');
            return;
        }

        let qty = parseInt($('#preview-qty').val());
        if (qty < 1) {
            alert('Qty harus minimal 1');
            return;
        }

        $.ajax({
            url: `/save-scanned-item/${opnameSubLocationId}`,
            method: 'POST',
            data: {
                item_master_id: scannedItem.item_master_id,
                qty: qty,
                _token: $('input[name="_token"]').val()
            },
            success: function(res) {
                alert(res.message);

                $('#preview-container').hide();
                scannedItem = null;
                table.ajax.reload();
                $('#barcode').focus();
            },
            error: function() {
                alert('Gagal menyimpan item');
            }
        });
    });
});
</script>
@endpush --}}
@extends('layouts.app')

@section('title', 'Scan Barcode')
@push('style')
    <style>
        .text-center {
            text-align: center;
        }
        #preview-container {
            margin-bottom: 1rem;
            display: none;
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
            background-color: #f9f9f9;
            max-width: 600px;
        }
        #preview-container h5 {
            margin-bottom: 1rem;
        }
        .preview-row {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .preview-label {
            font-weight: 600;
            width: 120px;
            color: #555;
        }
        .preview-value {
            flex: 1;
        }
        #preview-qty {
            width: 80px;
        }
        #btn-save {
            padding: 0.4rem 1.2rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rak {{ $posopnamesublocation->form_number }}</h1>
                <small>Lokasi: {{ $posopnamesublocation->sublocation->location->name ?? '-' }}</small>
            </div>

            <div class="section-body">
                <div class="mb-3">
                    <strong>Opname ID:</strong> {{ $opname_id }} <br>
                    <strong>Nama Lokasi:</strong> {{ $posopname->location->name ?? '-' }} <br>
                    <strong>Tanggal:</strong> {{ $posopnamesublocation->date ?? '-' }}
                </div>

                {{-- Form Scan --}}
                <form id="scanForm" method="POST" action="{{ route('posopname.scanBarcodePreview', $posopnamesublocation->opname_sub_location_id) }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan atau ketik barcode di sini..." autofocus autocomplete="off">
                        <button class="btn btn-primary" type="submit">Preview</button>
                    </div>
                </form>

                <div id="preview-container" class="card p-3">
                    <h5>Preview Item</h5>
                    <div class="preview-row">
                        <div class="preview-label">Barcode</div>
                        <div class="preview-value" id="preview-barcode"></div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Nama Item</div>
                        <div class="preview-value" id="preview-name"></div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Warning</div>
                        <div class="preview-value" id="preview-name"></div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Qty</div>
                        <input type="number" id="preview-qty" min="1" value="1" class="form-control" style="max-width: 80px;">
                    </div>
                    <button id="btn-save" class="btn btn-success">Save</button>
                </div>

                {{-- Tabel Items --}}
                <div class="table-responsive mt-4">
                    <table id="itemsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 80px;">Barcode</th>
                                <th style="width: 150px;">Nama Item</th>
                                <th style="width: 80px;">Qty</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    const opnameSubLocationId = '{{ $posopnamesublocation->opname_sub_location_id }}';

    let scannedItem = null;

    // Inisialisasi DataTable
    var table = $('#itemsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: '{{ route("posopname.items", $posopnamesublocation->opname_sub_location_id) }}',
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        columns: [
            { data: null, render: (data, type, row, meta) => meta.row + 1 },
            { data: 'item.barcode', defaultContent: '-' },
            { data: 'item.name', defaultContent: '-' },
            { data: 'qty_real', defaultContent: '0' }
        ]
    });

    // Hide preview container awalnya
    $('#preview-container').hide();

    // Submit scan form: ambil preview saja dulu
    $('#scanForm').on('submit', function(e) {
        e.preventDefault();
        let barcode = $('#barcode').val();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: { barcode: barcode, _token: $('input[name="_token"]').val() },
            success: function(res) {
                scannedItem = res.data;

                // Tampilkan preview tanpa tabel
                $('#preview-barcode').text(scannedItem.barcode);
                $('#preview-name').text(scannedItem.name);
                $('#preview-qty').val(1);
                $('#preview-container').show();

                $('#barcode').val('').focus();
            },
            error: function() {
                alert('Barcode tidak ditemukan!');
                $('#preview-container').hide();
                $('#barcode').val('').focus();
            }
        });
    });

    // Save button
    $('#btn-save').on('click', function() {
        if (!scannedItem) {
            alert('Tidak ada item untuk disimpan');
            return;
        }

        let qty = parseInt($('#preview-qty').val());
        if (qty < 1) {
            alert('Qty harus minimal 1');
            return;
        }

        $.ajax({
            url: `/save-scanned-item/${opnameSubLocationId}`,
            method: 'POST',
            data: {
                item_master_id: scannedItem.item_master_id,
                qty: qty,
                _token: $('input[name="_token"]').val()
            },
            success: function(res) {
                alert(res.message);

                // Reset preview dan reload tabel
                $('#preview-container').hide();
                scannedItem = null;
                table.ajax.reload();
                $('#barcode').focus();
            },
            error: function() {
                alert('Gagal menyimpan item');
            }
        });
    });
});
</script>
<script>
  window.onload = function() {
    document.getElementById('barcode').focus();
  };
</script>
@endpush
