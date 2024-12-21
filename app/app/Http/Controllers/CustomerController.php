<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // Define the fields configuration
    protected $fields = [
        'nama_customer' => 'Nama',
        'alamat_customer' => 'Alamat',
        'no_telepon_customer' => 'Nomor Telepon',
        'tanggal_lahir_customer' => 'Tanggal Lahir',
        'email_customer' => 'Email',
        'jumlah_poin' => 'Poin'
    ];

    public function index()
    {
        $data['pageTitle'] = 'Pelanggan';
        $data['fields'] = $this->fields;

        // Format the Tanggal Lahir field for display
        $data['customers'] = Customer::all()->map(function ($customer) {
            $customer->tanggal_lahir_customer = date('Y-m-d', strtotime($customer->tanggal_lahir_customer)); // Ensure formatting
            return $customer;
        });
        
        return view('customers.index', $data);
    }

    protected function getValidationRules()
    {
        return [
            'nama_customer' => 'required|string|max:50|unique:customer,nama_customer',
            'nama_customer.unique' => 'Nama pelanggan sudah ada di sistem!',
            'alamat_customer' => 'required|string|max:50',
            'no_telepon_customer' => 'required|string|max:50|unique:customer,no_telepon_customer',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'email_customer' => 'required|string|email|max:50|unique:customer,email_customer',
        ];
    }

    protected function getCustomMessages()
    {
        return [
            'nama_customer.required' => 'Nama pelanggan belum diisi!',
            'nama_customer.unique' => 'Nama pelanggan sudah ada di sistem!',
            'alamat_customer.required' => 'Alamat belum diisi!',
            'no_telepon_customer.required' => 'Nomor telepon belum diisi!',
            'no_telepon_customer.unique' => 'Nomor telepon sudah ada di sistem!',
            'tanggal_lahir_customer.required' => 'Tanggal lahir belum diisi!',
            'email_customer.required' => 'Email belum diisi!',
            'email.unique' => 'Email sudah ada di sistem!',
        ];
    }
    protected function getValidationRulesUpdate()
    {
        return [
            'nama_customer' => 'required|string|max:50|unique:customer,nama_customer',
            'alamat_customer' => 'required|string|max:50',
            'no_telepon_customer' => 'required|string|max:50|unique:customer,no_telepon_customer',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'email_customer' => 'required|string|email|max:50|unique:customer,no_telepon_customer',
        ];
    }

    protected function getCustomMessagesUpdate()
    {
        return [
            'nama_customer.required' => 'Nama pelanggan belum diisi!',
            'nama_customer.unique' => 'Nama pelanggan sudah ada di sistem!',
            'alamat_customer.required' => 'Alamat belum diisi!',
            'no_telepon_customer.required' => 'Nomor telepon belum diisi!',
            'no_telepon_customer.unique' => 'Nomor telepon sudah ada di sistem!',
            'tanggal_lahir_customer.required' => 'Tanggal lahir belum diisi!',
            'email_customer.required' => 'Email belum diisi!',
            'email.unique' => 'Email sudah ada di sistem!',
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

        // Ambil data pelanggan
        $customer = Customer::findOrFail($id);

        // Format tanggal lahir ke Y-m-d (jika sudah berupa Carbon)
        if (!empty($customer->tanggal_lahir_customer)) {
            $customer->tanggal_lahir_customer = $customer->tanggal_lahir_customer instanceof \Carbon\Carbon
                ? $customer->tanggal_lahir_customer->format('Y-m-d')
                : $customer->tanggal_lahir_customer;
        }

        $data['customer'] = $customer;
        $data['fields'] = $this->fields;

        return view('customers.edit', $data);
    }

    

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Atur validasi agar mengabaikan nilai lama untuk nama_customer menggunakan id_customer
        $rules = [
            'nama_customer' => 'required|string|max:50|unique:customer,nama_customer,' . $id . ',id_customer',
            'alamat_customer' => 'required|string|max:50',
            'no_telepon_customer' => 'required|string|max:50|unique:customer,no_telepon_customer,' . $request->no_telepon_customer . ',no_telepon_customer',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'email_customer' => 'required|string|email|max:50|unique:customer,email_customer,' . $request->email_customer . ',email_customer',
        ];
        $customMessages = $this->getCustomMessagesUpdate();

        // Validasi input
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            // Redirect back with errors and old input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Hanya update data yang berubah
        $updatedData = [];
        foreach ($rules as $field => $rule) {
            if ($customer->{$field} !== $request->input($field)) {
                $updatedData[$field] = $request->input($field);
            }
        }

        if (!empty($updatedData)) {
            $customer->update($updatedData);
            return redirect('/customers')->with('message', 'Data pelanggan telah diubah');
        }

        return redirect('/customers')->with('message', 'Tidak ada perubahan pada data pelanggan');
    }


    public function destroy($id)
    {
        Customer::destroy($id);
        return redirect('/customers')->with('message', 'Data pelanggan telah dihapus');
    }
}
