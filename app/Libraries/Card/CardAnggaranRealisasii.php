<?php

namespace App\Libraries\Card;

use App\Libraries\FilterDataUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Filter;

class CardAnggaranRealisasii
{
    public function dapatkancard(){
        $cardlra = $this->cardanggaran();
        return array(
            'cardlra' => $cardlra,
        );

    }

    public function cardanggaran(){
        $tahunanggaran = session('tahunanggaran');
        //anggaran
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();
        $anggaransetjen = DB::table('laporanrealisasianggaranbac')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=','001012');

        $anggarandewan = DB::table('laporanrealisasianggaranbac')
            ->where('tahunanggaran','=',$tahunanggaran)
            ->where('kodesatker','=','001030');

        if (in_array(1,$role) OR in_array(9, $role) OR in_array(10, $role)){
            $sumanggaransetjen = $anggaransetjen->sum('paguanggaran');
            $sumanggarandewan = $anggarandewan->sum('paguanggaran');
            $sumrealisasisetjen = $anggaransetjen->sum('rsd12');
            if ($sumanggaransetjen > 0){
                $prosentasesetjen = ($sumrealisasisetjen/$sumanggaransetjen)*100;
            }else{
                $prosentasesetjen = 0;
            }
            //$prosentasesetjen = ($sumrealisasisetjen/$sumanggaransetjen)*100;
            $sumrealisasidewan = $anggarandewan->sum('rsd12');
            if ($sumanggarandewan > 0){
                $prosentasedewan = ($sumrealisasidewan/$sumanggarandewan)*100;
            }else{
                $prosentasedewan = 0;
            }

            $cardanggaranrealisasi = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Anggaran</span>
                                <span class="info-box-number">
                                 Anggaran Setjen: '. number_format($sumanggaransetjen,0,",",".").'
                                </span>
                                <span class="info-box-number">
                                 Realisasi Setjen: '.number_format($sumrealisasisetjen,0,",",".").'
                                </span>
                                <span class="info-box-number">
                                 Prosentase Setjen: '.number_format($prosentasesetjen,2,",",".").'
                                </span>

                                <span class="info-box-number">
                                 Anggaran Dewan: '.number_format($sumanggarandewan,0,",",".").'
                                </span>
                                 <span class="info-box-number">
                                 Realisasi Dewan: '.number_format($sumrealisasidewan,0,",","."). '
                                </span>
                                <span class="info-box-number">
                                 Prosentase Dewan: '.number_format($prosentasedewan,2,",",".").'
                                </span>


                            </div>
                        </div>
                        </div>';
            return $cardanggaranrealisasi;



        }else if(in_array(2,$role) OR in_array(6, $role)){
            $where = new FilterDataUser();
            $where = $where->filterdata();
            $sumanggaransetjen = $anggaransetjen->where($where)
                ->sum('paguanggaran');
            $sumanggarandewan = $anggarandewan->where($where)
                ->sum('paguanggaran');
            $sumrealisasisetjen = $anggaransetjen->where($where)
                ->sum('rsd12');
            if ($sumanggaransetjen > 0){
                $prosentasesetjen = ($sumrealisasisetjen/$sumanggaransetjen)*100;
            }else{
                $prosentasesetjen = 0;
            }
            $sumrealisasidewan = $anggarandewan->where($where)
                ->sum('rsd12');
            if ($sumanggarandewan > 0){
                $prosentasedewan = ($sumrealisasidewan/$sumanggarandewan)*100;
            }else{
                $prosentasedewan = 0;
            }

            $cardanggaranrealisasi = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Anggaran</span>
                                <span class="info-box-number">
                                 Anggaran Setjen: '. number_format($sumanggaransetjen,0,",",".").'
                                </span>
                                <span class="info-box-number">
                                 Realisasi Setjen: '.number_format($sumrealisasisetjen,0,",",".").'
                                </span>
                                <span class="info-box-number">
                                 Prosentase Setjen: '.number_format($prosentasesetjen,2,",",".").'
                                </span>

                                <span class="info-box-number">
                                 Anggaran Dewan: '.number_format($sumanggarandewan,0,",",".").'
                                </span>
                                 <span class="info-box-number">
                                 Realisasi Dewan: '.number_format($sumrealisasidewan,0,",","."). '
                                </span>
                                <span class="info-box-number">
                                 Prosentase Dewan: '.number_format($prosentasedewan,2,",",".").'
                                </span>
                            </div>
                        </div>
                        </div>';
            return $cardanggaranrealisasi;
        }
    }
}
