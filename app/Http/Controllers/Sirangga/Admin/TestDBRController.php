<?php

namespace App\Http\Controllers\Sirangga\Admin;

use App\Http\Controllers\Controller;

class TestDBRController extends Controller
{
    public function testdbr(){
        return view('sirangga.test.dbrruangan');
    }
}

