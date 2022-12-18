<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AksesMenu
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
        $iduser = $request->user()->id;

        //dapatkan path url sekarang
        $urlroute = $request->path();
        $urlroute = explode('/',$urlroute,2);
        $urlroute = $urlroute[0];

        if ($urlroute == 'tampillistmenu'){
            return $next($request);
        }

        //cek kewenangan di submenu
        $adakewenangansubmenu = DB::table('submenu')
            ->select(['url_submenu'])
            ->join('menu','submenu.idmenu','=','menu.id','left')
            ->join('menu_kewenangan','menu_kewenangan.idmenu','=','menu.id','left')
            ->join('role_users','role_users.idrole','=','menu_kewenangan.idkewenangan','left')
            ->where('role_users.iduser','=',$iduser)
            ->where('submenu.url_submenu','=',$urlroute)
            ->count();

        //cek kewenangan di menu
        $adakewenanganmenu = DB::table('menu')
            ->select(['url_menu'])
            ->join('menu_kewenangan','menu.id','=','menu_kewenangan.idmenu','left')
            ->join('role_users','menu_kewenangan.idkewenangan','=','role_users.idrole')
            ->where('role_users.iduser','=',$iduser)
            ->where('menu.url_menu','=',$urlroute)
            ->count();


        if ($adakewenangansubmenu > 0 || $adakewenanganmenu > 0){
            return $next($request);
        }else{
            abort(403,'Anda Tidak Memiliki Kewenangan Pada Menu/Submenu Ini');
        }



    }
}
