@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-selection--single {
            height: calc(1.0em + .55rem + 1.5px); /* Match Bootstrap's input height */
            box-sizing: border-box;
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
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="section-body">
            <form method="POST" action="{{ route('penjualans.addCart') }}">
                @csrf
                <!-- First Row: Cards -->
                <div class="row">
                    <!-- Today's Sales -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Penjualan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Tanggal:</strong> {{ date('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Add Product -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tambah Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="product">Pilih Produk</label>
                                  <select id="product" name="product" class="form-control select2" required>
                                    <option value="" disabled selected>Cari dan pilih produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id_produk }}">
                                            {{ $produk->nama_produk }} - 
                                            @if ($produk->harga_produk == 0)
                                                {{ $produk->biaya_poin }} Poin
                                            @else
                                                Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                </div>

                                <div class="form-group">
                                    <label for="quantity">Jumlah</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Masukkan jumlah" required>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Price -->
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

            <!-- Second Row -->
            <div class="row">
                <!-- Cart Table -->
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
                                            <td>
                                                @if ($transaction->harga_produk == 0 || $transaction->biaya_poin > 1)
                                                    {{ number_format($transaction->biaya_poin, 0) }} Poin
                                                @else
                                                    Rp {{ number_format($transaction->harga_produk, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($transaction->harga_produk == 0 || $transaction->biaya_poin > 1)
                                                    {{ number_format($transaction->biaya_poin * $transaction->kuantitas, 0) }} Poin
                                                @else
                                                    Rp {{ number_format($transaction->harga_produk * $transaction->kuantitas, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('penjualans.destroy', $transaction->id_detail_transaksi) }}" method="POST">
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

               <!-- Payment Confirmation -->
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Konfirmasi Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('penjualans.confirm') }}" id="payment-form">
                                @csrf
                                <div class="form-group">
                                    <label for="kode_transaksi">Kode Transaksi</label>
                                    <input type="text" id="kode_transaksi" name="kode_transaksi" class="form-control" value="{{ $kodeTransaksi }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="customer">Pilih Nama Pelanggan</label>
                                    <select id="customer" name="customer" class="form-control select2" required>
                                        <option value="" disabled selected>Cari dan pilih pelanggan</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id_customer }}">{{ $customer->nama_customer }} - {{ $customer->jumlah_poin }} Poin</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="total_harga">Total Harga</label>
                                    <input type="text" id="total_harga" name="total_harga" class="form-control" value="{{ $totalHarga }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="customer_money">Uang Pelanggan</label>
                                    <input type="number" id="customer_money" name="customer_money" class="form-control" placeholder="Masukkan jumlah uang pelanggan" required>
                                </div>

                                <div class="form-group">
                                    <label for="change">Kembalian</label>
                                    <input type="text" id="change" name="change" class="form-control" readonly>
                                </div>

                                <div class="form-group text-left">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Konfirmasi Pesanan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="error-alert">
                    Uang pelanggan tidak mencukupi untuk membayar total harga!
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const form = document.getElementById("payment-form");
                        const totalHarga = parseFloat(document.getElementById("total_harga").value.replace(/[^\d.-]/g, ''));
                        const customerMoneyInput = document.getElementById("customer_money");
                        const changeInput = document.getElementById("change");
                        const errorAlert = document.getElementById("error-alert");

                        // Kalkulasi kembalian secara real-time
                        customerMoneyInput.addEventListener("input", function () {
                            const customerMoney = parseFloat(customerMoneyInput.value) || 0;
                            const change = customerMoney - totalHarga;

                            if (change >= 0) {
                                changeInput.value = "Rp " + change.toLocaleString();
                                errorAlert.classList.add("d-none"); // Sembunyikan alert jika valid
                            } else {
                                changeInput.value = "Rp 0";
                            }
                        });

                        // Validasi form sebelum submit
                        form.addEventListener("submit", function (e) {
                            const customerMoney = parseFloat(customerMoneyInput.value) || 0;
                            const change = totalHarga - customerMoney;
                            if (customerMoney < totalHarga) {
                                e.preventDefault(); // Cegah pengiriman form
                                errorAlert.textContent = "Uang pelanggan tidak mencukupi untuk membayar, masih kurang sebesar Rp. "+change;
                                errorAlert.classList.remove("d-none"); // Tampilkan alert
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();
        });
    </script>

    <!-- Select2 -->
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });

        // Change Calculation
        $('#customer_money').on('input', function () {
            let totalHarga = {{ $totalHarga }};
            let customerMoney = parseFloat($(this).val()) || 0;
            let change = customerMoney - totalHarga;
            $('#change').val('Rp ' + (change > 0 ? change.toLocaleString() : '0'));
        });
    </script>
@endsection
