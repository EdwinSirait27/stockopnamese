@extends('layouts.app2')

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
                    <strong>Sub Lokasi:</strong> {{ $posopname->location->name ?? '-' }} <br>
                    <strong>Tanggal:</strong> {{ $posopnamesublocation->date ?? '-' }}
                </div>

                {{-- Form Scan --}}
                <form id="scanForm" method="POST"
                    action="{{ route('posopname.scanBarcodePreview', $posopnamesublocation->opname_sub_location_id) }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="barcode" id="barcode" class="form-control"
                            placeholder="Scan atau ketik barcode di sini..." autofocus autocomplete="off">
                        <button class="btn btn-primary" type="submit">Preview</button>
                    </div>
                </form>

                <div id="preview-container" class="card p-3" style="display:none;">
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
                        <div class="preview-value text-danger fw-bold" id="preview-warning"></div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Qty</div>
                        <input type="number" id="preview-qty" min="0" autofocus autocomplete="off" class="form-control"
                            style="max-width: 80px;">
                    </div>
                    <button id="btn-save" class="btn btn-success">Save</button>
                </div>
                <div class="table-responsive mt-4">
                    <table id="itemsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Action</th>
                               
                                <th style="width: 80px;">Barcode</th>
                                <th style="width: 150px;">Nama Item</th>
                                <th style="width: 80px;">Qty</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                 <form action="{{ route('posopnamesublocation.reqPrint', $posopnamesublocation->opname_sub_location_id) }}"
      method="POST"
      onsubmit="return confirm('Ubah status menjadi REQ PRINT?')">
    @csrf
    <button type="submit" class="btn btn-warning">
        Request Print
    </button>
</form>
            </div>
        </section>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="opname_item_id">
                        <div class="mb-3">
                            <label>Nama Item</label>
                            <input type="text" id="edit_nama" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Qty</label>
                            <input type="number" id="edit_qty" name="qty_real" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
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
                ajax: '{{ route('posopname.items', $posopnamesublocation->opname_sub_location_id) }}',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columns: [{
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'item.barcode',
                        defaultContent: '-'
                    },
                    {
                        data: 'item.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'qty_real',
                        defaultContent: '0'
                    }
                ]
            });

            // Submit scan form
            $('#scanForm').on('submit', function(e) {
                e.preventDefault();
                let barcode = $('#barcode').val();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: {
                        barcode: barcode,
                        _token: $('input[name="_token"]').val()
                    },
                    statusCode: {
                        200: function(res) {
                            scannedItem = res.data;
                            $('#preview-barcode').text(scannedItem.barcode);
                            $('#preview-name').text(scannedItem.name);
                            $('#preview-warning').text(''); // clear warning
                            $('#preview-qty').val(1);
                            $('#preview-container').show();
                            $('#barcode').val('').focus();
                        },
                        409: function(res) {
                            scannedItem = res.responseJSON.data;
                            $('#preview-barcode').text(scannedItem.barcode);
                            $('#preview-name').text(scannedItem.name);
                            $('#preview-warning').text('⚠ ' + res.responseJSON.message);
                            $('#preview-qty').val(1);
                            $('#preview-container').show();
                            $('#barcode').val('').focus();
                        },
                        404: function(res) {
                            alert(res.responseJSON.message);
                            $('#preview-container').hide();
                            $('#barcode').val('').focus();
                        }
                    }
                });
            });

            // Save button
            $('#btn-save').on('click', function() {
                if (!scannedItem) {
                    alert('Tidak ada item untuk disimpan');
                    return;
                }


                let qty = parseFloat($('#preview-qty').val()); // bisa decimal
                if (isNaN(qty) || qty < 0.000) {
                    alert('Qty harus minimal 0.000');
                    return;
                }

                $.ajax({
                    url: `/save-scanned-item/${opnameSubLocationId}`,
                    method: 'POST',
                    data: {
                        item_master_id: scannedItem.item_master_id,
                        qty: qty,
                        barcode: scannedItem.barcode,
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(res) {
                        alert(res.message);
                        $('#preview-container').hide();
                        scannedItem = null;
                        table.ajax.reload();
                        $('#barcode').focus();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON);
                        alert(xhr.responseJSON?.message || 'Gagal menyimpan item');
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
    <script>
        function editItem(id) {
            $.ajax({
                url: '/posopname-item/' + id,
                type: 'GET',
                success: function(response) {
                    let item = response.data;
                    $('#edit_id').val(item.opname_item_id);
                    $('#edit_nama').val(item.item.name);
                    $('#edit_qty').val(item.qty_real);
                    $('#editModal').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data item');
                }
            });
        }
        $('#editForm').on('submit', function(e) {
            e.preventDefault();

            let id = $('#edit_id').val();
            let qty_real = $('#edit_qty').val();
            $.ajax({
                url: '/posopname-item/' + id,
                type: 'PUT',
                data: {
                    qty_real: qty_real,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    alert(res.message);
                    $('#editModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Gagal memperbarui data');
                }
            });

        });
    </script>
    
@endpush
