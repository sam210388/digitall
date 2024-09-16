<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\AnggaranRealisasiDewanService;
use App\Services\AnggaranRealisasiSetjenService;
use App\Services\IKPACaputService;
use App\Services\IKPAKontraktualService;
use App\Services\IKPAPenyelesaianService;
use App\Services\IKPAPenyerapanService;
use App\Services\IKPARevisiService;
use App\Services\MonitoringKegiatanServices;
use App\Services\IKPABagianServices;
use App\Services\BagianServices;
use App\Services\IKPADeviasiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardBagianController extends Controller
{
    protected $bagianService;
    protected $anggaranRealisasiSetjenService;

    protected $anggaranRealisasiDewanService;

    protected $monitoringKegiatanServices;

    protected $ikpabagianServices;

    protected $ikparevisiservices;

    protected $ikpadeviasiservices;

    protected $ikpapenyelesaianservices;

    protected $ikpapenyerapanservices;

    protected $ikpakontraktualservices;

    protected $ikpacaputservices;

    public function __construct(BagianServices $bagianService, AnggaranRealisasiSetjenService $anggaranRealisasiSetjenService,
                                AnggaranRealisasiDewanService $anggaranRealisasiDewanService, MonitoringKegiatanServices $monitoringKegiatanServices, IKPABagianServices $ikpabagianServices,
                                IKPARevisiService $ikparevisiservices, IKPADeviasiService $ikpadeviaservices, IKPAPenyelesaianService $ikpapenyelesaianservices,
                                IKPAPenyerapanService $ikpapenyerapanservices, IKPAKontraktualService $ikpakontraktualservices,
                                IKPACaputService $ikpacaputservices
                                )
    {
        $this->middleware(['auth']);
        $this->bagianService = $bagianService;
        $this->anggaranRealisasiSetjenService = $anggaranRealisasiSetjenService;
        $this->anggaranRealisasiDewanService = $anggaranRealisasiDewanService;
        $this->monitoringKegiatanServices = $monitoringKegiatanServices;
        $this->ikpabagianServices = $ikpabagianServices;
        $this->ikparevisiservices = $ikparevisiservices;
        $this->ikpadeviasiservices = $ikpadeviaservices;
        $this->ikpapenyelesaianservices = $ikpapenyelesaianservices;
        $this->ikpapenyerapanservices = $ikpapenyerapanservices;
        $this->ikpakontraktualservices = $ikpakontraktualservices;
        $this->ikpacaputservices = $ikpacaputservices;



    }
    public function dapatkandata() {
        $kdsatker = '001012';
        $idbagian = Auth::user()->idbagian;
        $uraianbagian = $this->bagianService->getUraianBagian();
        $tahunanggaran = session('tahunanggaran');

        // Query untuk dataset
        $datasetjen = $this->anggaranRealisasiSetjenService->getDataset($idbagian, $tahunanggaran,$kdsatker);


        // Menentukan bulan saat ini
        $currentMonth = date('n');
        $bulanterakhir = $currentMonth - 1;
        $uraianbulan = DB::table('bulan')->where('id','=',$currentMonth)->value('bulan');
        $uraianbulanterakhir = DB::table('bulan')->where('id','=',$bulanterakhir)->value('bulan');

        $realisasisetjensd = $datasetjen ? $datasetjen->{'rsd' . $currentMonth} : 0;
        $paguanggaransetjen = $datasetjen ? $datasetjen->paguanggaran : 0;
        $prosentasesetjensd = $realisasisetjensd ? ($realisasisetjensd/$paguanggaransetjen)*100 : 0;

        $anggaranDanRealisasiResult = $this->anggaranRealisasiSetjenService->calculatePercentages($datasetjen, $currentMonth);


        //echo $prosentasedeviasisetjenbulanberjalan;
        $waktucetak = now();

        //query untuk report monitoring kegiatan
        $monitoringKegiatanResult = $this->monitoringKegiatanServices->getKegiatanData($kdsatker,$bulanterakhir, $idbagian);

        $dataikpasetjen = $this->ikpabagianServices->getRekapIKPABagian($kdsatker,$bulanterakhir, $idbagian);

        $historyikpasetjen = $this->ikpabagianServices->historyrekapikpabagian($tahunanggaran,$kdsatker,$idbagian);

        //IKPA REVISI
        $dataikparevisi = $this->ikparevisiservices->getIKPARevisiBagian($tahunanggaran, $kdsatker,$bulanterakhir, $idbagian);

        $historyikparevisi = $this->ikparevisiservices->getHistoryIKPARevisi($tahunanggaran, $kdsatker, $idbagian);

        $historyrevisikemenkeu = $this->ikparevisiservices->gethistoryjumlahrevkemenkeu($tahunanggaran, $kdsatker, $idbagian);

        $historyrevisipok = $this->ikparevisiservices->gethistoryjumlahrevpok($tahunanggaran, $kdsatker, $idbagian);

        //IKPA DEVIASI
        $dataikpadeviasi = $this->ikpadeviasiservices->getIKPADeviasiBagian($tahunanggaran, $kdsatker, $bulanterakhir, $idbagian);
        $historyikpadeviasi = $this->ikpadeviasiservices->getHistoryIKPADeviasi($tahunanggaran, $kdsatker, $idbagian);
        $historydeviasi51 = $this->ikpadeviasiservices->getHistoryDeviasi51($tahunanggaran, $kdsatker, $idbagian);
        $historydeviasi52 = $this->ikpadeviasiservices->getHistoryDeviasi52($tahunanggaran, $kdsatker, $idbagian);
        $historydeviasi53 = $this->ikpadeviasiservices->getHistoryDeviasi53($tahunanggaran, $kdsatker, $idbagian);

        //IKPA PENYELESAIAN
        $dataikpapenyelesaian = $this->ikpapenyelesaianservices->getIKPApenyelesaianBagian($tahunanggaran, $kdsatker, $bulanterakhir, $idbagian);
        $historyjumlahtagihan = $this->ikpapenyelesaianservices->jumlahtagihanbulanan($tahunanggaran, $kdsatker, $idbagian);
        $historyikpapenyelesaian = $this->ikpapenyelesaianservices->historynilaiikpa($tahunanggaran, $kdsatker, $idbagian);
        $historytotalakumulatif = $this->ikpapenyelesaianservices->historytotalkumulatif($tahunanggaran, $kdsatker, $idbagian);

        //IKPA PENYERAPAN
        $dataikpapenyerapan = $this->ikpapenyerapanservices->getIKPAPenyerapanBagian($tahunanggaran, $kdsatker, $bulanterakhir, $idbagian);
        $historyikpapenyerapan = $this->ikpapenyerapanservices->historynilaiikpa($tahunanggaran, $kdsatker, $idbagian);
        $historytotalnominaltarget = $this->ikpapenyerapanservices->historytotalnominaltarget($tahunanggaran, $kdsatker, $idbagian);
        $historypenyerapansdperiodeini = $this->ikpapenyerapanservices->historypenyerapansdperiodeini($tahunanggaran, $kdsatker, $idbagian);

        //IKPA KONTRAKTUAL
        $dataikpakontraktual = $this->ikpakontraktualservices->getIKPAKontraktual($tahunanggaran, $kdsatker, $bulanterakhir, $idbagian);
        $historyikpakontraktual = $this->ikpakontraktualservices->historynilaiikpa($tahunanggaran, $kdsatker, $idbagian);
        $historyjumlahkontrak = $this->ikpakontraktualservices->historyjumlahkontrak($tahunanggaran, $kdsatker, $idbagian);

        //IKPA CAPUT
        $dataikpacaput = $this->ikpacaputservices->getDataIKPACaput($tahunanggaran, $kdsatker, $bulanterakhir, $idbagian);
        $historyikpacaput = $this->ikpacaputservices->historynilaiikpa($tahunanggaran, $kdsatker, $idbagian);
        $historyikpaketepatan = $this->ikpacaputservices->historyikpaketepatan($tahunanggaran, $kdsatker, $idbagian);
        $historyikpacapaian = $this->ikpacaputservices->historyikpacapaian($tahunanggaran, $kdsatker, $idbagian);


        return compact('uraianbagian',
            'uraianbulan',
            'uraianbulanterakhir',
            'waktucetak',
            'anggaranDanRealisasiResult',
            'realisasisetjensd',
            'paguanggaransetjen',
            'prosentasesetjensd',
            'monitoringKegiatanResult',
            'dataikpasetjen',
            'historyikpasetjen',
            'dataikparevisi',
            'historyikparevisi',
            'historyrevisikemenkeu',
            'historyrevisipok',
            'dataikpadeviasi',
            'historyikpadeviasi',
            'historydeviasi51',
            'historydeviasi52',
            'historydeviasi53',
            'historyjumlahtagihan',
            'dataikpapenyelesaian',
            'historyikpapenyelesaian',
            'historytotalakumulatif',
            'dataikpapenyerapan',
            'historyikpapenyerapan',
            'historytotalnominaltarget',
            'historypenyerapansdperiodeini',
            'dataikpakontraktual',
            'historyikpakontraktual',
            'historyjumlahkontrak',
            'dataikpacaput',
            'historyikpacapaian',
            'historyikpaketepatan',
            'historyikpacaput'


        );

    }

    public function index()
    {
        $data = $this->dapatkandata();
        // Mengirim data ke view
        return view('laporan.ikpa.dashboardkabagsetjen',$data);

    }

    public function cetakpdf(Request $request)
    {
        Log::info('Base64 Image for Kegiatan: ' . $request->get('chartImageKegiatan'));

        $data = $this->dapatkandata();
        $data['chartImageRealisasi'] = $request->get('chartImageRealisasi');
        $data['chartImageDeviasi'] = $request->get('chartImageDeviasi');
        $data['chartImageKegiatan'] = $request->get('chartImageKegiatan');
        $data['chartImageIKPA'] = $request->get('chartImageIKPA');
        $data['chartImageIKPADeviasi'] = $request->get('chartImageIKPADeviasi');
        $data['chartImageIKPARevisi'] = $request->get('chartImageIKPARevisi');
        $data['chartImageIKPAPenyelesaian'] = $request->get('chartImageIKPAPenyelesaian');
        $data['chartImageIKPAPenyerapan'] = $request->get('chartImageIKPAPenyerapan');
        $data['chartImageIKPAKontraktual'] = $request->get('chartImageIKPAKontraktual');
        $data['chartImageIKPACaput'] = $request->get('chartImageIKPACaput');

        $pdf = PDF::loadView('laporan.ikpa.dashboardkabagsetjen', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Dashboard-Kabag-Setjen.pdf');

    }

}
