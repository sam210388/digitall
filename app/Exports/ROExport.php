<?php

namespace App\Exports;

use App\Models\Caput\Admin\RoModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ROExport implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $tahunanggaran = session('tahunanggaran');
        $data = DB::table('ro as a')
            ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                a.kodeoutput,".",a.kodesuboutput," | ",a.uraianro) as ro'), 'a.target as target','n.uraianbiro as Biro','o.uraiandeputi as deputi',
                'b.jumlahsdperiodeini as jumlahsdjanuari','b.prosentasesdperiodeini as prosentasesdjanuari',
                'c.jumlahsdperiodeini as jumlahsdfebruari','c.prosentasesdperiodeini as prosentasesdfebruari',
                'd.jumlahsdperiodeini as jumlahsdmaret','d.prosentasesdperiodeini as prosentasesdmaret',
                'e.jumlahsdperiodeini as jumlahsdapril','e.prosentasesdperiodeini as prosentasesdapril',
                'f.jumlahsdperiodeini as jumlahsdmei','f.prosentasesdperiodeini as prosentasesdmei',
                'g.jumlahsdperiodeini as jumlahsdjuni','g.prosentasesdperiodeini as prosentasesdjuni',
                'h.jumlahsdperiodeini as jumlahsdjuli','h.prosentasesdperiodeini as prosentasesdjuli',
                'i.jumlahsdperiodeini as jumlahagustus','i.prosentasesdperiodeini as prosentasesdagustuss',
                'j.jumlahsdperiodeini as jumlahseptember','j.prosentasesdperiodeini as prosentasesdseptember',
                'k.jumlahsdperiodeini as jumlahoktober','k.prosentasesdperiodeini as prosentasesdoktober',
                'l.jumlahsdperiodeini as jumlahnovember','l.prosentasesdperiodeini as prosentasesdnovember',
                'm.jumlahsdperiodeini as jumlahjanuari','m.prosentasesdperiodeini as prosentasesddesember',

            ])
            ->leftJoin('biro as n','a.idbiro','=','n.id')
            ->leftJoin('deputi as o','a.iddeputi','=','o.id')
            ->leftJoin('realisasiro as b',function ($join){
                $join->on('a.id','=','b.idro');
                $join->on('b.periode','=',DB::raw(1));
            })
            ->leftJoin('realisasiro as c',function ($join){
                $join->on('a.id','=','c.idro');
                $join->on('c.periode','=',DB::raw(2));
            })
            ->leftJoin('realisasiro as d',function ($join){
                $join->on('a.id','=','d.idro');
                $join->on('d.periode','=',DB::raw(3));
            })
            ->leftJoin('realisasiro as e',function ($join){
                $join->on('a.id','=','e.idro');
                $join->on('e.periode','=',DB::raw(4));
            })
            ->leftJoin('realisasiro as f',function ($join){
                $join->on('a.id','=','f.idro');
                $join->on('f.periode','=',DB::raw(5));
            })
            ->leftJoin('realisasiro as g',function ($join){
                $join->on('a.id','=','g.idro');
                $join->on('g.periode','=',DB::raw(6));
            })
            ->leftJoin('realisasiro as h',function ($join){
                $join->on('a.id','=','h.idro');
                $join->on('h.periode','=',DB::raw(7));
            })
            ->leftJoin('realisasiro as i',function ($join){
                $join->on('a.id','=','i.idro');
                $join->on('i.periode','=',DB::raw(8));
            })
            ->leftJoin('realisasiro as j',function ($join){
                $join->on('a.id','=','j.idro');
                $join->on('j.periode','=',DB::raw(9));
            })
            ->leftJoin('realisasiro as k',function ($join){
                $join->on('a.id','=','k.idro');
                $join->on('k.periode','=',DB::raw(10));
            })
            ->leftJoin('realisasiro as l',function ($join){
                $join->on('a.id','=','l.idro');
                $join->on('l.periode','=',DB::raw(11));
            })
            ->leftJoin('realisasiro as m',function ($join){
                $join->on('a.id','=','m.idro');
                $join->on('m.periode','=',DB::raw(12));
            })
            ->where('a.tahunanggaran', '=', $tahunanggaran)
            ->orderBy('a.id');

        return $data;
    }

    public function headings(): array
    {
       return ['RO','Target','Biro','Deputi','Jumlah sd Januari','Prosentase sd Januari',
           'Jumlah sd Februari','Prosentase sd Februari',
           'Jumlah sd Maret','Prosentase sd Maret',
           'Jumlah sd April','Prosentase sd April',
           'Jumlah sd Mei','Prosentase sd Mei',
           'Jumlah sd Juni','Prosentase sd Juni',
           'Jumlah sd Juli','Prosentase sd Juli',
           'Jumlah sd Agustus','Prosentase sd Agustus',
           'Jumlah sd September','Prosentase sd September',
           'Jumlah sd Oktober','Prosentase sd Oktober',
           'Jumlah sd November','Prosentase sd November',
           'Jumlah sd Desember','Prosentase sd Desember'];
    }
}
