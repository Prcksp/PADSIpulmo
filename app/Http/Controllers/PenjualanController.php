<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Produk;
class PenjualanController extends Controller
{
    
    protected $table = 'detail_transaksi_penjualan';

    public function index()
    {
        $data['pageTitle'] = 'Penjualan';

        // Get customers and products
        $data['customers'] = Customer::select('id_customer', 'nama_customer')->get();
        $data['produks'] = Produk::select('id_produk', 'nama_produk', 'harga_produk')->get();

        // Get transactions with necessary joins
        $data['transactions'] = DB::table('detail_transaksi_penjualan')
            ->join('produk', 'detail_transaksi_penjualan.id_produk', '=', 'produk.id_produk')
            ->join('customer', 'detail_transaksi_penjualan.id_customer', '=', 'customer.id_customer')
            ->join('users', 'detail_transaksi_penjualan.id_pengguna', '=', 'users.id')  // Assuming users table is linked with 'id' column
            ->where('detail_transaksi_penjualan.status', 'belum_bayar') // Add this condition
            ->select(
                'detail_transaksi_penjualan.*',
                'detail_transaksi_penjualan.id_detail_transaksi_penjualan as id_detail_transaksi',
                'produk.nama_produk',
                'produk.harga_produk',
                'customer.nama_customer',
                'customer.email_customer',
                'customer.no_telepon_customer', 
                'users.name as user_name'
            )
            ->get();

        // Sum total harga using raw query
        $totalHarga = DB::table('detail_transaksi_penjualan')
            ->join('produk', 'detail_transaksi_penjualan.id_produk', '=', 'produk.id_produk')
            ->where('detail_transaksi_penjualan.status', 'belum_bayar') // Add this condition
            ->select(DB::raw('SUM(detail_transaksi_penjualan.kuantitas * produk.harga_produk) as total_harga'))
            ->first();

        // Add totalHarga to data array
        $data['totalHarga'] = $totalHarga->total_harga ?? 0;
        $data['kodeTransaksi'] = 'TRX' . now()->format('YmdHis'); // Format: TRXYYYYMMDDHHMMSS
        // Return view with the necessary data
        return view('penjualans.index', $data);
    }


    public function addCart(Request $request)
    {
        

        // Prepare the data for insertion
        $data = [// Generate a unique transaction code (example)
            'id_produk' => $request['product'],
            'id_customer' => $request['customer'],
            'id_pengguna' => session('user_id'), // Assuming the user is logged in
            'kuantitas' => $request['quantity'],
            'status' => 'belum_bayar', // Default status
        ];

        // Insert data into the database using a raw query
        DB::table('detail_transaksi_penjualan')->insert($data);

        // Redirect back to the penjualan page or another page as needed
        return redirect()->route('penjualans.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }


    public function destroy($id)
    {
        // Using raw SQL query to delete the data
        DB::delete('DELETE FROM ' . $this->table . ' WHERE id_detail_transaksi_penjualan = ?', [$id]);
        
        return redirect()->route('penjualans.index')->with('success', 'Data telah dihapus dari keranjang');
    }

   public function confirm(Request $request)
    {
        // Ambil data dari form
        $kodeTransaksi = $request->input('kode_transaksi'); // Kode transaksi dari view
        $totalHarga = $request->input('total_harga'); // Total harga dari view

        try {
            DB::beginTransaction();

            // Ambil data dari tabel detail_transaksi_penjualan berdasarkan kode transaksi
            $detailTransaksi = DB::table('detail_transaksi_penjualan')
                ->where('status', 'belum_bayar') // Add this condition
                ->first();

            if (!$detailTransaksi) {
                return redirect()->route('penjualans.index')->with('error', 'Detail transaksi tidak ditemukan.');
            }

            // Ambil id_customer dari detail transaksi
            $idCustomer = $detailTransaksi->id_customer;

            // Insert data ke tabel 'transaksi_penjualan'
            DB::table('transaksi_penjualan')->insert([
                'kode_transaksi_penjualan' => $kodeTransaksi,
                'id_pengguna' => session('user_id'), // Ambil ID pengguna dari session
                'id_customer' => $idCustomer,
                'total_harga' => preg_replace('/[^0-9]/', '', $totalHarga)
            ]);

             // Perbarui status 'detail_transaksi_penjualan' ke 'sudah_bayar'
            DB::table('detail_transaksi_penjualan')
                ->where('status', 'belum_bayar')
                ->update([
                    'kode_transaksi_penjualan' => $kodeTransaksi,
                    'status' => 'sudah_bayar',
                ]);

            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('penjualans.index')->with('success', 'Pesanan berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Redirect dengan pesan error
            return redirect()->route('penjualans.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



}
