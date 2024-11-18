@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <style>
        /* Ensure Select2 matches the parent width */
        .select2-container {
            width: 100% !important;
        }

        .select2-selection--single {
            height: calc(1.0em + .55rem + 1.5px); /* Match Bootstrap's input height */
            box-sizing: border-box; /* Prevent overflow */
        }

    </style>
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

        <div class="section-body">
            <form method="POST" action="{{ route('penjualans.addCart') }}">
                @csrf
                <!-- First Row: Cards -->
                <div class="row">
                    <!-- Card 1: Customer Selection -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Penjualan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                <!-- Display Today's Date -->
                                <p><strong>Tanggal:</strong> {{ date('Y-m-d') }}</p>

                                <!-- Dropdown for Customer Name -->
                                <div class="form-group">
                                    <label for="customer">Pilih Nama Pelanggan</label>
                                    <select id="customer" name="customer" class="form-control select2" required>
                                        <option value="" disabled selected>Cari dan pilih pelanggan</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id_customer }}">{{ $customer->nama_customer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Product Selection -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tambah Produk</h4>
                            </div>
                            <div class="card-body">
                                <!-- Dropdown for Product Selection -->
                                <div class="form-group">
                                    <label for="product">Pilih Produk</label>
                                    <select id="product" name="product" class="form-control select2" required>
                                        <option value="" disabled selected>Cari dan pilih produk</option>
                                        @foreach ($produks as $produk)
                                          <option value="{{ $produk->id_produk }}">
                                            {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                                         </option>

                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input for Quantity -->
                                <div class="form-group">
                                    <label for="quantity">Jumlah</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Masukkan jumlah" required>
                                </div>

                                <!-- Button to Add to Cart -->
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Summary of Total Harga -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Total Harga</h4>
                            </div>
                            <div class="card-body">
                                <h2><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></h2>
                            </div>
                        </div>
                    </div>


                </div>
            </form>

         <!-- Second Row: Table and Payment Confirmation -->
        <div class="row">
            <!-- First Column (Table) -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Data Keranjang</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center" id="datatable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Pesanan</th>
                                        <th>Harga</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $index => $transaction)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transaction->nama_produk }} - x{{ $transaction->kuantitas }}</td>
                                            <td>Rp {{ number_format($transaction->harga_produk, 2) }}</td>
                                            <td>Rp {{ number_format($transaction->kuantitas * $transaction->harga_produk, 2) }}</td> <!-- Total Harga Column -->
                                            <td>
                                                <form action="{{ route('penjualans.destroy', ['penjualan' => $transaction->id_detail_transaksi]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Column (Payment Confirmation) -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Konfirmasi Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('penjualans.confirm') }}">
                            @csrf
                            <!-- Transaction Code -->
                            <div class="form-group">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" id="kode_transaksi" name="kode_transaksi" class="form-control" value="{{ $kodeTransaksi }}" readonly>
                            </div>
                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <input type="text" id="total_harga" name="total_harga" class="form-control" value="Rp {{ number_format($totalHarga, 0, ',', '.') }}" readonly>
                            </div>

                            <!-- Customer Money -->
                            <div class="form-group">
                                <label for="customer_money">Uang Pelanggan</label>
                                <input type="number" id="customer_money" name="customer_money" class="form-control" placeholder="Masukkan jumlah uang pelanggan" required>
                            </div>

                            <!-- Change Calculation -->
                            <div class="form-group">
                                <label for="change">Kembalian</label>
                                <input type="text" id="change" name="change" class="form-control" readonly>
                            </div>

                            <!-- Button to Confirm Order -->
                            <div class="form-group text-left">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Konfirmasi Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>
@endsection

@section('script')
    <!-- JS Libraries -->
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();
        });
    </script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for the customer dropdown
            $('#customer_name').select2({
                placeholder: "Cari dan pilih pelanggan",
                allowClear: true
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $('#customer_name').select2({
            width: 'resolve' // Use the parent's width
        });
    });

    </script>
    <script>
        $(document).ready(function () {
            // Listen for input on the customer money field
            $('#customer_money').on('input', function () {
                // Get the total price and customer money values
                var totalHarga = {{ $totalHarga }};  // Pass PHP value to JavaScript
                var customerMoney = parseFloat($(this).val()) || 0;

                // Calculate the change
                var change = customerMoney - totalHarga;

                // Display the change or show 0 if the customer money is less than the total price
                if (change >= 0) {
                    $('#change').val('Rp ' + change.toLocaleString());
                } else {
                    $('#change').val('Rp 0');
                }
            });
        });
    </script>

@endsection
