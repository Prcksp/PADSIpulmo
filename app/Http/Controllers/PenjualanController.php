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
        $data['customers'] = Customer::select('id_customer', 'nama_customer', 'jumlah_poin')->get();
        $data['produks'] = Produk::select('id_produk', 'nama_produk', 'harga_produk', 'biaya_poin')->get();

        // Get transactions with necessary joins
        $data['transactions'] = DB::table('detail_transaksi_penjualan')
            ->join('produk', 'detail_transaksi_penjualan.id_produk', '=', 'produk.id_produk')
            // ->join('customer', 'detail_transaksi_penjualan.id_customer', '=', 'customer.id_customer')
            ->join('users', 'detail_transaksi_penjualan.id_pengguna', '=', 'users.id')  // Assuming users table is linked with 'id' column
            ->where('detail_transaksi_penjualan.status', 'belum_bayar') // Add this condition
            ->select(
                'detail_transaksi_penjualan.*',
                'detail_transaksi_penjualan.id_detail_transaksi_penjualan as id_detail_transaksi',
                'produk.nama_produk',
                'produk.harga_produk',
                'produk.biaya_poin',
                // 'customer.nama_customer',
                // 'customer.email_customer',
                // 'customer.no_telepon_customer', 
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
        $data['kodeTransaksi'] = 'TRXJ' . now()->format('YmdHis'); // Format: TRXYYYYMMDDHHMMSS
        // Return view with the necessary data
        return view('penjualans.index', $data);
    }


    public function addCart(Request $request)
    {
        

        // Prepare the data for insertion
        $data = [// Generate a unique transaction code (example)
            'id_produk' => $request['product'],
            // 'id_customer' => $request['customer'],
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

            // Ambil data detail transaksi dengan status 'belum_bayar'
            $detailTransaksi = DB::table('detail_transaksi_penjualan')
                ->where('status', 'belum_bayar')
                ->get();

            if ($detailTransaksi->isEmpty()) {
                return redirect()->route('penjualans.index')->with('error', 'Detail transaksi tidak ditemukan.');
            }

            // Ambil id_customer dari detail transaksi
            $idCustomer = $request['customer'];

            // Ambil data pelanggan untuk pengecekan poin
            $customer = DB::table('customer')->where('id_customer', $idCustomer)->first();
            if (!$customer) {
                return redirect()->route('penjualans.index')->with('error', 'Pelanggan tidak ditemukan.');
            }

            $totalHargaFinal = 0; // Untuk menghitung total harga akhir setelah poin
            // Iterasi setiap item dalam detail transaksi
            foreach ($detailTransaksi as $item) {
                // Ambil data produk berdasarkan id_produk
                $produk = DB::table('produk')->where('id_produk', $item->id_produk)->first();

                if ($produk->biaya_poin > 0) { // Cek apakah produk memiliki poin yang lebih besar dari 0
                    $hargaBarang = $produk->biaya_poin * $item->kuantitas;
                    // Jika pelanggan memiliki cukup poin, gunakan poin untuk bayar produk ini
                    if ($customer->jumlah_poin >= $hargaBarang) {
                        $customer->jumlah_poin -= $hargaBarang; // Kurangi poin pelanggan
                        $hargaBarang = 0; // Harga barang dihapus karena dibayar dengan poin
                    } else {
                        return redirect()->route('penjualans.index')->with('error', 'Poin pelanggan tidak cukup');
                    }

                    // Update poin pelanggan di tabel
                    DB::table('customer')->where('id_customer', $idCustomer)->update(['jumlah_poin' => $customer->jumlah_poin]);
                } else {
                    $hargaBarang = $produk->harga_produk * $item->kuantitas; // Barang tidak menggunakan poin
                }

                $totalHargaFinal += $hargaBarang; // Tambahkan ke total harga akhir
            }

            // Insert data ke tabel 'transaksi_penjualan'
            DB::table('transaksi_penjualan')->insert([
                'kode_transaksi_penjualan' => $kodeTransaksi,
                'id_pengguna' => session('user_id'), // Ambil ID pengguna dari session
                'id_customer' => $idCustomer,
                'total_harga' => preg_replace('/[^0-9]/', '', $totalHargaFinal)
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

//    public function confirm(Request $request)
//     {
//         // Ambil data dari form
//         $kodeTransaksi = $request->input('kode_transaksi'); // Kode transaksi dari view
//         $totalHarga = $request->input('total_harga'); // Total harga dari view

//         try {
//             DB::beginTransaction();

//             // Ambil data dari tabel detail_transaksi_penjualan berdasarkan kode transaksi
//             $detailTransaksi = DB::table('detail_transaksi_penjualan')
//                 ->where('status', 'belum_bayar') // Add this condition
//                 ->first();

//             if (!$detailTransaksi) {
//                 return redirect()->route('penjualans.index')->with('error', 'Detail transaksi tidak ditemukan.');
//             }

//             // Ambil id_customer dari detail transaksi
//             $idCustomer = $request['customer'];

//             // Insert data ke tabel 'transaksi_penjualan'
//             DB::table('transaksi_penjualan')->insert([
//                 'kode_transaksi_penjualan' => $kodeTransaksi,
//                 'id_pengguna' => session('user_id'), // Ambil ID pengguna dari session
//                 'id_customer' => $idCustomer,
//                 'total_harga' => preg_replace('/[^0-9]/', '', $totalHarga)
//             ]);

//              // Perbarui status 'detail_transaksi_penjualan' ke 'sudah_bayar'
//             DB::table('detail_transaksi_penjualan')
//                 ->where('status', 'belum_bayar')
//                 ->update([
//                     'kode_transaksi_penjualan' => $kodeTransaksi,
//                     'status' => 'sudah_bayar',
//                 ]);

//             DB::commit();

//             // Redirect dengan pesan sukses
//             return redirect()->route('penjualans.index')->with('success', 'Pesanan berhasil dikonfirmasi!');
//         } catch (\Exception $e) {
//             DB::rollBack();

//             // Redirect dengan pesan error
//             return redirect()->route('penjualans.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
//         }
//     }



}
