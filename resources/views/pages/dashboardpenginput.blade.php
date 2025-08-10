@extends('layouts.app')

@section('title', 'Stock Opname')

@push('style')
    <style>
        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0 0.5rem;
            justify-content: center;
            max-width: 80vw;
            margin: 0 auto;
        }

        .box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            padding: 2rem 2rem;
            width: 80vw;
            /* supaya full lebar layar HP */
            max-width: 80vw;
            box-sizing: border-box;
            transition: transform 0.2s ease;
            font-size: 1.8rem;
            /* font lebih besar */
            word-wrap: break-word;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
        }

        .box-header {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #222;
        }

        .box-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
            font-size: 1.2rem;
        }

        @media (max-width: 576px) {
            .box {
                padding: 2rem 1.5rem;
                font-size: 1.2rem;
                /* tambah lagi font size di HP */
                width: 90vw;
                max-width: 100vw;
            }

            .box-header {
                font-size: 1.2rem;
            }

            .box-content p {
                font-size: 1.2rem;
                line-height: 1.8;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Stock Opname</h1>
            </div>

            <div class="section-body">
                <input type="text" id="search-input" placeholder="Cari opname..." class="form-control mb-3" />

                <div id="posopname-container" class="box-container">

                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('search-input');
            let allData = [];

            fetchPosopnames();

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const filtered = allData.filter(item => {
                    return (
                        item.opname_id?.toLowerCase().includes(keyword) ||
                        (item.date && item.date.toLowerCase().includes(keyword)) ||
                        (item.status && item.status.toLowerCase().includes(keyword)) ||
                        (item.location?.name && item.location.name.toLowerCase().includes(
                            keyword)) ||
                        (item.note && item.note.toLowerCase().includes(keyword)) ||
                        formatType(item.type).toLowerCase().includes(keyword)
                    );
                });
                renderBoxes(filtered);
            });

            function fetchPosopnames() {
                fetch("{{ route('posopnamepenginput.posopnamepenginput') }}")
                    .then(response => response.json())
                    .then(data => {
                        allData = data.data || [];
                        // Debug cek isi type tiap item
                        allData.forEach(item => {
                            console.log('opname_id:', item.opname_id, 'type:', item.type,
                                'typeof type:', typeof item.type);
                        });
                        if (allData.length === 0) {
                            document.getElementById('posopname-container').innerHTML =
                                '<p>Tidak ada data opname.</p>';
                            return;
                        }
                        renderBoxes(allData);
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        document.getElementById('posopname-container').innerHTML = '<p>Gagal memuat data.</p>';
                    });
            }

            function renderBoxes(data) {
                const container = document.getElementById('posopname-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = '<p>Tidak ada data ditemukan.</p>';
                    return;
                }

                data.forEach(item => {
                    const box = document.createElement('div');
                    box.classList.add('box');

                    box.innerHTML = `
                    <div class="box-header">Location SO: ${item.location?.name ?? '-'}</div>
                    <div class="box-content">
                        <p><strong>ID:</strong> ${item.opname_id ?? '-'}</p>
                        <p><strong>Date:</strong> ${item.date ?? '-'}</p>
                        <p><strong>Status:</strong> ${item.status ?? '-'}</p>
                        <p><strong>Type:</strong> ${formatType(item.type)}</p>
                        <p><strong>Note:</strong> ${item.note ?? '-'}</p>
                        <div>
                            ${item.status === 'CANCELED' 
                                ? `<button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-eye-slash"></i> Locked</button>`
                                : `<a href="/dashboardadmin/${item.opname_id}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i> Show</a>`
                            }
                        </div>
                    </div>
                `;

                    container.appendChild(box);
                });
            }

            function formatType(type) {
                let t = type;
                if (typeof type === 'string') {
                    t = parseInt(type, 10);
                }
                if (typeof t !== 'number' || isNaN(t)) {
                    return 'Unknown';
                }
                switch (t) {
                    case 0:
                        return 'Global';
                    case 1:
                        return 'Partial';
                    case 2:
                        return 'Per Item';
                    default:
                        return 'Unknown';
                }
            }
        });
    </script>
@endpush
