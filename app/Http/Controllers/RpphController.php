<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Rpph;
use PDF;
class RpphController extends Controller
{
    protected $table = 'rpph';

    // Define the fields configuration
    protected $fields = [
        'nomor_rpph' => 'Nomor RPPH',
        'hari_atau_tanggal' => 'Hari atau Tanggal',
        'smt_atau_bln_minggu' => 'Smt atau Bulan Minggu',
        'topik' => 'Topik',
        'elemen_atau_sub_tema' => 'Elemen atau Sub Tema',
        'kelompok_atau_usia' => 'Kelompok atau Usia',
        'tujuan_kegiatan' => 'Tujuan Kegiatan',
        'capaian_pembelajaran' => 'Capaian Pembelajaran',
        'alat_dan_bahan' => 'Alat dan Bahan',
        'kegiatan' => 'Kegiatan',
        'assemen_atau_penilaian' => 'Assemen atau Penilaian',
        'guru' => 'ID Pendidik',
    ];

    public function getThemesBySemester($semester)
    {
        $themes = DB::table('theme')->where('semester', $semester)->get(['id', 'theme', 'semester']);
        return response()->json($themes);
    }


    public function index()
    {
        $data['pageTitle'] = 'RPPH';
        $data['rpph'] = DB::table($this->table)->get(); // Use query builder for simplicity
        $data['fields'] = $this->fields; // Pass fields configuration to the view
        return view('rpph.index', $data);
    }

    protected function getValidationRules()
    {
        return [
            'nomor_rpph' => 'required',
            'hari_atau_tanggal' => 'required',
            'smt_atau_bln_minggu' => 'required',
            'theme' => 'required',
            'topik' => 'required',
            'elemen_atau_sub_tema' => 'required',
            'kelompok_atau_usia' => 'required',
            'tujuan_kegiatan' => 'required',
            'capaian_pembelajaran' => 'required',
            'alat_dan_bahan' => 'required',
            'kegiatan' => 'required',
            'assemen_atau_penilaian' => 'required',
            'guru' => 'required',
        ];

    }

    protected function getValidationRulesUpdate()
    {
        return [
            'nomor_rpph' => 'required',
            'hari_atau_tanggal' => 'required',
            'smt_atau_bln_minggu' => 'required',
            'topik' => 'required',
            'elemen_atau_sub_tema' => 'required',
            'kelompok_atau_usia' => 'required',
            'tujuan_kegiatan' => 'required',
            'capaian_pembelajaran' => 'required',
            'alat_dan_bahan' => 'required',
            'kegiatan' => 'required',
            'assemen_atau_penilaian' => 'required',
            'guru' => 'required',
        ];

    }

    protected function getCustomMessages()
    {
        return [
            'nomor_rpph.required' => 'Nomor RPPH belum diisi!',
            'hari_atau_tanggal.required' => 'Hari atau tanggal belum diisi!',
            'smt_atau_bln_minggu.required' => 'Semester atau bulan/minggu belum diisi!',
            'semester.required' => 'Semester belum dipilih',
            'theme.required' => 'Tema belum dipilih',
            'topik.required' => 'Topik belum diisi!',
            'elemen_atau_sub_tema.required' => 'Elemen atau sub tema belum diisi!',
            'kelompok_atau_usia.required' => 'Kelompok atau usia belum diisi!',
            'tujuan_kegiatan.required' => 'Tujuan kegiatan belum diisi!',
            'capaian_pembelajaran.required' => 'Capaian pembelajaran belum diisi!',
            'alat_dan_bahan.required' => 'Alat dan bahan belum diisi!',
            'kegiatan.required' => 'Kegiatan belum diisi!',
            'assemen_atau_penilaian.required' => 'Assemen atau penilaian belum diisi!',
            'guru.required' => 'ID Pendidik belum diisi!',
        ];

    }

    protected function getCustomMessagesUpdate()
    {
        return [
            'nomor_rpph.required' => 'Nomor RPPH belum diisi!',
            'hari_atau_tanggal.required' => 'Hari atau tanggal belum diisi!',
            'smt_atau_bln_minggu.required' => 'Semester atau bulan/minggu belum diisi!',
            'topik.required' => 'Topik belum diisi!',
            'elemen_atau_sub_tema.required' => 'Elemen atau sub tema belum diisi!',
            'kelompok_atau_usia.required' => 'Kelompok atau usia belum diisi!',
            'tujuan_kegiatan.required' => 'Tujuan kegiatan belum diisi!',
            'capaian_pembelajaran.required' => 'Capaian pembelajaran belum diisi!',
            'alat_dan_bahan.required' => 'Alat dan bahan belum diisi!',
            'kegiatan.required' => 'Kegiatan belum diisi!',
            'assemen_atau_penilaian.required' => 'Assemen atau penilaian belum diisi!',
            'guru.required' => 'ID Pendidik belum diisi!',
        ];

    }


    public function create(Request $request)
    {
        $data['pageTitle'] = 'Tambah Data RPPH';
        $data['fields'] = $this->fields;

        // Fetch the selected semester if any
        $selectedSemester = $request->input('semester');

        // Fetch themes based on the selected semester
        if ($selectedSemester) {
            $data['themes'] = DB::table('theme')->where('semester', $selectedSemester)->get(['id', 'theme', 'semester']);
        } else {
            $data['themes'] = [];
        }

        // Pass the selected semester back to the view
        $data['selectedSemester'] = $selectedSemester;

        return view('rpph.create', $data);
    }


    public function store(Request $request)
    {
        $rules = $this->getValidationRules();
        $customMessages = $this->getCustomMessages();

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            // Redirect back with errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(array_keys($rules));

        DB::table($this->table)->insert($data);

        return redirect('/rpph')->with('message', 'Data telah ditambahkan');
    }

    public function edit($id)
    {
        $data['pageTitle'] = 'Ubah Data RPPH';
        $data['rpph'] = DB::table($this->table)->where('id', $id)->first();
        $data['fields'] = $this->fields; // Ensure this is an associative array as shown above
        return view('rpph.edit', $data);
    }

    public function cetak($id)
    {
        $rpph = Rpph::findOrFail($id);
        $pdf = PDF::loadView('rpph.cetak', compact('rpph'));
        return $pdf->stream('RPPH-' . $rpph->id . '.pdf');
    }

    public function update(Request $request, $id)
    {
        // Define the validation rules, excluding 'theme' and 'semester'
        $rules = $this->getValidationRulesUpdate();

        $customMessages = $this->getCustomMessagesUpdate();

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            // Redirect back with errors and old input

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve only the validated fields, excluding 'theme' and 'semester'
        $data = $request->only(array_keys($rules));

        // Perform the update
        $updated = DB::table($this->table)->where('id', $id)->update($data);

        // Check if the update was successful
        if ($updated === 0) {

            return redirect()->back()->with('error', 'Gagal mengubah data. Data tidak ditemukan atau tidak ada perubahan.');
        }

        return redirect('/rpph')->with('message', 'Data telah diubah');
    }


    public function destroy($id)
    {
        DB::table($this->table)->where('id', $id)->delete();
        return redirect('/rpph')->with('message', 'Data telah dihapus');
    }
}
