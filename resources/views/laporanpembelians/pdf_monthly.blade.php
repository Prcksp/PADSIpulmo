<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <title>Laporan Pembelian Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            flex-grow: 1;
        }
        .subtitle {
            font-size: 18px;
            margin-left: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">
            Laporan Pembelian Bulanan
        </div>
        <div class="subtitle">
            Pulmo Coffee
        </div>
    </div>
    
    <p>Periode: {{ $month }} / {{ $year }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pengguna</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                 $totalPengeluaran = 0;
            @endphp
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->kode_transaksi_pembelian }}</td>
                    <td>{{ $transaction->tanggal_transaksi }}</td>
                    <td>{{ $transaction->nama_pengguna }}</td>
                    <td>{{ $transaction->nama_barang }}</td>
                    <td>{{ $transaction->jumlah }}</td>
                    <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                </tr>
                @php 
                    $totalPengeluaran += $transaction->total_harga;
                @endphp
                
            @endforeach
            <tr class="total-row">
                <td 
                    colspan="5">Total Pengeluaran                   
                </td>
                <td>
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
        </tbody>
    </table>
</body>
</html>
