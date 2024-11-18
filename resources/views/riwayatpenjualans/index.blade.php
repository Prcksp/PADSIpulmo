@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <style>
        .small-font-table {
            font-size: 0.8rem; /* You can adjust this size */
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif
        @if (session('message'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Penjualan</h4>
                        
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                               <table class="table table-striped text-center small-font-table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Customer</th>
                                        <th>Email Customer</th>
                                        <th>No Telepon Customer</th>
                                        <th>Total Harga</th>
                                        <th>Kasir</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->kode_transaksi }}</td>
                                            <td>{{ $transaction->nama_customer }}</td>
                                            <td>{{ $transaction->email_customer }}</td>
                                            <td>{{ $transaction->no_telepon_customer }}</td>
                                            <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ $transaction->user_name }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm view-detail" data-transaction-id="{{ $transaction->kode_transaksi }}" data-toggle="modal" data-target="#transactionDetailModal">
                                                    Detail Transaksi
                                                </button>
                                            </td>
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

    
@endsection
 <!-- Modal for Detail Transaksi -->
        <div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionDetailModalLabel">Detail Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul id="transactionDetailList">
                            <!-- Details will be populated here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@section('script')
    <!-- JS Libraries -->
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Function to format numbers as Rupiah (with thousand separators)
            function formatRupiah(amount) {
                return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // When the 'View Details' button is clicked
            $('.view-detail').click(function() {
                // Get the transaction ID from the data attribute
                var transactionId = $(this).data('transaction-id');

                // Find the details for this transaction (use the transactionId)
                var details = [];
                @foreach ($transaction_details as $detail)
                    if ('{{ $detail->kode_transaksi_penjualan }}' === transactionId) {
                        details.push({
                            nama_produk: '{{ $detail->nama_produk }}',
                            kuantitas: '{{ $detail->kuantitas }}',
                            status: '{{ $detail->status }}',
                            harga: {{ $detail->harga }},
                            total_harga: {{ $detail->total_harga }}
                        });
                    }
                @endforeach

                // Clear existing modal content
                $('#transactionDetailList').empty();

                // Append the transaction details to the modal
                details.forEach(function(detail) {
                    var formattedHarga = formatRupiah(detail.harga);
                    var formattedTotalHarga = formatRupiah(detail.total_harga);
                    $('#transactionDetailList').append(
                        '<li>' + detail.nama_produk + ' - ' + detail.kuantitas + ' x Rp ' + formattedHarga + ' = Rp ' + formattedTotalHarga + '</li>'
                    );
                });
            });
        });
    </script>


@endsection
