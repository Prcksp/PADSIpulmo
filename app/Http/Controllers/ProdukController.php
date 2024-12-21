<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the produk.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pageTitle'] = 'Produk List';
        $data['produks'] = Produk::all();
        $data['fields'] = [
            'nama_produk' => 'Nama Produk',
            'deskripsi_produk' => 'Deskripsi Produk',
            'harga_produk' => 'Harga Produk',
            'biaya_poin' => 'Biaya Poin'
        ];
        return view('produks.index', $data);
    }

    /**
     * Show the form for creating a new produk.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['pageTitle'] = 'Tambah Data Produk';
        return view('produks.create', $data);
    }

    /**
     * Store a newly created produk in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_produk' => 'required|string|max:50|unique:Produk,nama_produk',
            'deskripsi_produk' => 'nullable|string|max:50',
            'harga_produk' => 'required|numeric',
            'biaya_poin' => 'required|numeric'
        ];

        $customMessages = [
            'nama_produk.unique' => 'Nama produk sudah ada dalam sistem!',
            'nama_produk.required' => 'Nama produk belum diisi!',
            'harga_produk.required' => 'Harga produk belum diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->after(function ($validator) use ($request) {
            // Validasi khusus: salah satu harus diisi, tidak boleh keduanya 0
            if ($request->harga_produk == 0 && $request->biaya_poin == 0) {
                $validator->errors()->add('harga_produk', 'Harga produk atau biaya poin harus diisi.');
                $validator->errors()->add('biaya_poin', 'Harga produk atau biaya poin harus diisi.');
            }
    
            // Validasi khusus: jika biaya_poin diisi, harga_produk harus 0
            if ($request->biaya_poin > 0 && $request->harga_produk > 0) {
                $validator->errors()->add('harga_produk', 'Jika biaya poin diisi, harga produk harus 0.');
            }
        });
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Produk::create($request->all());

        return redirect('/produks')->with('message', 'Data produk telah ditambahkan');
    }

    /**
     * Show the form for editing the specified produk.
     *
     * @param  string  $id_produk
     * @return \Illuminate\Http\Response
     */
    public function edit($id_produk)
    {
        $data['pageTitle'] = 'Ubah Data Produk';
        $data['produk'] = Produk::findOrFail($id_produk);
        return view('produks.edit', $data);
    }

    /**
     * Update the specified produk in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id_produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_produk)
    {
        $produk = Produk::findOrFail($id_produk);
        $rules = [
            'nama_produk' => 'required|string|max:50|unique:Produk,nama_produk,' . $id_produk . ',id_produk',
            'deskripsi_produk' => 'nullable|string|max:50',
            'harga_produk' => 'required|numeric',
        ];

        $customMessages = [
            'nama_produk.required' => 'Nama produk belum diisi!',
            'nama_produk.unique' => 'Nama produk sudah ada dalam sistem!',
            'harga_produk.required' => 'Harga produk belum diisi!',
            'biaya_poin.required' => 'Biaya poin belum diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->after(function ($validator) use ($request) {
            // Validasi khusus: salah satu harus diisi, tidak boleh keduanya 0
            if ($request->harga_produk == 0 && $request->biaya_poin == 0) {
                $validator->errors()->add('harga_produk', 'Harga produk atau biaya poin harus diisi.');
                $validator->errors()->add('biaya_poin', 'Harga produk atau biaya poin harus diisi.');
            }
    
            // Validasi khusus: jika biaya_poin diisi, harga_produk harus 0
            if ($request->biaya_poin > 0 && $request->harga_produk > 0) {
                $validator->errors()->add('harga_produk', 'Jika biaya poin diisi, harga produk harus 0.');
            }
        });
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $produk = Produk::findOrFail($id_produk);
        $produk->update($request->all());

        return redirect('/produks')->with('message', 'Data produk telah diubah');
    }

    /**
     * Remove the specified produk from storage.
     *
     * @param  string  $id_produk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_produk)
    {
        Produk::findOrFail($id_produk)->delete();
        return redirect('/produks')->with('message', 'Data produk telah dihapus');
    }
}
