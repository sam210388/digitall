<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiapAPIController extends Controller
{
    function userportal(){
        $judul = "List Userportal";
        return view('Administrasi.userportal',[
            "judul"=>$judul
        ]);
    }
    function importsiap(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://siap.dpr.go.id/api-rest/angestellter',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'geheimagent=s4mb3n3k3YP4ttY',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: PHPSESSID=ru0ive16k7a7lpo57kljts8dh5'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo json_encode($response);

        //return redirect()->to('userportal')->with('status','Import Datauser Dari SIAP Berhasil');

    }
}
