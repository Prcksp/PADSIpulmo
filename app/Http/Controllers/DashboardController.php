<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data['pageTitle'] = 'Dashboard';

    
        return view('dashboard', $data);
    }
}
