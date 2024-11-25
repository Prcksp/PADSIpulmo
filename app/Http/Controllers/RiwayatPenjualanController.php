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
            ->join('produk', 'detail_transaksi_penjualan.id_produk', '=', 'produk.id_produk')
            ->select(
                'detail_transaksi_penjualan.kode_transaksi_penjualan',
                'detail_transaksi_penjualan.id_produk',
                'detail_transaksi_penjualan.kuantitas',
                'produk.harga_produk as harga',
                'produk.biaya_poin as biaya_poin',
                'detail_transaksi_penjualan.status',
                'produk.nama_produk',
                DB::raw('produk.harga_produk * detail_transaksi_penjualan.kuantitas as total_harga') // Calculate total_harga
            )
            ->get();


        // Return view with the necessary data
        return view('riwayatpenjualans.index', $data);
    }

}
