@extends('layouts.app')
@section('title', 'Scan Barcode')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Scan Barcode</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <form id="barcodeForm" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="BARA2">Scan Barcode</label>
                                <input type="text" name="BARA2" id="BARA2" class="form-control"
                                    placeholder="Scan barcode..." autofocus>
                            </div>
                            <div class="form-group">
                                <label for="penghitung">Penghitung</label>
                                <input type="text" name="penghitung" id="penghitung" class="form-control"
                                    placeholder="penghitung" required>
                            </div>
                            <div class="form-group">
                                <label for="NAMA">Nama Barang</label>
                                <input type="text" name="NAMA" id="NAMA" class="form-control" placeholder="NAMA"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="FISIK">Quantity</label>
                                <input type="number" name="FISIK" id="FISIK" class="form-control"
                                    placeholder="Quantity" min="0" step="1" required>
                            </div>
                            <div class="form-group">
                                <label for="SATUAN">Satuan</label>
                                <input type="text" name="SATUAN" id="SATUAN" class="form-control"
                                    placeholder="SATUAN" readonly>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        <div class="mt-4 alert alert-danger" id="notFound" style="display:none;">
                            Produk tidak ditemukan.
                        </div>

                        <div class="mt-4 alert alert-warning" id="notForSO" style="display:none;">
                            Produk ditemukan, tetapi tidak untuk di stock opname.
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const barcodeInput = document.getElementById('BARA2');
        const notFoundBox = document.getElementById('notFound');
        const notForSOBox = document.getElementById('notForSO');
        const csrfToken = document.querySelector('input[name="_token"]').value;


        // Cegah reload halaman saat submit
        document.getElementById('barcodeForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });

        // Autofokus input saat halaman load
        window.addEventListener('DOMContentLoaded', () => {
            barcodeInput.focus();
        });

        // Ketika barcode discan
        barcodeInput.addEventListener('change', function() {
            const barcode = this.value.trim();
            if (!barcode) return;

            fetch("{{ route('scan.barcode') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        BARA2: barcode
                    })
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {

                    notFoundBox.style.display = 'none';
                    notForSOBox.style.display = 'none';

                    // Cek apakah item ditemukan tapi tidak untuk SO
                    if (data.status === 'not_for_so') {
                        notForSOBox.style.display = 'block';
                        document.getElementById('NAMA').value = data.item?.NAMA ?? '';
                        document.getElementById('SATUAN').value = data.item?.SATUAN ?? '';
                        document.getElementById('FISIK').value = '';
                        return;
                    }
                    if (data.error) {
                        notFoundBox.style.display = 'block';
                        document.getElementById('NAMA').value = '';
                        document.getElementById('SATUAN').value = '';
                        document.getElementById('FISIK').value = '';
                        return;
                    }

                    // Item valid untuk SO
                    document.getElementById('NAMA').value = data.NAMA ?? '';
                    document.getElementById('FISIK').value = data.FISIK ?? '';
                    document.getElementById('SATUAN').value = data.SATUAN ?? '';
                })
                .catch(() => {
                    notFoundBox.style.display = 'block';
                    notForSOBox.style.display = 'none';
                    document.getElementById('NAMA').value = '';
                    document.getElementById('SATUAN').value = '';
                    document.getElementById('FISIK').value = '';
                })
                .finally(() => {
                    barcodeInput.value = '';
                    barcodeInput.focus();
                });
        });
    </script>
@endpush
