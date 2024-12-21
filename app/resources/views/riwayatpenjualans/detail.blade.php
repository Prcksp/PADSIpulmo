@extends('layouts.app')

@section('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <style>
        .small-font-table {
            font-size: 0.8rem; /* Adjust table font size */
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Detail Transaksi: {{ $kode_transaksi }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Produk</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped text-center small-font-table" id="detailTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Kuantitas</th>
                                            <th>Harga</th>
                                            <th>Biaya Poin</th>
                                            <th>Status</th>
                                            <th>Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaction_details as $detail)
                                            <tr>
                                                <td>{{ $detail->nama_produk }}</td>
                                                <td>{{ $detail->kuantitas }}</td>
                                                <td>
                                                    {{ $detail->harga > 0 
                                                        ? 'Rp ' . number_format($detail->harga, 0, ',', '.') 
                                                        : '-' 
                                                    }}
                                                </td>
                                                <td>
                                                    {{ $detail->biaya_poin > 0 
                                                        ? $detail->biaya_poin . ' Poin' 
                                                        : '-' 
                                                    }}
                                                </td>
                                                <td>{{ ucfirst($detail->status) }}</td>
                                                <td>
                                                    {{ $detail->harga > 0 
                                                        ? 'Rp ' . number_format($detail->total_harga, 0, ',', '.') 
                                                        : $detail->biaya_poin * $detail->kuantitas . ' Poin' 
                                                    }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('riwayatpenjualans.index') }}" class="btn btn-secondary mt-3">Kembali</a>
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
        $(document).ready(function() {
            $('#detailTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
@endsection
