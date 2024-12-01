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
            width: 80px; /* Ukuran logo */
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

        <!-- Judul Tengah -->
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
                <th>Total Harga</th>
                <th>Biaya Poin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->kode_transaksi_penjualan }}</td>
                    <td>{{ $transaction->tanggal_transaksi }}</td>
                    <td>{{ $transaction->nama_customer }}</td>
                    <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($transaction->biaya_poin_from_detail)
                            Rp {{ number_format($transaction->biaya_poin_from_detail, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
