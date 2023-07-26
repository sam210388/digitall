<?php

namespace App\Exports;

use App\Models\Caput\Admin\RoModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IndikatorROExport implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $TA;

    public function __construct($TA)
    {
        $this->TA = $TA;
    }

    public function query()
    {
        $tahunanggaran = $this->TA;
        $data = DB::table('indikatorro as a')
            ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                a.kodeoutput,".",a.kodesuboutput," | ",a.uraianindikatorro) as indikatorro'), 'a.target as target','n.uraianbiro as Biro','o.uraiandeputi as deputi',
                'b.jumlah as jumlahjanuari','b.prosentase as prosentasejanuari',
                'b.jumlahsdperiodeini as jumlahsdjanuari','b.prosentasesdperiodeini as prosentasesdjanuari',
                'c.jumlah as jumlahfebruari','c.prosentase as prosentasefebruari',
                'c.jumlahsdperiodeini as jumlahsdfebruari','c.prosentasesdperiodeini as prosentasesdfebruari',
                'd.jumlah as jumlahmaret','d.prosentase as prosentasemaret',
                'd.jumlahsdperiodeini as jumlahsdmaret','d.prosentasesdperiodeini as prosentasesdmaret',
                'e.jumlah as jumlahapril','e.prosentase as prosentaseapril',
                'e.jumlahsdperiodeini as jumlahsdapril','e.prosentasesdperiodeini as prosentasesdapril',
                'f.jumlah as jumlahmei','f.prosentase as prosentasemei',
                'f.jumlahsdperiodeini as jumlahsdmei','f.prosentasesdperiodeini as prosentasesdmei',
                'g.jumlah as jumlahjuni','g.prosentase as prosentasejuni',
                'g.jumlahsdperiodeini as jumlahsdjuni','g.prosentasesdperiodeini as prosentasesdjuni',
                'h.jumlah as jumlahjuli','h.prosentase as prosentasejuli',
                'h.jumlahsdperiodeini as jumlahsdjuli','h.prosentasesdperiodeini as prosentasesdjuli',
                'i.jumlah as jumlahagustus','i.prosentase as prosentaseagustus',
                'i.jumlahsdperiodeini as jumlahsdagustus','i.prosentasesdperiodeini as prosentasesdagustus',
                'j.jumlah as jumlahsdseptember','j.prosentase as prosentaseseptember',
                'j.jumlahsdperiodeini as jumlahsdseptember','j.prosentasesdperiodeini as prosentasesdseptember',
                'k.jumlah as jumlahoktober','k.prosentase as prosentaseoktober',
                'k.jumlahsdperiodeini as jumlahsdoktober','k.prosentasesdperiodeini as prosentasesdoktober',
                'l.jumlah as jumlahnovember','l.prosentase as prosentasenovember',
                'l.jumlahsdperiodeini as jumlahsdnovember','l.prosentasesdperiodeini as prosentasesdnovember',
                'm.jumlah as jumlahdesember','m.prosentase as prosentasedesember',
                'm.jumlahsdperiodeini as jumlahsddesember','m.prosentasesdperiodeini as prosentasesddesember',
            ])
            ->leftJoin('biro as n','a.idbiro','=','n.id')
            ->leftJoin('deputi as o','a.iddeputi','=','o.id')
            ->leftJoin('realisasiindikatorro as b',function ($join){
                $join->on('a.id','=','b.idindikatorro');
                $join->on('b.periode','=',DB::raw(1));
            })
            ->leftJoin('realisasiindikatorro as c',function ($join){
                $join->on('a.id','=','c.idindikatorro');
                $join->on('c.periode','=',DB::raw(2));
            })
            ->leftJoin('realisasiindikatorro as d',function ($join){
                $join->on('a.id','=','d.idindikatorro');
                $join->on('d.periode','=',DB::raw(3));
            })
            ->leftJoin('realisasiindikatorro as e',function ($join){
                $join->on('a.id','=','e.idindikatorro');
                $join->on('e.periode','=',DB::raw(4));
            })
            ->leftJoin('realisasiindikatorro as f',function ($join){
                $join->on('a.id','=','f.idindikatorro');
                $join->on('f.periode','=',DB::raw(5));
            })
            ->leftJoin('realisasiindikatorro as g',function ($join){
                $join->on('a.id','=','g.idindikatorro');
                $join->on('g.periode','=',DB::raw(6));
            })
            ->leftJoin('realisasiindikatorro as h',function ($join){
                $join->on('a.id','=','h.idindikatorro');
                $join->on('h.periode','=',DB::raw(7));
            })
            ->leftJoin('realisasiindikatorro as i',function ($join){
                $join->on('a.id','=','i.idindikatorro');
                $join->on('i.periode','=',DB::raw(8));
            })
            ->leftJoin('realisasiindikatorro as j',function ($join){
                $join->on('a.id','=','j.idindikatorro');
                $join->on('j.periode','=',DB::raw(9));
            })
            ->leftJoin('realisasiindikatorro as k',function ($join){
                $join->on('a.id','=','k.idindikatorro');
                $join->on('k.periode','=',DB::raw(10));
            })
            ->leftJoin('realisasiindikatorro as l',function ($join){
                $join->on('a.id','=','l.idindikatorro');
                $join->on('l.periode','=',DB::raw(11));
            })
            ->leftJoin('realisasiindikatorro as m',function ($join){
                $join->on('a.id','=','m.idindikatorro');
                $join->on('m.periode','=',DB::raw(12));
            })
            ->where('a.tahunanggaran', '=', $tahunanggaran)
            ->orderBy('a.id');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
       return ['Indikato RO','Target','Biro','Deputi',
           'Jumlah Januari','Prosentase Januari',
           'Jumlah sd Januari','Prosentase sd Januari',
           'Jumlah Februari','Prosentase Februari',
           'Jumlah sd Februari','Prosentase sd Februari',
           'Jumlah Maret','Prosentase Maret',
           'Jumlah sd Maret','Prosentase sd Maret',
           'Jumlah April','Prosentase April',
           'Jumlah sd April','Prosentase sd April',
           'Jumlah Mei','Prosentase Mei',
           'Jumlah sd Mei','Prosentase sd Mei',
           'Jumlah Juni','Prosentase Juni',
           'Jumlah sd Juni','Prosentase sd Juni',
           'Jumlah sd Juli','Prosentase sd Juli',
           'Jumlah Agustus','Prosentase Agustus',
           'Jumlah sd Agustus','Prosentase sd Agustus',
           'Jumlah September','Prosentase September',
           'Jumlah sd September','Prosentase sd September',
           'Jumlah Oktober','Prosentase Oktober',
           'Jumlah sd Oktober','Prosentase sd Oktober',
           'Jumlah November','Prosentase November',
           'Jumlah sd November','Prosentase sd November',
           'Jumlah Desember','Prosentase Desember',
           'Jumlah sd Desember','Prosentase sd Desember'];
    }
}
