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
    <input type="text" name="barcode" id="barcode" autofocus autocomplete="off">
    <button type="submit">Cari</button>
</form>

{{-- @if(isset($posopnameitem)) --}}
@if(isset($posopnameitem))
    <h3>Detail Item</h3>
    <p>Nama Item: {{ $posopnameitem->item->item_name ?? 'N/A' }}</p>
    <p>Barcode: {{ $posopnameitem->item->barcode ?? 'N/A' }}</p>
    <p>Status Item: {{ $posopnameitem->status ?? 'N/A' }}</p>
@endif


            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
