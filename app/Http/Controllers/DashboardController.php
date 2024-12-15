<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data['pageTitle'] = 'Dashboard';
    
        // Count total data for each section using raw queries
        $data['dataBarangCount'] = DB::select('SELECT COUNT(*) AS count FROM barang')[0]->count;
        $data['dataProdukCount'] = DB::select('SELECT COUNT(*) AS count FROM produk')[0]->count;
        $data['dataPelangganCount'] = DB::select('SELECT COUNT(*) AS count FROM customer')[0]->count;
        $data['dataPegawaiCount'] = DB::select('SELECT COUNT(*) AS count FROM users')[0]->count;
        $data['dataTransaksiCount'] = DB::select('SELECT COUNT(*) AS count FROM transaksi_penjualan')[0]->count;
    
        // Query to get monthly transactions
        $monthlyTransactions = DB::select('
            SELECT CONCAT(YEAR(CURRENT_DATE()), "-", LPAD(m.month, 2, "0")) AS month, IFNULL(SUM(t.total_harga), 0) AS total_harga
            FROM (SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS m
            LEFT JOIN transaksi_penjualan t ON MONTH(t.tanggal_transaksi) = m.month
            GROUP BY m.month
            ORDER BY m.month
        ');
    
        // Indonesian month names
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
    
        // Replace numeric months with Indonesian month names
        $data['monthlyTransactions'] = array_map(function ($item) use ($monthNames) {
            $item->month = $monthNames[substr($item->month, -2)];
            return $item;
        }, $monthlyTransactions);
    
        return view('dashboard', $data);
    }
    
    
}
