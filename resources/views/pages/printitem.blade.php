<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Stock Opname - {{ $form_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h2 {
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 15px;
        }
        .header td {
            border: none;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ url()->previous() }}">⬅ Kembali</a>
    </div>

    <h2>Stock Opname - {{ $form_number }}</h2>
    <table class="header">
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>{{ $posopname->date ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Lokasi</strong></td>
            <td>{{ $posopname->location->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Note</strong></td>
            <td>{{ $posopname->note ?? '-' }}</td>
        </tr>
        <tr>
            @foreach($posopnameitems as $index => $item)

            <td><strong>User</strong></td>
            <td>{{ $item->user_id ?? '-' }}</td>
            @endforeach

        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Barcode</th>
                <th>Nama Item</th>
                {{-- <th>Qty Sistem</th> --}}
                <th>Qty Real</th>
                {{-- <th>Selisih</th> --}}
                {{-- <th>Catatan</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($posopnameitems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->item->code ?? '-' }}</td>
                <td>{{ $item->item->barcode ?? '-' }}</td>
                <td>{{ $item->item->name ?? '-' }}</td>
                {{-- <td>{{ $item->qty_system }}</td> --}}
                <td>{{ $item->qty_real }}</td>
                {{-- <td>{{ $item->qty_real - $item->qty_system }}</td> --}}
                {{-- <td>{{ $item->note }}</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
