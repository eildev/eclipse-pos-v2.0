<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function view(){
        return view('pos.variants.variant');
    }
}
