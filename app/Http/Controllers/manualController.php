<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class manualController extends Controller
{
    public function viewManual(){
        return view('docs.manual');
    }
}
