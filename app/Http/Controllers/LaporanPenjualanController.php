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
            'pageTitle' => 'Laporan Transaksi Penjualan dan Pembelian',
        ]);
    }

    public function generateLaporan(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Fetch transactions with the updated query
        $transactions = DB::select("
            SELECT 
                tp.kode_transaksi_penjualan,
                tp.tanggal_transaksi,
                c.nama_customer,
                GROUP_CONCAT(
                    CONCAT(
                        p.nama_produk, 
                        ' (Harga: ', 
                        COALESCE(p.harga_produk, 0), 
                        ', Poin: ', 
                        COALESCE(p.biaya_poin, 0), 
                        ')'
                    ) SEPARATOR ', '
                ) AS rincian_produk,
                SUM(COALESCE(p.harga_produk, 0)) AS total_harga,
                SUM(COALESCE(p.biaya_poin, 0)) AS total_biaya_poin,
                CASE 
                    WHEN SUM(CASE WHEN p.biaya_poin > 0 THEN 1 ELSE 0 END) > 0 
                        AND SUM(CASE WHEN p.biaya_poin = 0 THEN 1 ELSE 0 END) > 0 
                    THEN 'Campuran (Harga dan Poin)'
                    WHEN SUM(CASE WHEN p.biaya_poin > 0 THEN 1 ELSE 0 END) = 0
                    THEN 'Hanya Harga'
                    ELSE 'Hanya Poin'
                END AS jenis_transaksi
            FROM 
                transaksi_penjualan tp
            LEFT JOIN 
                detail_transaksi_penjualan dtp ON tp.kode_transaksi_penjualan = dtp.kode_transaksi_penjualan
            LEFT JOIN 
                produk p ON dtp.id_produk = p.id_produk
            LEFT JOIN 
                customer c ON tp.id_customer = c.id_customer
            WHERE 
                tp.tanggal_transaksi BETWEEN ? AND ?
            GROUP BY 
                tp.kode_transaksi_penjualan, tp.tanggal_transaksi, c.nama_customer
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

        // Fetch transactions using the updated query
        $transactions = DB::select("
            SELECT 
                tp.kode_transaksi_penjualan,
                tp.tanggal_transaksi,
                c.nama_customer,
                GROUP_CONCAT(
                    CONCAT(
                        p.nama_produk, 
                        ' (Harga: ', 
                        COALESCE(p.harga_produk, 0), 
                        ', Poin: ', 
                        COALESCE(p.biaya_poin, 0), 
                        ')'
                    ) SEPARATOR ', '
                ) AS rincian_produk,
                SUM(COALESCE(p.harga_produk, 0)) AS total_harga,
                SUM(COALESCE(p.biaya_poin, 0)) AS total_biaya_poin,
                CASE 
                    WHEN SUM(CASE WHEN p.biaya_poin > 0 THEN 1 ELSE 0 END) > 0 
                        AND SUM(CASE WHEN p.biaya_poin = 0 THEN 1 ELSE 0 END) > 0 
                    THEN 'Campuran (Harga dan Poin)'
                    WHEN SUM(CASE WHEN p.biaya_poin > 0 THEN 1 ELSE 0 END) = 0
                    THEN 'Hanya Harga'
                    ELSE 'Hanya Poin'
                END AS jenis_transaksi
            FROM 
                transaksi_penjualan tp
            LEFT JOIN 
                detail_transaksi_penjualan dtp ON tp.kode_transaksi_penjualan = dtp.kode_transaksi_penjualan
            LEFT JOIN 
                produk p ON dtp.id_produk = p.id_produk
            LEFT JOIN 
                customer c ON tp.id_customer = c.id_customer
            WHERE 
                YEAR(tp.tanggal_transaksi) = ? AND MONTH(tp.tanggal_transaksi) = ?
            GROUP BY 
                tp.kode_transaksi_penjualan, tp.tanggal_transaksi, c.nama_customer
        ", [$validated['year'], $validated['month']]);

        // Generate PDF
        $pdf = Pdf::loadView('laporanpenjualans.pdf_monthly', [
            'transactions' => $transactions,
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);

        return $pdf->download('laporan_bulanan_' . now()->format('Ymd_His') . '.pdf');
    }

    public function generatePembelian(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Fetch transaksi pembelian based on the start and end date
        $transactions = DB::select("
            SELECT 
                tp.kode_transaksi_pembelian,
                tp.tanggal_transaksi,
                u.name as nama_pengguna,
                b.nama_barang,
                tp.jumlah,
                tp.total_harga
            FROM 
                transaksi_pembelian tp
            LEFT JOIN 
                users u ON tp.id_pengguna = u.id
            LEFT JOIN 
                barang b ON tp.id_barang = b.id_barang
            WHERE 
                tp.tanggal_transaksi BETWEEN ? AND ?
        ", [$validated['start_date'], $validated['end_date']]);

        // Generate PDF for the date range report
        $pdf = Pdf::loadView('laporanpembelians.pdf_date_range', [
            'transactions' => $transactions,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return $pdf->download('laporan_pembelian_' . now()->format('Ymd_His') . '.pdf');
    }

    public function generatePembelianMonthly(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);
    
        // Fetch transaksi pembelian based on the specified month and year
        $transactions = DB::select("
            SELECT 
                tp.kode_transaksi_pembelian,
                tp.tanggal_transaksi,
                u.name as nama_pengguna,
                b.nama_barang,
                tp.jumlah,
                tp.total_harga
            FROM 
                transaksi_pembelian tp
            LEFT JOIN 
                users u ON tp.id_pengguna = u.id
            LEFT JOIN 
                barang b ON tp.id_barang = b.id_barang
            WHERE 
                YEAR(tp.tanggal_transaksi) = ? AND MONTH(tp.tanggal_transaksi) = ?
        ", [$validated['year'], $validated['month']]);
    
        // Generate PDF for the monthly report
        $pdf = Pdf::loadView('laporanpembelians.pdf_monthly', [
            'transactions' => $transactions,
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);
    
        return $pdf->download('laporan_bulanan_pembelian_' . now()->format('Ymd_His') . '.pdf');
    }
    
}
