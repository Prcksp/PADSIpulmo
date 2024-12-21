@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">

    <!-- Custom CSS -->
    <style>
        .card-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
        }

        .card {
            width: 18%;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-icon {
            font-size: 40px;
            color: #007bff;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .card-total {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        .card-count {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Chart container */
        .chart-container {
            width: 100%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .card {
                width: 48%;  /* Two cards per row on medium screens */
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%;  /* One card per row on small screens */
            }
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="card-container">
        <!-- Data Barang -->
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-cogs"></i> <!-- Example icon, use any suitable icon -->
            </div>
            <div class="card-title">Data Barang</div>
            <div class="card-total">{{ $dataBarangCount }}</div>
            <div class="card-count">Total</div>
        </div>

        <!-- Data Produk -->
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-title">Data Produk</div>
            <div class="card-total">{{ $dataProdukCount }}</div>
            <div class="card-count">Total</div>
        </div>

        <!-- Data Pelanggan -->
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Data Pelanggan</div>
            <div class="card-total">{{ $dataPelangganCount }}</div>
            <div class="card-count">Total</div>
        </div>

        <!-- Data Pegawai -->
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="card-title">Data Pegawai</div>
            <div class="card-total">{{ $dataPegawaiCount }}</div>
            <div class="card-count">Total</div>
        </div>

        <!-- Data Transaksi -->
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="card-title">Data Transaksi</div>
            <div class="card-total">{{ $dataTransaksiCount }}</div>
            <div class="card-count">Total</div>
        </div>
    </div>

    <!-- Chart for Monthly Transactions -->
    <div class="chart-container">
        <canvas id="monthlyTransactionsChart"></canvas>
    </div>
</div>

@endsection

@section('script')
<!-- Include DataTables JS -->
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        // Data for the chart from the backend
        const monthlyTransactions = @json($monthlyTransactions);

        const months = monthlyTransactions.map(item => item.month);
        const totals = monthlyTransactions.map(item => item.total_harga);

        // Create the bar chart
        const ctx = document.getElementById('monthlyTransactionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',  // Set chart type to 'bar'
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Transaksi (Rp)',
                    data: totals,
                    backgroundColor: '#007bff', // Bar color
                    borderColor: '#0056b3', // Border color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total (Rp)'
                        },
                        ticks: {
                            beginAtZero: true,
                            max: 10000000,  // 10 juta
                            stepSize: 1000000
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
