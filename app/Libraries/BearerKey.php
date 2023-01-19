<?php

namespace App\Libraries;


use Illuminate\Support\Facades\DB;


class BearerKey
{
    public function dapatkanbearerkey($tahunanggaran, $kodemodul){
        //cek apakah di bearerkey history ada
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
        return $bearerkey;
    }


    public function resetapi(String $tahunanggaran, String $kodemodul, String $tipedata){
        //dapatkan token awal
        $tokenawal = DB::table('tokenapi')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('modul','=',$kodemodul)
            ->value('token');

        $url =  "https://monsakti.kemenkeu.go.id/sitp-monsakti-omspan/webservice/resetToken/".$kodemodul."/".$tipedata."/KL002";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$tokenawal,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $hasilasli = json_decode($response);
        foreach ($hasilasli as $ITEM) {
            $tokenbaru = $ITEM->TOKEN;
        }

        $this->simpantokenbaru($tahunanggaran, $kodemodul, $tipedata);
        return $tokenbaru;
    }

    function simpantokenbaru($tahunanggaran, $kodemodul, $tokenbaru){
        //cek apakah sudah ada tokenhistorynya
        $data = DB::table('tokenapihistory')
            ->where('modul','=',$kodemodul)
            ->where('tahunanggaran','=',$tahunanggaran)
            ->count();
        if ($data == 1){
            DB::table('tokenapihistory')
                ->where('modul','=',$kodemodul)
                ->where('tahunanggaran','=',$tahunanggaran)
                ->update([
                    'token' => $tokenbaru
                ]);
        }else{
            DB::table('tokenapihistory')
                ->insert([
                    'tahunanggaran' => $tahunanggaran,
                    'modul' => $kodemodul,
                    'token' => $tokenbaru
                ]);
        }
    }
}
