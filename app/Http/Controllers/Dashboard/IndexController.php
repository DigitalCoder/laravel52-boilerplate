<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
