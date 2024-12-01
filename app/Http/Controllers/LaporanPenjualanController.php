<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('laporanpenjualans.index', [
            'pageTitle' => 'Laporan Penjualan',
        ]);
    }

    public function generateLaporan(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        // Fetch transactions with customer name, total_harga, and biaya_poin
        $transactions = DB::select("
            SELECT 
                tp.kode_transaksi_penjualan, 
                tp.tanggal_transaksi, 
                tp.total_harga, 
                c.nama_customer,
                -- Jika harga = 0, ambil poin yang digunakan dalam detail transaksi
                CASE 
                    WHEN tp.total_harga = 0 THEN SUM(dt.kuantitas * p.biaya_poin) -- biaya poin dihitung dari kuantitas dan biaya_poin per produk
                    ELSE 0
                END AS biaya_poin_from_detail,
                -- Jika ada harga, tampilkan harga transaksi
                CASE 
                    WHEN tp.total_harga > 0 THEN tp.total_harga
                    ELSE 0
                END AS total_harga_final
            FROM transaksi_penjualan tp
            LEFT JOIN customer c ON tp.id_customer = c.id_customer
            LEFT JOIN detail_transaksi_penjualan dt ON tp.kode_transaksi_penjualan = dt.kode_transaksi_penjualan
            LEFT JOIN produk p ON dt.id_produk = p.id_produk
            WHERE tp.tanggal_transaksi BETWEEN ? AND ?
            GROUP BY tp.kode_transaksi_penjualan, tp.tanggal_transaksi, tp.total_harga, c.nama_customer
        ", [$validated['start_date'], $validated['end_date']]);
    
        // Generate PDF
        $pdf = Pdf::loadView('laporanpenjualans.pdf_date_range', [
            'transactions' => $transactions,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);
    
        return $pdf->download('laporan_penjualan_' . now()->format('Ymd_His') . '.pdf');
    }
    
    
    

    public function generateLaporanBulanan(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        // Fetch monthly transactions
        $transactions = DB::table('transaksi_penjualan')
            ->whereYear('tanggal_transaksi', $validated['year'])
            ->whereMonth('tanggal_transaksi', $validated['month'])
            ->get();

        $pdf = Pdf::loadView('laporanpenjualans.pdf_monthly', [
            'transactions' => $transactions,
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);

        return $pdf->download('laporan_bulanan_' . now()->format('Ymd_His') . '.pdf');
    }
}
