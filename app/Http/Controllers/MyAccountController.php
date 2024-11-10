<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

class MyAccountController extends Controller
{
    public function show()
    {
        $userId = session('user_id');

        // Fetch user details using raw SQL query
        $user = DB::table('user') // Use 'user' if that's your table name
            ->where('id', $userId)
            ->first();

        $data['pageTitle'] = 'Akun Saya';
        $data['user'] = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone_number' => $user->phone_number
        ];

        return view('my_account', $data);
    }

    public function updateAccount(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:35',
            'email' => ['required', 'string', 'email', 'max:35', 'unique:user,email,' . $request->id],
            'phone_number' => ['required', 'string', 'max:35', 'unique:user,phone_number,' . $request->id],
        ];

        $customMessages = [
            'name.required' => 'Nama belum diisi!',
            'email.required' => 'Email belum diisi!',
            'email.unique' => 'Email telah digunakan!',
            'phone_number.required' => 'Nomor telepon belum diisi!',
            'phone_number.unique' => 'Nomor telepon telah digunakan!',
        ];

        $this->validate($request, $rules, $customMessages);

        // Update user details using raw SQL query
        DB::table('user') // Use 'user' if that's your table name
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number
            ]);

        return redirect('/my-account')->with('message', 'Data telah diperbaharui');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'new_password' => ['required', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'],
            'new_password_confirmation' => ['same:new_password'],
        ];

        $customMessages = [
            'new_password.required' => 'Kata sandi baru belum diisi!',
            'new_password.min' => 'Kata sandi minimal :min karakter!',
            'new_password.regex' => 'Kata sandi harus mengandung huruf kapital, huruf kecil, dan angka!',
            'new_password_confirmation.same' => 'Kata sandi tidak cocok!',
        ];

        $this->validate($request, $rules, $customMessages);

        // Update password using raw SQL query
        DB::table('user') // Use 'user' if that's your table name
            ->where('id', $request->id)
            ->update([
                'password' => Hash::make($request->new_password)
            ]);

        return redirect('/my-account')->with('message', 'Kata sandi telah diperbaharui');
    }
}
