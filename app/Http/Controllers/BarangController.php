<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the barang.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pageTitle'] = 'Barang List';
        $data['barangs'] = Barang::all();
        $data['fields'] = [
            'nama_barang' => 'Nama Barang',
            'deskripsi_barang' => 'Deskripsi Barang',
            'jumlah_barang' => 'Jumlah Barang',
            'harga_barang' => 'Harga Barang',
        ];
        return view('barangs.index', $data);
    }

    /**
     * Show the form for creating a new barang.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['pageTitle'] = 'Tambah Data Barang';
        return view('barangs.create', $data);
    }

    /**
     * Store a newly created barang in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_barang' => 'required|string|max:50|unique:barang,nama_barang', // Check for uniqueness
            'deskripsi_barang' => 'nullable|string|max:50',
            'jumlah_barang' => 'required|integer',
            'harga_barang' => 'required|numeric',
        ];

        $customMessages = [
            'nama_barang.required' => 'Nama barang belum diisi!',
            'nama_barang.unique' => 'Nama barang sudah ada dalam sistem!',
            'jumlah_barang.required' => 'Jumlah barang belum diisi!',
            'harga_barang.required' => 'Harga barang belum diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Barang::create($request->all());

        return redirect('/barangs')->with('message', 'Data barang telah ditambahkan');
    }

    /**
     * Show the form for editing the specified barang.
     *
     * @param  string  $id_barang
     * @return \Illuminate\Http\Response
     */
    public function edit($id_barang)
    {
        $data['pageTitle'] = 'Ubah Data Barang';
        $data['barang'] = Barang::findOrFail($id_barang);
        return view('barangs.edit', $data);
    }

    /**
     * Update the specified barang in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id_barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_barang)
    {
        $rules = [
            'nama_barang' => 'required|string|max:50',
            'deskripsi_barang' => 'nullable|string|max:50',
            'jumlah_barang' => 'required|integer',
            'harga_barang' => 'required|numeric',
        ];

        $customMessages = [
            'nama_barang.required' => 'Nama barang belum diisi!',
            'jumlah_barang.required' => 'Jumlah barang belum diisi!',
            'harga_barang.required' => 'Harga barang belum diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barang = Barang::findOrFail($id_barang);
        $barang->update($request->all());

        return redirect('/barangs')->with('message', 'Data barang telah diubah');
    }

    /**
     * Remove the specified barang from storage.
     *
     * @param  string  $id_barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_barang)
    {
        Barang::findOrFail($id_barang)->delete();
        return redirect('/barangs')->with('message', 'Data barang telah dihapus');
    }
}
