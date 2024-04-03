<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(){
        View::composer('layouts.partials.navbar', function ($view) {
            $idbagian = Auth::user()->idbagian;
            if ($idbagian) {
                $uraianbagian = DB::table('bagian')->where('id', '=', $idbagian)->value('uraianbagian');
            } else {
                $idbiro = Auth::user()->idbiro;
                $uraianbagian = DB::table('biro')->where('id', '=', $idbiro)->value('uraianbiro');
            }

            $iduser = Auth::user()->id;
            $roles = DB::table('role_users')
                ->join('role', 'role_users.idrole', '=', 'role.id')
                ->where('iduser', '=', $iduser)
                ->pluck('role.kewenangan');

            $view->with('uraianbagian', $uraianbagian)->with('datarole', $roles);
        });
    }

    public function register()
    {
        //
    }

}
