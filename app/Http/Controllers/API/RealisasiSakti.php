<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RealisasiSakti extends Controller
{
    public function getStatusSpm(Request $request, $nospm){
        $token = $request->header('token');
        if ($token == "samwitwicky"){
            $no = substr($nospm,0,6);
            $kdsatker = substr($nospm,7,6);
            $tahunanggaran = substr($nospm,14,4);
            $where = array(
                'KDSATKER' => $kdsatker,
                'NO_SPP' => $no,
                'THN_ANG' => $tahunanggaran
            );

            $infotagihan = DB::table('sppheader')->where($where)->limit(1)->get(['NO_SP2D','TGL_SP2D']);

            header( "Content-type: application/json" );

            $data = array(
                "data" => $infotagihan
            );
            echo json_encode($data);
        }else{
            $data = array(
                "data" => "Token Invalid"
            );
            echo json_encode($data);
        }
    }
}
