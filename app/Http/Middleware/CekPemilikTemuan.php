<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CekPemilikTemuan
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
        $idbagian = Auth::user()->idbagian;
        $idtemuan = $request->route('id');
        $bagian = DB::table('temuan')
            ->where('id','=',$idtemuan)
            ->value('idbagian');

        if ($idbagian == $bagian){
            return $next($request);
        }else{
            abort(403,'Temuan Ini Bukan Milik Bagian Anda');
        }

    }
}
