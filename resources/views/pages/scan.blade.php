@extends('layouts.app')

@section('title', 'Blank Page')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                {{-- <h1>Blank Page</h1> --}}
                <h1>Rak {{ $posopnamesublocation->form_number }}</h1>
            </div>

            <div class="section-body">
                @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('scan.post', $posopnamesublocation->opname_sub_location_id) }}" method="POST">
    @csrf
    <label for="barcode">Scan Barcode:</label>
    <input type="text" name="barcode" id="barcode" autofocus autocomplete="off" 
        value="{{ old('barcode', request('barcode') ?? '') }}">
    <button type="submit">Cari</button>
</form>

@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

@if(session('info'))
    <div class="alert alert-warning">{{ session('info') }}</div>
@endif

@if(isset($posopnameitems) && $posopnameitems->count())
    <h3>Posopnameitem ditemukan di opname berikut:</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Opname Sub Location ID</th>
                <th>Nama Item</th>
                <th>Barcode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posopnameitems as $item)
                <tr>
                    <td>{{ $item->opname_sub_location_id }}</td>
                    <td>{{ $item->item->name ?? 'N/A' }}</td>
                    <td>{{ $item->item->barcode ?? 'N/A' }}</td>
                    <td>{{ $item->status ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif(isset($itemMaster))
    <h3>Detail Item dari Item Master (belum masuk opname)</h3>
    <p>Nama Item: {{ $itemMaster->name ?? 'N/A' }}</p>
    <p>Barcode: {{ $itemMaster->barcode ?? 'N/A' }}</p>
@endif



            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
