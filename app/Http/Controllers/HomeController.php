<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tahunanggaran = session('tahunanggaran');
        $iduser = Auth::id();
        $role = DB::table('role_users')->where('iduser','=',$iduser)->pluck('idrole')->toArray();

        //temuan
        $jumlahtemuan = DB::table('temuan')->count();
        $jumlahtemuanselesai = DB::table('temuan')
            ->where('status','=',6)
            ->orWhere('status','=',7)
            ->count();
        if ($jumlahtemuanselesai == 0){
            $prosentasetemuanselesai = "0%";
        }else{
            $prosentasetemuanselesai = ($jumlahtemuanselesai/$jumlahtemuan)*100;
            $prosentasetemuanselesai = $prosentasetemuanselesai."%";
        }

        //rekomendasi
        $jumlahrekomendasi = DB::table('rekomendasi')->count();
        $jumlahrekomendasiselesai = DB::table('rekomendasi')
            ->where('status','=',6)
            ->orWhere('status','=',7)
            ->count();
        if ($jumlahrekomendasiselesai == 0){
            $prosentaserekomendasiselesai = "0%";
        }else{
            $prosentaserekomendasiselesai = ($jumlahrekomendasiselesai/$jumlahrekomendasi)*100;
            $prosentaserekomendasiselesai = $prosentaserekomendasiselesai."%";
        }



        $card = "";
        $cardtemuan = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Temuan</span>
                                <span class="info-box-number">
                                 Jumlah Temuan: '.$jumlahrekomendasi.'
                                </span>
                                <span class="info-box-number">
                                 '.$prosentasetemuanselesai.' Selesai
                                </span>
                            </div>
                        </div>
                        </div>';
        $cardrekomendasi = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Rekomendasi</span>
                                <span class="info-box-number">
                                 Jumlah Rekomendasi: '.$jumlahrekomendasi.'
                                </span>
                                <span class="info-box-number">
                                 '.$prosentaserekomendasiselesai.' Selesai
                                </span>
                            </div>
                        </div>
                        </div>';

        if (in_array(1,$role) OR in_array(3,$role)){
            $card = $card.$cardtemuan;
            $card = $card.$cardrekomendasi;
        }else if (in_array(2,$role)){
            $card = $card.$cardrekomendasi;
        }

        return view('home',[
            "card" => $card
        ]);
    }
}
