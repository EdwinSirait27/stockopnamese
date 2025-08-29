@extends('layouts.app')
@section('title', 'Import SO')
@push('styles')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-actions button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-actions button:hover {
            background-color: #0056b3;
        }
    </style>
@endpush

{{-- Laravel Excel Failures --}}
@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                {{-- <h1>Import File SO : {{ $posopname->location->name }}</h1> --}}
                <h1>Import File SO : {{ $dbLabel }}</h1>

            </div>
            {{-- <h4>Sub Location : {{ $posopname->ambildarisublocation->name }}</h4> --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Import Gagal (Validasi Excel):</strong>
                    <ul>
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="section-body">
                <div class="form-container">
                    {{-- <form id="import-create" action="{{ route('Importso.Importso') }}" method="POST" --}}
                    <form id="import-create" action="{{ route('Importso.use') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Choose your File Excel:</label>
                            <input type="file" name="file" id="file" required>
                        </div>

                        <div class="form-actions"
                            style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                            {{-- <a href="javascript:history.back()" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back
</a> --}}
                            <a href="{{ route('Stockopname.index') }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>


                            <button id="create-btn" type="submit" class="btn btn-primary">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>
        <h4>List Template</h4>
        <ul>
            @forelse($files as $file)
                <li>
                    {{ basename($file) }} -
                    <a href="{{ route('Importso.downloadso', ['filename' => basename($file)]) }}">
                        Download
                    </a>
                </li>
            @empty
                <li>There's no template here boy.</li>
            @endforelse
        </ul>

        <div class="alert alert-secondary mt-4" role="alert">
            <span class="text-dark">
                <strong>Important Note:</strong> <br>
                - for the file use excel xlsx type, csv may not work.<br>
                {{-- - for colom deductions, salary, take_home, and created_at just leave the value blank.<br> --}}
            </span>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('create-btn').addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah pengiriman form langsung
            Swal.fire({
                title: 'Are You Sure?',
                text: "Make sure the data you entered is correct!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Assign!',
                cancelButtonText: 'Abort'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengkonfirmasi, submit form
                    document.getElementById('import-create').submit();
                }
            });
        });
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    @if (session('errorr'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('errorr') }}',
            });
        </script>
    @endif
@endpush
