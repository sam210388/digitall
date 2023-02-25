<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FilterDataUser
{
    public function kewenanganuser(){
        $iduser = Auth::id();
        $role = DB::table('role_users')->where('iduser','=',$iduser)
            ->pluck('idrole')
            ->toArray();;
        return $role;
    }

    public function filterdata(){
        $role = $this->kewenanganuser();
        $where = array();
        if (in_array(2,$role) OR in_array(14, $role)){
            $idbagian = Auth::user()->idbagian;
            if ($idbagian){
                $wheretambahan = array(
                    'idbagian' => $idbagian
                );
                $where = array_merge($where, $wheretambahan);
            }
        }elseif (in_array(6,$role) OR in_array(13, $role)){
            $idbiro = Auth::user()->idbiro;
            if ($idbiro){
                $wheretambahan = array(
                    'idbiro' => $idbiro);
                $where = array_merge($where, $wheretambahan);
            }
        }elseif(in_array(11,$role)){
            $iddeputi = Auth::user()->iddeputi;
            if ($iddeputi){
                $wheretambahan = array(
                    'iddeputi' => $iddeputi);
                $where = array_merge($where, $wheretambahan);
            }
        }
        return $where;
    }
}
