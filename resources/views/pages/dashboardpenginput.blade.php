@extends('layouts.app2')
@section('title', 'Stock Opname')
@push('style')
    <style>
        .box-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            padding: 0.5rem;
        }

        .box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            width: 100%;
            aspect-ratio: 1 / 1;
            box-sizing: border-box;
            transition: transform 0.2s ease;
            font-size: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
        }

        .box-header {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #222;
        }

        .box-content p {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        @media (max-width: 576px) {
            .box {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush
@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div style="position: sticky; top: 0; background: white; z-index: 100; padding-top: 10px;">
                    {{-- <input type="text" id="search-input" placeholder="Scan QRCode" class="form-control mb-3"
                        autocomplete="off" /> --}}
                        <input type="text" id="search-input" 
       placeholder="Scan QRCode" 
       class="form-control mb-3"
       autocomplete="off" />

<script>
  const input = document.getElementById("search-input");

  // Fokus pertama kali
  input.focus();

  // Kalau kehilangan fokus, otomatis balik lagi
  input.addEventListener("blur", () => {
    setTimeout(() => input.focus(), 100);
  });
</script>

                </div>

                <div id="posopname-container" class="box-container">
                    <p>Loading data...</p>
                </div>
               <form id="logout-form" method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="dropdown-item has-icon text-danger" style="background:none; border:none; padding:0;">
        <i class="fas fa-sign-out-alt"></i> Logout
    </button>
</form>

            </div>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('search-input');
            const container = document.getElementById('posopname-container');
            let allData = [];
            let debounceTimer;

            fetchPosopnames(); // load awal

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const keyword = this.value.trim();
                    fetchPosopnames(keyword);
                }, 300);
            });

            function fetchPosopnames(keyword = '') {
                fetch("{{ route('posopnamesublocationpenginput.posopnamesublocationpenginput') }}?search[value]=" +
                        encodeURIComponent(keyword))
                    .then(response => response.json())
                    .then(data => {
                        allData = data.data || [];
                        if (allData.length === 0) {
                            container.innerHTML = '<p>Tidak ada data.</p>';
                            return;
                        }
                        renderBoxes(allData);
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        container.innerHTML = '<p>Gagal memuat data.</p>';
                    });
            }


            function renderBoxes(data) {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<p>Tidak ada data ditemukan.</p>';
                    return;
                }

                data.forEach(item => {
                    const box = document.createElement('div');
                    box.classList.add('box');
                    box.style.cursor = 'pointer';

                    // klik menuju route scan
                    box.addEventListener('click', function() {
                        window.location.href =
                            "{{ route('scan', ['opname_sub_location_id' => 'ID_PLACEHOLDER']) }}"
                            .replace('ID_PLACEHOLDER', item.opname_sub_location_id);
                    });

                    box.innerHTML = `
    <div class="box-content">
        <div>
            <p><strong></strong> ${item.form_number ?? '-'}</p>
        </div>
      
    </div>
`;
                    container.appendChild(box);
                });
            }

        });
    </script>
@endpush












































































































{{-- <div class="mt-2">
                            <a href="/opname/show/${item.form_number}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Show
                            </a>
                            <a href="/opname/print/${item.form_number}" class="btn btn-sm btn-success" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div> --}}




{{-- @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('search-input');
        const container = document.getElementById('posopname-container');
        let allData = [];

        // Fetch data from server
        fetchPosopnames();

        // Listen for typing on search input with debounce
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const keyword = this.value.trim().toLowerCase();
                if (keyword === '') {
                    renderBoxes(allData);
                } else {
                    const filtered = allData.filter(item => {
                        
                        return (
                            (item.opname_sub_location_id && item.opname_sub_location_id.toLowerCase().includes(keyword)) ||
                            (item.opname_id && item.opname_id.toLowerCase().includes(keyword)) ||
                            (item.sub_location_name && item.sub_location_name.toLowerCase().includes(keyword)) ||
                            (item.status && item.status.toLowerCase().includes(keyword)) ||
                            (item.form_number && item.form_number.toLowerCase().includes(keyword)) ||
                            (item.date && item.date.toLowerCase().includes(keyword)) ||
                            (item.users.name && item.users.name.toLowerCase().includes(keyword))
                        );
                    });
                    renderBoxes(filtered);
                }
            }, 300); // delay 300ms for better UX
        });

        function fetchPosopnames() {
            fetch("{{ route('posopnamesublocationpenginput.posopnamesublocationpenginput') }}")
                .then(response => response.json())
                .then(data => {
                    allData = data.data || [];
                    if (allData.length === 0) {
                        container.innerHTML = '<p>Tidak ada data opname sublocation.</p>';
                        return;
                    }
                    renderBoxes(allData);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    container.innerHTML = '<p>Gagal memuat data.</p>';
                });
        }

        function renderBoxes(data) {
            container.innerHTML = '';

            if (data.length === 0) {
                container.innerHTML = '<p>Tidak ada data ditemukan.</p>';
                return;
            }

            data.forEach(item => {
                const box = document.createElement('div');
                box.classList.add('box');

                box.innerHTML = `
                    <div class="box-header">Sublocation: ${item.sub_location_name ?? '-'}</div>
                    <div class="box-content">
                        <p><strong>Opname Sub Location ID:</strong> ${item.opname_sub_location_id ?? '-'}</p>
                        <p><strong>Opname ID:</strong> ${item.opname_id ?? '-'}</p>
                        <p><strong>Status:</strong> ${item.status ?? '-'}</p>
                        <p><strong>Form Number:</strong> ${item.form_number ?? '-'}</p>
                        <p><strong>Date:</strong> ${item.date ?? '-'}</p>
                        <p><strong>User ID:</strong> ${item.users.name ?? '-'}</p>
                        <div class="mt-2">
                            <a href="/opname/show/${item.form_number}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Show
                            </a>
                            <a href="/opname/print/${item.form_number}" class="btn btn-sm btn-success" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                `;

                container.appendChild(box);
            });
        }
    });
</script>
@endpush --}}

{{-- @section('main')
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
                    item.opname_sub_location_id?.toLowerCase().includes(keyword) ||
                    item.opname_id?.toLowerCase().includes(keyword) ||
                    (item.sub_location_name && item.sub_location_name.toLowerCase().includes(keyword)) ||
                    (item.status && item.status.toLowerCase().includes(keyword)) ||
                    (item.form_number && item.form_number.toLowerCase().includes(keyword)) ||
                    (item.date && item.date.toLowerCase().includes(keyword)) ||
                    (item.user_id && item.user_id.toLowerCase().includes(keyword))
                );
            });
            renderBoxes(filtered);
        });

        function fetchPosopnames() {
            fetch("{{ route('posopnamesublocationpenginput.posopnamesublocationpenginput') }}") // ganti route sesuai route baru controller
                .then(response => response.json())
                .then(data => {
                    allData = data.data || [];
                    if (allData.length === 0) {
                        document.getElementById('posopname-container').innerHTML =
                            '<p>Tidak ada data opname sublocation.</p>';
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
                    <div class="box-header">Sublocation: ${item.sub_location_name ?? '-'}</div>
                    <div class="box-content">
                        <p><strong>Opname Sub Location ID:</strong> ${item.opname_sub_location_id ?? '-'}</p>
                        <p><strong>Opname ID:</strong> ${item.opname_id ?? '-'}</p>
                        <p><strong>Status:</strong> ${item.status ?? '-'}</p>
                        <p><strong>Form Number:</strong> ${item.form_number ?? '-'}</p>
                        <p><strong>Date:</strong> ${item.date ?? '-'}</p>
                        <p><strong>User ID:</strong> ${item.user_id ?? '-'}</p>
                        <div>
                            <a href="/opname/show/${item.form_number}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Show
                            </a>
                            <a href="/opname/print/${item.form_number}" class="btn btn-sm btn-success" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                `;

                container.appendChild(box);
            });
        }
    });
</script>
@endpush --}}

























































{{-- @extends('layouts.app')

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
@endpush --}}
