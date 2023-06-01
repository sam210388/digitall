<?php

namespace App\Http\Controllers;

use App\Libraries\Card\CardAnggaranRealisasii;
use App\Libraries\Card\CardTemuanRekomendasi;
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
        //card BPK
        $cardtemuanrekomendasi = new CardTemuanRekomendasi();
        $cardtemuanrekomendasi = $cardtemuanrekomendasi->dapatkancard();

        //card LRA
        $cardlra = new CardAnggaranRealisasii();
        $cardlra = $cardlra->dapatkancard();
        $card = array_merge($cardlra, $cardtemuanrekomendasi);

        return view('home',[
            'data' => $card
        ]);


    }
}
