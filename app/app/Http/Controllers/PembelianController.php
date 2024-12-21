<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    protected $table = 'transaksi_pembelian';


    public function index()
    {
        $data['pageTitle'] = 'Transaksi Pembelian';
    
        // Retrieve transactions with joins
        $data['transactions'] = DB::table('transaksi_pembelian')
            ->join('Barang', 'transaksi_pembelian.id_barang', '=', 'Barang.id_barang')
            ->join('users', 'transaksi_pembelian.id_pengguna', '=', 'users.id')
            ->select(
                'transaksi_pembelian.kode_transaksi_pembelian as kode_transaksi',
                'transaksi_pembelian.tanggal_transaksi as tanggal_transaksi',
                'users.name as nama_pengguna',
                'users.email as email_pengguna',
                'users.phone_number as no_telepon_pengguna',
                'transaksi_pembelian.jumlah',
                'Barang.nama_barang',
                'Barang.harga_barang',
                'transaksi_pembelian.total_harga',
                'users.name as user_name'
            )
            ->get();
        $data['barang'] = DB::table('Barang')->get(); // Retrieve all barang
        return view('pembelians.index', $data);
    }
    

    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'id_barang.*' => 'required|exists:Barang,id_barang',
            'jumlah.*' => 'required|integer|min:1',
        ];

        $customMessages = [
            'id_barang.*.required' => 'Barang belum dipilih!',
            'id_barang.*.exists' => 'Barang tidak valid!',
            'jumlah.*.required' => 'Jumlah barang belum diisi!',
            'jumlah.*.integer' => 'Jumlah barang harus berupa angka!',
            'jumlah.*.min' => 'Jumlah barang harus minimal 1!',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mulai proses penyimpanan
        DB::beginTransaction();
        try {
            // Generate kode transaksi
            $kodeTransaksi = 'TRXB'. now()->format('YmdHis');

            $totalHarga = 0;

            foreach ($request->id_barang as $index => $id_barang) {
                $jumlah = $request->jumlah[$index];

                // Ambil data barang berdasarkan ID
                $barang = DB::table('Barang')->where('id_barang', $id_barang)->first();
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$id_barang} tidak ditemukan.");
                }

                // Hitung total harga per barang
                $hargaTotalBarang = $barang->harga_barang * $jumlah;

                // Simpan data pembelian ke tabel transaksi_pembelian
                DB::table('transaksi_pembelian')->insert([
                    'kode_transaksi_pembelian' => $kodeTransaksi,
                    'id_pengguna' => session('user_id'),
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'total_harga' => $hargaTotalBarang
                ]);

                $totalHarga += $hargaTotalBarang;
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return redirect()->route('pembelians.index')->with('message', 'Pembelian berhasil disimpan! Total: Rp ' . number_format($totalHarga, 0, ',', '.'));
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pembelian: ' . $e->getMessage());
        }
    }



    
}
