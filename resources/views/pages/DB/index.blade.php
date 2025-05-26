<!DOCTYPE html>
<html>
<head>
    <title>Pilih dan Tampilkan Database</title>
</head>
<body>
    <h2>Pilih Database</h2>

    <form action="{{ route('DB.index') }}" method="POST">
        @csrf
        <label>
            <input type="radio" name="db" value="utama" {{ old('db') == 'utama' ? 'checked' : '' }}> Utama
        </label>
        <label>
            <input type="radio" name="db" value="kedua" {{ old('db') == 'kedua' ? 'checked' : '' }}> Kedua
        </label>
        <br><br>
        <button type="submit">Tampilkan Data</button>
    </form>

    <br><hr><br>

    @isset($data)
        <h3>Data dari koneksi: {{ $db_yang_dipakai }}</h3>
        <table border="1" cellpadding="5">
            <tr>
                <th>BARA</th>
                <th>BARA2</th>
                <th>NAMA</th>
                <th>AWAL</th>
                <th>MASUK</th>
                <th>KELUAR</th>
                <th>SALDO</th>
                <th>AVER</th>
                <th>HBELI</th>
                <th>HJUAL</th>
                <th>STATUS</th>
                <th>KDGOL</th>
                <th>KDTOKO</th>
                <th>HPP</th>
                <th>SATUAN</th>
                
                {{-- Tambah kolom sesuai kebutuhan --}}
            </tr>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->BARA }}</td>
                    <td>{{ $item->BARA2 }}</td>
                    <td>{{ $item->NAMA }}</td>
                    <td>{{ $item->AWAL }}</td>
                    <td>{{ $item->MASUK }}</td>
                    <td>{{ $item->KELUAR }}</td>
                    <td>{{ $item->AVER }}</td>
                    <td>{{ $item->HBELI }}</td>
                    <td>{{ $item->HJUAL }}</td>
                    <td>{{ $item->STATUS }}</td>
                    <td>{{ $item->KDGOL }}</td>
                    <td>{{ $item->KDTOKO }}</td>
                    <td>{{ $item->HPP }}</td>
                    <td>{{ $item->SATUAN }}</td>
                    
                </tr>
            @endforeach
        </table>
    @endisset
</body>
</html>
