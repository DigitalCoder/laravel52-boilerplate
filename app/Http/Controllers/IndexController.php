<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use DB;
use Illuminate\Http\Request;
use Session;

class IndexController extends Controller
{
	public function getIndex()
	{
		$values = [
            'title' => "Hello World",
        ];
		return view("index", $values);
	}
}
