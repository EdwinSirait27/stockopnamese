<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Stock Opname : {{$dbLabel}}</title>
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
        .header-row td {
            border: none;
            padding: 2px 4px;
            background: #fff;
        }

           @media print {
            .no-print {
                display: none;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            .highlight-duplicate {
                background-color: yellow !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            tfoot td {
                text-align: right;
                font-size: 12px;
            }

             tfoot td::after {
}

        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨 Print</button>
       <a href="{{ route('Stockopname.index', ['db' => $db]) }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="7" style="background:none;">
                    <h2>Fixture : {{ $kdtoko }}</h2>
                </th>
            </tr>
            <tr class="header-row">
                <td><strong>Lokasi</strong></td>
                <td>{{ $dbLabel ?? '-' }}</td>
                <td><strong>Penghitung</strong></td>
                <td colspan="2">{{ $store->personil ?? '-' }}</td>
                <td><strong>Penginput</strong></td>
                  <td colspan="2">
                    {{ $store->inpmasuk ?? 'empty'}}
                </td>
            </tr>
            <tr>
                <th>No</th>
                <th>Fixture</th>
                <th>Nama</th>
                <th>Bara</th>
                <th>Barcode</th>
                <th>Qty Real</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
               @foreach($details as $i => $d)
                  @php
                $isDuplicate = ($nameCounts[$d->nama_barang] ?? 0) > 1;
                //  $totalFisik = $isDuplicate ? ($sumFisik[$d->nama_barang] ?? $d->FISIK) : $d->FISIK;
            @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $d->KDTOKO }}</td>
            <td class="{{ $isDuplicate ? 'highlight-duplicate' : '' }}">
                    {{ $d->nama_barang }}
                </td>
            <td>{{ $d->BARA }}</td>
            <td>{{ $d->BARCODE }}</td>
            <td>{{ $d->FISIK }}</td>
            <td></td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align:right; font-weight:bold;">Total Qty Real:  {{ $totalFisik}}</td>
              
            </tr>
        </tfoot>
    </table>

</body>

</html>