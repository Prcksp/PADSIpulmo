<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $table = 'user';

    // Define the fields configuration
    protected $fields = [
        'name' => 'Nama',
        'email' => 'Email',
        'role' => 'Role',
        'phone_number' => 'Nomor Telepon',
        'password' => 'Password', // Add password field
    ];

    public function index()
    {
        $data['pageTitle'] = 'Pengguna';
        $data['users'] = DB::table($this->table)
            ->select('user.*')
            ->where('role', 'barista')
            ->get();
        $data['fields'] = $this->fields; // Pass fields configuration to the view
        return view('users.index', $data);
    }

    protected function getValidationRules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'], // Optional for updates
            'role' => 'required',
            'phone_number' =>  ['required', 'string'],
        ];
    }

    protected function getRoleOptions()
    {
        return [
            // 'admin' => 'Admin',
            'guru' => 'Guru',
        ];
    }


    protected function getCustomMessages()
    {
        return [
            'name.required' => 'Nama belum diisi!',
            'email.required' => 'Email belum diisi!',
            'password.required' => 'Password belum diisi!',
            'role.required' => 'Role belum diisi!',
            'phone_number.required' => 'Nomor telepon belum diisi!',
            // Add custom messages for new fields here
        ];
    }

    public function create()
    {
        $data['pageTitle'] = 'Tambah Data Pengguna';
        $data['fields'] = $this->fields; // Pass fields configuration to the view
        $data['roleOptions'] = $this->getRoleOptions(); // Add role options
        return view('users.create', $data);
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
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table($this->table)->insert($data);

        return redirect('/users')->with('message', 'Data telah ditambahkan');
    }

    public function edit($id)
    {
        $data['pageTitle'] = 'Ubah Data Pengguna';
        $data['user'] = DB::table($this->table)->where('id', $id)->first();
        $data['fields'] = $this->fields; // Ensure this is an associative array as shown above
        $data['roleOptions'] = $this->getRoleOptions(); // Add role options
        return view('users.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $rules = $this->getValidationRules($id);
        $customMessages = $this->getCustomMessages();

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            // Redirect back with errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = $request->only(array_keys($rules));
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Perform the update
        $updated = DB::table($this->table)->where('id', $id)->update($data);

        // Check if the update was successful
        if ($updated === 0) {

            return redirect()->back()->with('error', 'Gagal mengubah data. Data tidak ditemukan atau tidak ada perubahan.');
        }

        return redirect('/users')->with('message', 'Data telah diubah');
    }



    public function destroy($id)
    {
        DB::table($this->table)->where('id', $id)->delete();
        return redirect('/users')->with('message', 'Data telah dihapus');
    }

    protected function getFields()
    {
        $columns = Schema::getColumnListing($this->table);
        $fields = [];

        foreach ($columns as $column) {
            if ($column === 'id') continue; // Skip primary key

            $fields[$column] = ucfirst(str_replace('_', ' ', $column));
        }

        return $fields;
    }
}
