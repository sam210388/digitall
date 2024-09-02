<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class CekPengenal
{
    function kodeprogram($pengenal){
        return substr($pengenal,0,2);
    }

    function kodekegiatan($pengenal){
        return substr($pengenal,3,4);
    }

    function kodeoutput($pengenal){
        return substr($pengenal,8,3);
    }

    function kodesuboutput($pengenal){
        return substr($pengenal,12,3);
    }

    function kodekomponen($pengenal){
        return substr($pengenal,16,3);
    }

    function kodesubkomponen($pengenal){
        return substr($pengenal,20,1);
    }

    function satkerpemilik($pengenal){
        $kodeprogram = $this->kodeprogram($pengenal);
        $kodekegiatan = $this->kodekegiatan($pengenal);
        $kodeoutput =$this->kodeoutput($pengenal);

        $tahunanggaran = session('tahunanggaran');

        $where = array(
            'tahunanggaran' => $tahunanggaran,
            'kodeprogram' => $kodeprogram,
            'kodekegiatan' => $kodekegiatan,
            'kodeoutput' => $kodeoutput
        );

        return DB::table('anggaranbagian')
            ->where($where)
            ->value('kdsatker');
    }


    function pengenalunit($pengenal){
        $kodesatker = $this->satkerpemilik($pengenal);
        $kodeprogram = $this->kodeprogram($pengenal);
        $kodekegiatan = $this->kodekegiatan($pengenal);
        $kodeoutput = $this->kodeoutput($pengenal);
        $kodesuboutput = $this->kodesuboutput($pengenal);
        $kodekomponen = $this->kodekomponen($pengenal);
        $kodesubkomponen = $this->kodesubkomponen($pengenal);
        $pengenal = $kodeprogram.".".$kodekegiatan.".".$kodeoutput.".".$kodesuboutput.".".$kodekomponen;
        if ($kodesatker == '001030'){
            return $pengenal;
        }else{
            return $pengenal.".".$kodesubkomponen;
        }
    }

    function cekbagian($pengenal){
        $pengenalunit = $this->pengenalunit($pengenal);
        return DB::table('anggaranbagian')
            ->where('pengenal','=',$pengenalunit)
            ->limit(1)
            ->value('idbagian');
    }

    function cekbiro($pengenal){
        $pengenalunit = $this->pengenalunit($pengenal);
        return DB::table('anggaranbagian')
            ->where('pengenal','=',$pengenalunit)
            ->limit(1)
            ->value('idbiro');
    }

    function cekdeputi($pengenal){
        $pengenalunit = $this->pengenalunit($pengenal);
        return DB::table('anggaranbagian')
            ->where('pengenal','=',$pengenalunit)
            ->limit(1)
            ->value('iddeputi');
    }
}
