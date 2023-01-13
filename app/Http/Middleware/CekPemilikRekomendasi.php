<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CekPemilikRekomendasi
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
        if ($request->get('idrekomendasi')){
            $idbagian = Auth::user()->idbagian;
            $idrekomendasi = $request->get('idrekomendasi');
            $bagian = DB::table('rekomendasi')
                ->where('id','=',$idrekomendasi)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }else if ($request->route('kelolatindaklanjut')){
            $idtindaklanjut = $request->route('kelolatindaklanjut');
            $idrekomendasi = DB::table('tindaklanjutbpk')->where('id','=',$idtindaklanjut)->value('idrekomendasi');
            $idbagian = Auth::user()->idbagian;
            $bagian = DB::table('rekomendasi')
                ->where('id','=',$idrekomendasi)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }else if ($request->route('idrekomendasi')){
            $idrekomendasi = $request->route('idrekomendasi');
            $idbagian = Auth::user()->idbagian;
            $bagian = DB::table('rekomendasi')
                ->where('id','=',$idrekomendasi)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }else if ($request->route('idtindaklanjut')){
            $idtindaklanjut = $request->route('idtindaklanjut');
            $idrekomendasi =  DB::table('tindaklanjutbpk')
                ->where('id','=',$idtindaklanjut)
                ->value('idrekomendasi');
            $idbagian = Auth::user()->idbagian;
            $bagian = DB::table('rekomendasi')
                ->where('id','=',$idrekomendasi)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }else if ($request->get('idtindaklanjut')){
            $idtindaklanjut = $request->get('idtindaklanjut');
            $idrekomendasi =  DB::table('tindaklanjutbpk')
                ->where('id','=',$idtindaklanjut)
                ->value('idrekomendasi');
            $idbagian = Auth::user()->idbagian;
            $bagian = DB::table('rekomendasi')
                ->where('id','=',$idrekomendasi)
                ->value('idbagian');

            if ($idbagian == $bagian){
                return $next($request);
            }else{
                abort(403,'Rekomendasi Ini Bukan Milik Bagian Anda');
            }
        }


    }
}
