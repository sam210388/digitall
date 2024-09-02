<?php

namespace App\Libraries\Card;

use App\Libraries\FilterDataUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Filter;

class CardSirangga
{
    public function dapatkancard(){
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();

        //card temuan hanya di admin BPK dan admin
        if (in_array(1,$role) OR in_array(13, $role)){
            $carddbr = $this->carddbradmin();
        }else if (in_array(14, $role)){
            $carddbr = $this->carddbrbagian();
        }else if (in_array(15, $role)){
            $carddbr = $this->carddbrbiro();
        }else{
            $carddbr = null;
        }

        return array(
            'carddbr' => $carddbr,
        );

    }

    public function carddbradmin(){
        //temuan
        $jumlahDBR = DB::table('dbrinduk')->count();
        $jumlahdbrfinal = DB::table('dbrinduk')
            ->where('statusdbr','=',3)
            ->count();
        $jumlahdbrunit = DB::table('dbrinduk')
            ->where('statusdbr','=',2)
            ->count();
        $jumlahdbrdraft = DB::table('dbrinduk')
            ->where('statusdbr','=',1)
            ->count();

        $carddbr = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Jumlah DBR</span>
                                <span class="info-box-number">
                                 Jumlah DBR Total: '.$jumlahDBR.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Draft: '.$jumlahdbrdraft.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Unit: '.$jumlahdbrunit.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Final: '.$jumlahdbrfinal.'
                                </span>
                            </div>
                        </div>
                        </div>';

        return $carddbr;
    }

    public function carddbrbagian(){
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();
        if (in_array(14,$role)) {
            $idbagian = Auth::user()->idbagian;

            $jumlahdbr = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbagian','=',$idbagian)
                ->count();
            $jumlahdbrdraft = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbagian','=',$idbagian)
                ->where('dbrinduk.statusdbr','=',1)
                ->count();
            $jumlahdbrunit = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbagian','=',$idbagian)
                ->where('dbrinduk.statusdbr','=',2)
                ->count();
            $jumlahdbrfinal = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbagian','=',$idbagian)
                ->where('dbrinduk.statusdbr','=',3)
                ->count();
            $carddbr = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Jumlah DBR</span>
                                <span class="info-box-number">
                                 Jumlah DBR Total: '.$jumlahdbr.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Draft: '.$jumlahdbrdraft.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Unit: '.$jumlahdbrunit.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Final: '.$jumlahdbrfinal.'
                                </span>
                            </div>
                        </div>
                        </div>';

            return $carddbr;
        }
    }

    public function carddbrbiro(){
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();
        if (in_array(15,$role)) {
            $idbiro = Auth::user()->idbiro;

            $jumlahdbr = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbiro','=',$idbiro)
                ->count();
            $jumlahdbrdraft = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbiro','=',$idbiro)
                ->where('dbrinduk.statusdbr','=',1)
                ->count();
            $jumlahdbrunit = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbiro','=',$idbiro)
                ->where('dbrinduk.statusdbr','=',2)
                ->count();
            $jumlahdbrfinal = DB::table('dbrinduk')
                ->leftJoin('ruangan','dbrinduk.idruangan','=','id')
                ->where('ruangan.idbiro','=',$idbiro)
                ->where('dbrinduk.statusdbr','=',3)
                ->count();

            $carddbr = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Jumlah DBR</span>
                                <span class="info-box-number">
                                 Jumlah DBR Total: '.$jumlahdbr.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Draft: '.$jumlahdbrdraft.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Unit: '.$jumlahdbrunit.'
                                </span>
                                <span class="info-box-number">
                                 Jumlah DBR Final: '.$jumlahdbrfinal.'
                                </span>
                            </div>
                        </div>
                        </div>';

            return $carddbr;

        }
    }
}
