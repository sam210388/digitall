<?php

namespace App\Libraries\Card;

use App\Libraries\FilterDataUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Filter;

class CardTemuanRekomendasi
{
    public function dapatkancard(){
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();

        //card temuan hanya di admin BPK dan admin
        if (in_array(1,$role) OR in_array(3, $role)){
            $cardtemuan = $this->cardtemuan();
        }else{
            $cardtemuan = null;
        }

        $cardrekomendasi = $this->cardrekomendasi();

        return array(
            'cardtemuan' => $cardtemuan,
            'cardrekomendasi' => $cardrekomendasi
        );

    }

    public function cardtemuan(){
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

        $cardtemuan = '<div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Temuan</span>
                                <span class="info-box-number">
                                 Jumlah Temuan: '.$jumlahtemuan.'
                                </span>
                                <span class="info-box-number">
                                 '.$prosentasetemuanselesai.' Selesai
                                </span>
                            </div>
                        </div>
                        </div>';

        return $cardtemuan;
    }

    public function cardrekomendasi(){
        //rekomendasi
        $jumlahrekomendasiselesai = DB::table('rekomendasi')
            ->where('status','=',6)
            ->orWhere('status','=',7);
        $role = new FilterDataUser();
        $role = $role->kewenanganuser();
        if (in_array(11,$role) OR in_array(12, $role)){
            $where = new FilterDataUser();
            $where = $where->filterdata();
            $jumlahrekomendasi = DB::table('rekomendasi')
                ->where($where)
                ->count();
            $jumlahrekomendasiselesai = $jumlahrekomendasiselesai->where($where)->count();
            if ($jumlahrekomendasiselesai == 0){
                $prosentaserekomendasiselesai = "0%";
            }else{
                $prosentaserekomendasiselesai = ($jumlahrekomendasiselesai/$jumlahrekomendasi)*100;
                $prosentaserekomendasiselesai = $prosentaserekomendasiselesai."%";
            }
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

            return $cardrekomendasi;
        }elseif(in_array(1,$role) OR in_array(3, $role)){
            $jumlahrekomendasi = DB::table('rekomendasi')
                ->count();
            $jumlahrekomendasiselesai = $jumlahrekomendasiselesai->count();
            if ($jumlahrekomendasiselesai == 0){
                $prosentaserekomendasiselesai = "0%";
            }else{
                $prosentaserekomendasiselesai = ($jumlahrekomendasiselesai/$jumlahrekomendasi)*100;
                $prosentaserekomendasiselesai = $prosentaserekomendasiselesai."%";
            }
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

            return $cardrekomendasi;
        }else{
            $cardrekomendasi = null;
            return $cardrekomendasi;
        }
    }
}
