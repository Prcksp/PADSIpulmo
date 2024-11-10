<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rpph extends Model
{
    use HasFactory;

    protected $table = 'rpph';

    // protected $fillable = [
    //     'asal_sekolah', 'nama_penyusun', 'tahun_pelajaran', 'jenjang_kelas', 'semester',
    //     'jumlah_siswa', 'alokasi_waktu', 'kbm', 'fase', 'kompetensi_awal', 'sarana_prasarana',
    //     'alat_dan_bahan', 'target_peserta_didik'
    // ];
    protected $fillable = [
        'hari_atau_tanggal', 'smt_atau_bln_minggu', 'topik', 'elemen_atau_sub_tema',
        'kelompok_atau_usia', 'tujuan_kegiatan', 'capaian_pembelajaran', 'alat_dan_bahan',
        'kegiatan', 'assemen_atau_penilaian', 'theme', 'guru'
    ];

}
