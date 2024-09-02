<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CekPemilikRincianIndikator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('idrincianindikatorro')){
            $idbagian = Auth::user()->idbagian;
            $idrincianindikatorro = $request->get('idrincianindikatorro');
            $bagian = DB::table('rincianindikatorro')
                ->where('id','=',$idrincianindikatorro)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rincian Indikator Ini Bukan Milik Bagian Anda');
            }
        }else if ($request->route('realisasirincianindikatorro')) {
            $idrealisasirincianindikatorro = $request->route('realisasirincianindikatorro');
            $idrincianindikatorro = DB::table('realisasirincianindikatorro')->where('id', '=', $idrealisasirincianindikatorro)->value('idrincianindikatorro');
            $idbagian = Auth::user()->idbagian;
            $bagian = DB::table('rincianindikatorro')
                ->where('id', '=', $idrincianindikatorro)
                ->value('idbagian');

            if ($idbagian == $bagian) {
                return $next($request);
            } else {
                abort(403, 'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }
    }
}
