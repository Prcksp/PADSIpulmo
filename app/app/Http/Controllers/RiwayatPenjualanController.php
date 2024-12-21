<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Produk;
class RiwayatPenjualanController extends Controller
{
    
    protected $table = 'detail_transaksi_penjualan';

    public function index()
    {
        $data['pageTitle'] = 'Penjualan';

        // Get transactions
        $data['transactions'] = DB::table('transaksi_penjualan')
            ->join('customer', 'transaksi_penjualan.id_customer', '=', 'customer.id_customer')
            ->join('users', 'transaksi_penjualan.id_pengguna', '=', 'users.id')
            ->select(
                'transaksi_penjualan.kode_transaksi_penjualan as kode_transaksi',
                'transaksi_penjualan.tanggal_transaksi',
                'transaksi_penjualan.total_harga',
                'customer.nama_customer',
                'customer.email_customer',
                'customer.no_telepon_customer', 
                'users.name as user_name'
            )
            ->get();

        // Get the details for each transaction
        // Get the details for each transaction, including harga and total_harga
        $data['transaction_details'] = DB::table('detail_transaksi_penjualan')
            ->join('Produk', 'detail_transaksi_penjualan.id_produk', '=', 'Produk.id_produk')
            ->select(
                'detail_transaksi_penjualan.kode_transaksi_penjualan',
                'detail_transaksi_penjualan.id_produk',
                'detail_transaksi_penjualan.kuantitas',
                'Produk.harga_produk as harga',
                'Produk.biaya_poin as biaya_poin',
                'detail_transaksi_penjualan.status',
                'Produk.nama_produk',
                DB::raw('Produk.harga_produk * detail_transaksi_penjualan.kuantitas as total_harga') // Calculate total_harga
            )
            ->get();


        // Return view with the necessary data
        return view('riwayatpenjualans.index', $data);
    }
    public function show($kode_transaksi)
    {
        $data['pageTitle'] = 'Detail Penjualan';
        // Fetch details for the specific transaction
        $transaction_details = DB::table('detail_transaksi_penjualan')
            ->join('Produk', 'detail_transaksi_penjualan.id_produk', '=', 'Produk.id_produk')
            ->select(
                'detail_transaksi_penjualan.kode_transaksi_penjualan',
                'detail_transaksi_penjualan.id_produk',
                'detail_transaksi_penjualan.kuantitas',
                'Produk.nama_produk',
                'Produk.harga_produk as harga',
                'Produk.biaya_poin as biaya_poin',
                'detail_transaksi_penjualan.status',
                DB::raw('
                    CASE
                        WHEN detail_transaksi_penjualan.status = "point" THEN Produk.biaya_poin * detail_transaksi_penjualan.kuantitas
                        ELSE Produk.harga_produk * detail_transaksi_penjualan.kuantitas
                    END as total_harga
                ')
            )
            ->where('detail_transaksi_penjualan.kode_transaksi_penjualan', $kode_transaksi)
            ->get();

        // Return to a detail view
        return view('riwayatpenjualans.detail', [
            'transaction_details' => $transaction_details,
            'kode_transaksi' => $kode_transaksi,
            'pageTitle' => 'Detail Penjualan'
        ]);
    }

}
