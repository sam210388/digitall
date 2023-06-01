<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;

class ROExportRealisasi implements FromQuery, WithHeadings, WithPreCalculateFormulas
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
        $data = DB::table('ro as a')
            ->select([DB::raw('concat(a.tahunanggaran,".",a.kodesatker,".",a.kodekegiatan,".",
                a.kodeoutput,".",a.kodesuboutput," | ",a.uraianro) as ro'), 'a.target as target','n.uraianbiro as Biro','o.uraiandeputi as deputi',
                'b.paguanggaran as paguanggaran',
                'b.r1 as r1',DB::raw('(b.r1/b.paguanggaran)*100 as p1'),
                'b.rsd1 as rsd1',DB::raw('(b.rsd1/b.paguanggaran)*100 as psd1'),
                'b.r2 as r2',DB::raw('(b.r2/b.paguanggaran)*100 as p2'),
                'b.rsd2 as rsd2',DB::raw('(b.rsd2/b.paguanggaran)*100 as psd2'),
                'b.r3 as r3',DB::raw('(b.r3/b.paguanggaran)*100 as p3'),
                'b.rsd3 as rsd3',DB::raw('(b.rsd3/b.paguanggaran)*100 as psd3'),
                'b.r4 as r4',DB::raw('(b.r4/b.paguanggaran)*100 as p4'),
                'b.rsd4 as rsd4',DB::raw('(b.rsd4/b.paguanggaran)*100 as psd4'),
                'b.r5 as r5',DB::raw('(b.r5/b.paguanggaran)*100 as p5'),
                'b.rsd5 as rsd5',DB::raw('(b.rsd5/b.paguanggaran)*100 as psd5'),
                'b.r6 as r6',DB::raw('(b.r6/b.paguanggaran)*100 as p6'),
                'b.rsd6 as rsd6',DB::raw('(b.rsd6/b.paguanggaran)*100 as psd6'),
                'b.r7 as r7',DB::raw('(b.r7/b.paguanggaran)*100 as p7'),
                'b.rsd7 as rsd7',DB::raw('(b.rsd7/b.paguanggaran)*100 as psd7'),
                'b.r8 as r8',DB::raw('(b.r8/b.paguanggaran)*100 as p8'),
                'b.rsd8 as rsd8',DB::raw('(b.rsd8/b.paguanggaran)*100 as psd8'),
                'b.r9 as r9',DB::raw('(b.r9/b.paguanggaran)*100 as p9'),
                'b.rsd9 as rsd9',DB::raw('(b.rsd9/b.paguanggaran)*100 as psd9'),
                'b.r10 as r10',DB::raw('(b.r10/b.paguanggaran)*100 as p10'),
                'b.rsd10 as rsd10',DB::raw('(b.rsd10/b.paguanggaran)*100 as psd10'),
                'b.r11 as r11',DB::raw('(b.r11/b.paguanggaran)*100 as p11'),
                'b.rsd11 as rsd11',DB::raw('(b.rsd11/b.paguanggaran)*100 as psd11'),
                'b.r12 as r12',DB::raw('(b.r12/b.paguanggaran)*100 as p12'),
                'b.rsd12 as rsd12',DB::raw('(b.rsd12/b.paguanggaran)*100 as psd12')
            ])
            ->leftJoin('biro as n','a.idbiro','=','n.id')
            ->leftJoin('deputi as o','a.iddeputi','=','o.id')
            ->leftJoin('laporanrealisasianggaranbac as b','a.id','=','b.idro')
            ->where('a.tahunanggaran', '=', $tahunanggaran)
            ->groupBy('a.id')
            ->orderBy('a.id');
       //echo json_encode($data);
        return $data;
    }

    public function headings(): array
    {
       return ['RO','Target','Biro','Deputi',
           'Anggaran RO',
           'Realisasi Januari', 'Prosentase Januari',
           'Realisasi sd Januari', 'Prosentase sd Januari',
           'Realisasi Februari', 'Prosentase Februari',
           'Realisasi sd Februari', 'Prosentase sd Februari',
           'Realisasi Maret', 'Prosentase Maret',
           'Realisasi sd Maret', 'Prosentase sd Maret',
           'Realisasi April', 'Prosentase April',
           'Realisasi sd April', 'Prosentase sd April',
           'Realisasi Mei', 'Prosentase Mei',
           'Realisasi sd Mei', 'Prosentase sd Mei',
           'Realisasi Juni', 'Prosentase Juni',
           'Realisasi sd Juni', 'Prosentase sd Juni',
           'Realisasi Juli', 'Prosentase Juli',
           'Realisasi sd Juli', 'Prosentase sd Juli',
           'Realisasi Agustus', 'Prosentase Agustus',
           'Realisasi sd Agustus', 'Prosentase sd Agustus',
           'Realisasi September', 'Prosentase September',
           'Realisasi sd September', 'Prosentase sd September',
           'Realisasi Oktober', 'Prosentase Oktober',
           'Realisasi sd Oktober', 'Prosentase sd Oktober',
           'Realisasi November', 'Prosentase November',
           'Realisasi sd November', 'Prosentase sd November',
           'Realisasi Desember', 'Prosentase Desember',
           'Realisasi sd Desember', 'Prosentase sd Desember'];
    }


}
