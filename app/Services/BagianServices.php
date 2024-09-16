<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BagianServices
{
    public function getUraianBagian()
    {
        $idbagian = Auth::user()->idbagian;
        return DB::table('bagian')->where('id', '=', $idbagian)->value('uraianbagian');
    }
}
