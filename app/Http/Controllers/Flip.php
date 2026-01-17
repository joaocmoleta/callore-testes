<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Flip extends Controller
{
    public function index() {
        return(view('flip.index'));
    }
}
