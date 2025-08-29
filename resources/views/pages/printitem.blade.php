<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Stock Opname - {{ $posopnamesublocation->first()->form_number ?? '-' }}</title>
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
        <a href="{{ route('pages.showdashboard', ['opname_id' => $posopname->opname_id]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="7" style="background:none;">
                    <h2>Stock Opname - {{ $posopnamesublocation->first()->form_number ?? '-' }}</h2>
                </th>
            </tr>
            <tr class="header-row">
                <td><strong>Tanggal</strong></td>
                <td>{{ $posopname->date ?? '-' }}</td>
                <td><strong>Lokasi</strong></td>
                <td>{{ $posopname->location->name ?? '-' }}</td>
                <td><strong>User</strong></td>
                  <td colspan="2">
                    {{ optional($posopnamesublocation->first()->oxy)->full_name ??
                        (optional($posopnamesublocation->first()->users)->name ?? '-') }}
                </td>
            </tr>
            <tr class="header-row">
                <td><strong>Note</strong></td>
                <td colspan="6">{{ $posopname->note ?? '-' }}</td>
            </tr>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Barcode</th>
                <th>Uom</th>
                <th>Nama Item</th>
                <th>Qty Real</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>

              @foreach ($posopnameitems as $index => $item)
                @php
                    $code = $item->item->code ?? '-';
                    $isDuplicate = ($codeCounts[$code] ?? 0) > 1;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="{{ $isDuplicate ? 'highlight-duplicate' : '' }}">
                        {{ $code }}
                    </td>
                    <td>
                        {{ $item->note ? $item->note : $item->item->barcode ?? '-' }}
                    </td>
                    <td>{{ $item->item->posunit->unit ?? '-' }}</td>
                    <td>{{ $item->item->name ?? '-' }}</td>
                    <td>{{ $item->qty_real }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right; font-weight:bold;">Total Qty Real:</td>
                <td colspan="2" style="font-weight:bold;">
                    {{ rtrim(rtrim($totalQtyReal, '0'), '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>