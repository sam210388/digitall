<?php

namespace App\Http\Controllers\AdminAnggaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminAnggaran\BearerKeyModel;
use Illuminate\Support\Facades\DB;

class BearerKeyController extends Controller
{
    public function dapatkanbearerkey($kodemodul){
        $tahunanggaran = session('tahunanggaran');

        $bearerkeyhistory = DB::table('tokenapihistory')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('modul','=',$kodemodul)
            ->value('token');

        if ($bearerkeyhistory){
            $bearerkey = $bearerkeyhistory;
        }else{
            $bearerkey = DB::table('tokenapi')
                ->where('tahunanggaran','=',$tahunanggaran)
                ->where('modul','=',$kodemodul)
                ->value('token');
        }

        $bearerkey = $bearerkey->bearerkey;
        return $bearerkey;
    }
}
