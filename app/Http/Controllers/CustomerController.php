<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // Define the fields configuration
    protected $fields = [
        'nama_customer' => 'Nama',
        'alamat_customer' => 'Alamat',
        'no_telepon_customer' => 'Nomor Telepon',
        'tanggal_lahir_customer' => 'Tanggal Lahir',
        'email_customer' => 'Email',
        'jumlah_poin' => 'Jumlah Poin',
    ];

    public function index()
    {
        $data['pageTitle'] = 'Pelanggan';
        $data['customers'] = Customer::all();
        $data['fields'] = $this->fields; // Pass fields configuration to the view
        return view('customers.index', $data);
    }

    protected function getValidationRules()
    {
        return [
            'nama_customer' => 'required|string|max:50',
            'alamat_customer' => 'required|string|max:50',
            'no_telepon_customer' => 'required|string|max:50',
            'tanggal_lahir_customer' => 'required|date',
            'email_customer' => 'required|string|email|max:50',
            'jumlah_poin' => 'nullable|integer',
        ];
    }

    protected function getCustomMessages()
    {
        return [
            'nama_customer.required' => 'Nama pelanggan belum diisi!',
            'alamat_customer.required' => 'Alamat belum diisi!',
            'no_telepon_customer.required' => 'Nomor telepon belum diisi!',
            'tanggal_lahir_customer.required' => 'Tanggal lahir belum diisi!',
            'email_customer.required' => 'Email belum diisi!',
        ];
    }

    public function create()
    {
        $data['pageTitle'] = 'Tambah Data Pelanggan';
        $data['fields'] = $this->fields; // Pass fields configuration to the view
        return view('customers.create', $data);
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

        Customer::create($request->only(array_keys($rules)));

        return redirect('/customers')->with('message', 'Data pelanggan telah ditambahkan');
    }

    public function edit($id)
    {
        $data['pageTitle'] = 'Ubah Data Pelanggan';
        $data['customer'] = Customer::findOrFail($id);
        $data['fields'] = $this->fields;
        return view('customers.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = $this->getValidationRules();
        $customMessages = $this->getCustomMessages();

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            // Redirect back with errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(array_keys($rules)));

        return redirect('/customers')->with('message', 'Data pelanggan telah diubah');
    }

    public function destroy($id)
    {
        Customer::destroy($id);
        return redirect('/customers')->with('message', 'Data pelanggan telah dihapus');
    }
}
