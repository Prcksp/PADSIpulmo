<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <title>Laporan Penjualan</title>
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
            Laporan Penjualan
        </div>
        <div class="subtitle">
            Pulmo Coffee
        </div>
    </div>
    
    <p>Periode: {{ $start_date }} - {{ $end_date }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Rincian Produk</th>
                <th>Total Harga</th>
                <th>Biaya Poin</th>
            </tr>
        </thead>
        <tbody>
            @php
                 $totalPemasukan = 0;
                 $totalPoin = 0;
            @endphp
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->kode_transaksi_penjualan }}</td>
                    <td>{{ $transaction->tanggal_transaksi }}</td>
                    <td>{{ $transaction->nama_customer }}</td>
                    <td>{{ $transaction->rincian_produk }}</td>
                    <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($transaction->total_biaya_poin, 0, ',', '.') }}</td>
                </tr>
                @php 
                    $totalPemasukan += $transaction->total_harga;
                    $totalPoin += $transaction->total_biaya_poin;
                @endphp
                
            @endforeach
            <tr class="total-row">
                <td colspan="4">Total Pemasukan dan Penukaran Poin</td>
                <td>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td>{{ number_format($totalPoin, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
