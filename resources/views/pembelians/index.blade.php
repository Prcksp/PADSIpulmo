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
    <!-- Include necessary CSS libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <style>
        .small-font-table {
            font-size: 0.8rem; /* Adjust size as needed */
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addPurchaseModal">Tambah Pembelian</button>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Pembelian</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped text-center small-font-table" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>Kode Transaksi</th>
                                            <th>Barang</th>
                                            <th>Harga/Pcs</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal Transaksi</th>
                                            <th>Nama Pengguna</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->kode_transaksi }}</td>
                                                <td>x{{$transaction->jumlah}} - {{ $transaction->nama_barang }}</td>
                                                <td>Rp {{ number_format($transaction->harga_barang, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                                <td>{{ $transaction->tanggal_transaksi }}</td>
                                                <td>{{ $transaction->nama_pengguna }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Tambah Pembelian -->
    <div class="modal fade" id="addPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="addPurchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('pembelians.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPurchaseModalLabel">Tambah Pembelian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="form-group">
                            <label for="id_pengguna">Nama Pengguna</label>
                            <input type="text" class="form-control" id="id_pengguna" name="id_pengguna" placeholder="Masukkan Nama Pengguna" required>
                        </div> --}}
                        <div id="purchase-items">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="id_barang[]">Barang</label>
                                    <select class="form-control select2" name="id_barang[]" required>
                                        <option value="" disabled selected>Cari dan pilih barang</option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->id_barang }}">{{ $item->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="jumlah[]">Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-item">&times;</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" id="add-item">Tambah Barang</button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
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
    <!-- Include necessary JS libraries -->
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
       $(document).ready(function() {
            $('#datatable').DataTable();

            // Initialize Select2 for existing dropdowns
            $('.select2').select2();

            // Add new item row
            $('#add-item').click(function() {
                const itemRow = `
                <div class="form-group row">
                    <div class="col-md-5">
                        <select class="form-control select2" name="id_barang[]" required>
                            <option value="" disabled selected>Cari dan pilih barang</option>
                            @foreach ($barang as $item)
                                <option value="{{ $item->id_barang }}">{{ $item->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-block remove-item">&times;</button>
                    </div>
                </div>`;
                $('#purchase-items').append(itemRow);

                // Re-initialize Select2 for new dropdown
                $('#purchase-items .select2').last().select2();
            });

            // Remove item row
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.form-group.row').remove();
            });
        });

    </script>
@endsection
