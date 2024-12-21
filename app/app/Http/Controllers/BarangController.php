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
            'nama_barang' => 'required|string|max:50|unique:Barang,nama_barang', // Check for uniqueness
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
        // Find the barang by ID
        $barang = Barang::findOrFail($id_barang);
    
        // Define validation rules
        $rules = [
            'nama_barang' => 'required|string|max:50|unique:Barang,nama_barang,' . $id_barang . ',id_barang',
            'deskripsi_barang' => 'nullable|string|max:50',
            'jumlah_barang' => 'required|integer',
            'harga_barang' => 'required|numeric',
        ];
        
        // Define custom validation messages
        $customMessages = [
            'nama_barang.required' => 'Nama barang belum diisi!',
            'nama_barang.unique' => 'Nama barang sudah ada dalam sistem!',
            'jumlah_barang.required' => 'Jumlah barang belum diisi!',
            'harga_barang.required' => 'Harga barang belum diisi!',
        ];
    
        // Run the validation
        $validator = Validator::make($request->all(), $rules, $customMessages);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Prepare an array to store only the changed fields
        $updatedData = [];
        foreach ($rules as $field => $rule) {
            // Check if the field has changed before updating it
            if ($barang->{$field} !== $request->input($field)) {
                $updatedData[$field] = $request->input($field);
            }
        }
    
        // Update the fields if there are any changes
        if (!empty($updatedData)) {
            $barang->update($updatedData);
            return redirect('/barangs')->with('message', 'Data barang telah diubah');
        }
    
        // If no fields have changed, return a message
        return redirect('/barangs')->with('message', 'Tidak ada perubahan pada data barang');
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
