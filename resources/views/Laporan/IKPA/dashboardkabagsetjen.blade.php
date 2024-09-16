<!DOCTYPE html>
<html>
<head>
    <title>Ringkasan Kinerja Keuangan</title>
    <style>
        body {
            margin: 20px;
        }
        header img {
            border-bottom: 2px solid #000;
            width: 100%;
            height: auto;
        }

        img {
            width: 100%; /* Gambar akan menyesuaikan lebar halaman */
            height: auto; /* Proporsi tinggi mengikuti lebar gambar */
            display: block;
            margin-bottom: 20px;
        }

        @page {
            size: A4 portrait;
        }
        .content {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            max-width: 800px; /* Lebar maksimum konten */
            margin-left: auto;
            margin-right: auto;
        }
        .content h5, .content h6, .content h7, .content h8 {
            margin-bottom: 10px;
        }
        .content h4 {
            all: unset;
            font-size: 1.5em;
            font-weight: bold;
            letter-spacing: normal;
            word-spacing: normal;
        }

        .content p {
            border: 1px solid #ddd; /* Border untuk paragraf */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        @media print {
            canvas {
                width: 100% !important;
                height: auto !important;
            }
            .content {
                page-break-inside: avoid;
            }
            .page-break {
                page-break-before: always;
            }
            #printButton {
                display: none !important; /* Menyembunyikan tombol cetak PDF */
            }
            #pieChartKegiatanSetjen {
                width: 500px !important;  /* Tentukan lebar yang tetap */
                height: 500px !important;  /* Tentukan tinggi yang tetap */
                display: block;
                margin: 0 auto;  /* Pusatkan canvas di halaman PDF */
            }
        }
        /* Untuk menampilkan pie chart di tengah layar dan cetakan */
        #pieChartKegiatanSetjen {
            display: block;
            margin: 0 auto;
            max-width: 500px;  /* Ubah ukuran max-width untuk lebih optimal */
            height: 500px;
        }

        .chart-container {
            display: block;
            margin: 20px auto;  /* Tambahkan margin untuk jarak antara elemen */
            padding: 10px;  /* Opsional: Jika diperlukan lebih banyak ruang */
            max-width: 500px;
            height: 500px;
        }

    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js" integrity="sha512-MpDFIChbcXl2QgipQrt1VcPHMldRILetapBl5MPCA9Y8r7qvlwx1/Mc9hNTzY+kS5kX6PdoDq41ws1HiVNLdZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div id="content" class="content">
    <div class="card">
        <div class="card-header">
            <form action="{{ route('cetakdashboardkabag') }}" method="POST">
                @csrf
                <input type="hidden" name="chartImageRealisasi" id="chartImageRealisasi">
                <input type="hidden" name="chartImageDeviasi" id="chartImageDeviasi">
                <input type="hidden" name="chartImageKegiatan" id="chartImageKegiatan">
                <input type="hidden" name="chartImageIKPA" id="chartImageIKPA">
                <input type="hidden" name="chartImageIKPADeviasi" id="chartImageIKPADeviasi">
                <input type="hidden" name="chartImageIKPARevisi" id="chartImageIKPARevisi">
                <input type="hidden" name="chartImageIKPAPenyelesaian" id="chartImageIKPAPenyelesaian">
                <input type="hidden" name="chartImageIKPAPenyerapan" id="chartImageIKPAPenyerapan">
                <input type="hidden" name="chartImageIKPAKontraktual" id="chartImageIKPAKontraktual">
                <input type="hidden" name="chartImageIKPACaput" id="chartImageIKPACaput">
                <button class="btn btn-primary" type="submit">Cetak PDF</button>
                <a href="{{url('/home')}}"  class="btn btn-info" >Kembali</a>
            </form>

        </div>
    </div>
    <!-- Form untuk mengirimkan base64 ke server -->

    <header>
        <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(asset('storage/kopsurat/KOP2.png')))}}" width="100%" height="100%"/>
    </header>
    <center>
        <h4>Ringkasan  Kinerja  Keuangan</h4>
        <h6>Bagian {{$uraianbagian}}</h6>
        <h7>Untuk Periode Sampai Dengan {{$uraianbulan}}</h7>
        <h8 position="right">Waktu Cetak : {{$waktucetak}}</h8>
        <br>
        <br>
    </center>
    <h4>I. Realisasi Anggaran Berdasarkan SAKTI</h4>
    <p style="text-align: justify;">
        Realisasi Anggaran Untuk Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($realisasisetjensd, 0, ',', '.') }} atau sebesar {{ number_format($prosentasesetjensd, 2, ',', '.') }}% dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }}.
    </p>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    <!-- Tambahkan canvas untuk chart -->
    @if(isset($chartImageRealisasi))
        <img src="{{ $chartImageRealisasi }}" id="chartImageRealisasi" alt="Chart Realisasi" style="max-width: 800px; height: 400px;">
    @else
        <canvas id="mixedChartRealisasiSetjen" style="max-width: 800px; height: 400px;"></canvas>
    @endif

    <h4 style="page-break-before: always;">II. Realisasi Vs Rencana Penarikan</h4>
    <p style="text-align: justify;">
        Rencana Penarikan Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($anggaranDanRealisasiResult['pokikpasdbulanberjalan'], 0, ',', '.') }}  dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }} atau sebesar {{ number_format($anggaranDanRealisasiResult['rencanasetjensdbulanberjalan'], 2, ',', '.') }}% dari pagu.
        Terdapat Deviasi sebesar {{ number_format($anggaranDanRealisasiResult['deviasisetjenbulanberjalan'], 0, ',', '.') }} atau {{ number_format($anggaranDanRealisasiResult['prosentasedeviasisetjenbulanberjalan'], 2, ',', '.') }}% jika dibandingkan dengan Realisasi sampai dengan Bulan berjalan.
    </p>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi VS Rencana Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    @if(isset($chartImageDeviasi))
        <img src="{{ $chartImageDeviasi }}" id="chartImageDeviasi" alt="Chart Realisasi Deviasi" style="max-width: 800px; height: 400px;">
    @else
        <canvas id="mixedChartDeviasiSetjen" style="max-width: 800px; height: 400px;"></canvas>
    @endif


    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} Satker Setjen memiliki Total {{$monitoringKegiatanResult['total_kegiatan']}}.
        Dari jumlah tersebut, sebanyak {{$monitoringKegiatanResult['kegiatan_terlaksana']}} telah dilaksanakan dan sebanyak {{$monitoringKegiatanResult['kegiatan_terjadwal']}} masih berstatus terjadwal.
        Untuk kegiatan yang masih terjadwal, segera lakukan perubahan status yang diikuti dengan updating rencana penarikan agar capaian nilai IKPA
        dapat tetap terjaga. Rincian kegiatan dapat digambarkan sebagai berikut:
    </p>
    @if(isset($chartImageKegiatan))
        <img src="{{ $chartImageKegiatan }}" id="chartImageKegiatan" alt="Chart Kegiatan" style="max-width: 500px; height: 500px;">
    @else
        <div class="chart-container">
            <canvas id="pieChartKegiatanSetjen" style="max-width: 500px; height: 500px;"></canvas>
        </div>
    @endif


    <h4>III. Indikator Kinerja Pelaksanaan Anggaran</h4>
    <h5>1. Penilaian Umum</h5>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} Satker Setjen memiliki raihan ikpa total adalah sebesar {{$dataikpasetjen['ikpatotal']}}.
        Nilai tersebut terdiri atas IKPA Revisi {{$dataikpasetjen['ikparevisi']}}, IKPA Deviasi {{$dataikpasetjen['ikpadeviasi']}}, IKPA Penyerapan {{$dataikpasetjen['ikpapenyerapan']}},
        IKPA Kontraktual {{$dataikpasetjen['ikpakontraktual']}}, IKPA Penyelesaian {{$dataikpasetjen['ikpapenyelesaian']}}, dan IKPA Caput {{$dataikpasetjen['ikpacaput']}}. Nilai IKPA Tersebut
        sudah mengalami konversi bobot pada area Pengelolaan Uang Muka akibat kendala sistem.
        Bapak/Ibu diharapkan memperhatikan capaian IKPA apabila masih berada dibawah target, yaitu {{$dataikpasetjen['targetikpa']}}. Perhatikan capaian pada setiap detil indikator
        dan lakukan perbaikan yang diperlukan. Historis capaian IKPA Bagian digambarkan sebagai berikut:
    </p>
    @if(isset($chartImageIKPA))
        <img src="{{ $chartImageIKPA }}" id="chartImageIKPA" alt="Chart IKPA" style="max-width: 700px; height: 350px;">
    @else
        <canvas id="mixedChartIKPASetjen" style="max-width: 800px; height: 400px;"></canvas>
    @endif


    <h5>2. Penilaian Per Indikator</h5>
    <h6>a. IKPA Revisi</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Revisi sebesar {{$dataikparevisi['nilaiikparevisi']}}. Pada periode tersebut,
        telah dilakukan {{$dataikparevisi['jumlahrevisipok']}} revisi POK sehingga memperoleh nilai {{$dataikparevisi['nilaiikpapok']}} dan {{$dataikparevisi['jumlahrevisikemenkeu']}}
        revisi kewenangan Kemenkeu sehingga memperoleh nilai {{$dataikparevisi['nilaiikpakemenkeu']}}.
        Seperti diketahui batas revisi yang telah ditentukan yaitu Maksimal 1 (satu) kali dalam 1 (bulan) untuk Revisi POK
        dan Maksimal 2 (dua) kali dalam 1 (satu) semester untuk Revisi ke Kementerian Keuangan.
        Historis Capaian IKPA Revisi Bagian digambarkan sebagai berikut:
    </p>
    @if(isset($chartImageIKPARevisi))
        <img src="{{ $chartImageIKPARevisi }}" id="chartImageIKPARevisi" alt="Chart IKPA Revisi" style="max-width: 600px; height: 300px;">
    @else
        <canvas id="mixedChartIKPARevisiSetjen" style="max-width: 800px; height: 400px;"></canvas>
    @endif


    <h6 style="page-break-before: always;">b. IKPA Deviasi</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Deviasi sebesar {{$dataikpadeviasi['nilaiikpa']}}. Pada periode tersebut, Bagian {{$uraianbagian}}
        memiliki porsi Pagu 51 {{$dataikpadeviasi['porsipagu51']}} persen, Porsi Pagu 52 {{$dataikpadeviasi['porsipagu52']}} persen
        dan Porsi Pagu 53 {{$dataikpadeviasi['porsipagu53']}} persen. Dari Porsi tersebut, Pagu 51 menyumbang Deviasi sebesar {{$dataikpadeviasi['prosentasedeviasi51']}},
        Porsi Pagu 52 Menyumbang Deviasi sebesar {{$dataikpadeviasi['prosentasedeviasi52']}} dan Porsi Pagu 53 menyumbang Deviasi sebesar {{$dataikpadeviasi['prosentasedeviasi53']}}. Sehingga Total
        Deviasi Tertimbang (Total dari %Deviasi x %Porsi Pagu) Bagian {{$uraianbagian}} adalah sebesar {{$dataikpadeviasi['reratadeviasikumulatif']}}.
    </p>
    <p style="text-align: justify;">
        Agar dapat mencapai/mempertahankan capaian IKPA Deviasi, penting bagi Unit Kerja untuk
        memperhatikan target penyerapan anggaran di tiap triwulan ketika melakukan pengisian
        RPD, sehingga isian tersebut bisa digunakan sebagai pengingat unit kerja untuk memenuhi target penyerapan anggaran.
        Rincian Perkembangan Total Deviasi dan Capaian IKPA Bagian, digambarkan sebagai berikut:
    </p>
    @if(isset($chartImageIKPADeviasi))
        <img src="{{ $chartImageIKPADeviasi }}" id="chartImageIKPADeviasi" alt="Chart IKPA Deviasi" style="max-width: 800px; height: 400px;">
    @else
        <canvas id="mixedChartIKPADeviasiSetjen" style="max-width: 800px; height: 400px;"></canvas>
    @endif

    <h6>c. IKPA Penyelesaian Tagihan</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Penyelesaian Tagihan sebesar {{$dataikpapenyelesaian['persen']}}. Pada periode tersebut, Bagian {{$uraianbagian}}
        memiliki total tagihan yang diperhitungkan dalam IKPA adalah sebanyak {{$dataikpapenyelesaian['totalakumulatif']}}. Dari jumlah tersebut
        sebanyak {{$dataikpapenyelesaian['tepatwaktuakumulatif']}} diselesaikan tepat waktu, dan sebanyak {{$dataikpapenyelesaian['terlambatakumulatif']}} diselesaikan melebihi batas waktu yang
        ditetapkan yaitu 17 hari kerja. Perlu diketahui bahwa IKPA Penyelesaian hanya memperhitungkan Tagihan LS Kontraktual dengan nilai diatas 50jt.
        Ringkasan perbandingan antara total tagihan, serta tagihan yang diperhitungkan dalam IKPA dan Nilai IKPA, disajikan sebagai berikut:
    </p>

    @if(isset($chartImageIKPAPenyelesaian))
        <img src="{{ $chartImageIKPAPenyelesaian }}" id="chartImageIKPAPenyelesaian" alt="Chart IKPA Penyelesaian" style="max-width: 800px; height: 400px;">
    @else
        <canvas id="mixedChartIKPAPenyelesaian" style="max-width: 800px; height: 400px;"></canvas>
    @endif

    <h6>d. IKPA Penyerapan</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Penyerapan sebesar {{$dataikpapenyerapan['nilaiikpapenyerapan']}}.
        Sampai dengan periode tersebut, Bagian {{$uraianbagian}} memiliki pagu 51 sebesar {{ number_format($dataikpapenyerapan['pagu51'], 0, ',', '.') }} dengan penyerapan sebesar
        {{ number_format($dataikpapenyerapan['penyerapan51'], 0, ',', '.') }}, pagu 52 sebesar {{ number_format($dataikpapenyerapan['pagu52'], 0, ',', '.') }} dengan penyerapan sebesar
        {{ number_format($dataikpapenyerapan['penyerapan52'], 0, ',', '.') }}, dan pagu 53 sebesar {{ number_format($dataikpapenyerapan['pagu53'], 0, ',', '.') }} dengan penyerapan sebesar
        {{ number_format($dataikpapenyerapan['penyerapan53'], 0, ',', '.') }}. Rincian perbandingan antara pagu dan penyerapan terhadap ikpa disajikan sebagai berikut:
    </p>
    @if(isset($chartImageIKPAPenyerapan))
        <img src="{{ $chartImageIKPAPenyerapan }}" id="chartImageIKPAPenyerapan" alt="Chart IKPA Penyerapan" style="max-width: 800px; height: 400px;">
    @else
        <canvas id="mixedChartIKPAPenyerapan" style="max-width: 800px; height: 400px;"></canvas>
    @endif

    <h6>e. IKPA Kontraktual</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Kontraktual sebesar {{$dataikpakontraktual['nilai']}}.
        Nilai tersebut diperoleh atas sejumlah {{$dataikpakontraktual['jumlahkontrak']}} kontrak. Atas sejumlah kontrak tersebut, Bagian
        {{$uraianbagian}} memperoleh nilai atas Komponen Distribusi {{$dataikpakontraktual['nilaikomponen']}}, nilai atas Komponen Akselerasi sebesar
        {{$dataikpakontraktual['nilaikomponenakselerasi']}} dan nilai atas Akselerasi Kontrak 53 sebesar {{$dataikpakontraktual['nilaikomponen53']}}. History
        Capaian Nilai IKPA Kontraktual Bagian {{$uraianbagian}} adalah sebagai berikut:
    </p>
    <p style="text-align: justify">
        Atas capaian tersebut, kami merekomendasikan Unit Kerja untuk memperhatikan tiga komponen di atas untuk dioptimalkan dengan ketentuan:
    </p>

    <ul style="text-align: justify">
        <li>
            Untuk menjaga Nilai Distribusi Kontrak Semester I, Unit Kerja diharapkan untuk tidak terlalu banyak mendaftarkan kontrak &gt; Rp.50.000.000,- pada Semester II.
            Semakin sedikit kontrak di Semester II maka semakin baik nilai capaiannya.
        </li>
        <li>
            Untuk menjaga nilai Kontrak Dini, Unit Kerja diharapkan untuk memperbanyak mendaftarkan kontrak sebelum tanggal 1 Januari Tahun Anggaran Berjalan.
        </li>
        <li>
            Untuk menjaga Akselerasi Belanja Modal, Unit Kerja diharapkan untuk mempercepat penyelesaian kontrak dengan nilai Rp.50.000.000 - Rp.200.000.000 di Triwulan I.
        </li>
    </ul>


@if(isset($chartImageIKPAKontraktual))
        <img src="{{ $chartImageIKPAKontraktual }}" id="chartImageIKPAKontraktual" alt="Chart IKPA Kontraktual" style="max-width: 600px; height: 300px;">
    @else
        <canvas id="mixedChartIKPAKontraktual" style="max-width: 800px; height: 400px;"></canvas>
    @endif

    <h6>f. IKPA Capaian Output</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Caput sebesar {{$dataikpacaput['nilaiikpa']}}.
        Nilai tersebut didapat atas raihan nilai Ketercapaian sebesar {{$dataikpacaput['rerataikpacapaian']}} persen dan raihan nilai Ketepatan sebesar
        {{$dataikpacaput['rerataikpaketepatan']}}. Unit Kerja perlu lebih memperhatikan 2 (dua) komponen di atas pada periode berikutnya, yaitu dengan
        melaporkan capaian output di Aplikasi DIGITALL tidak melewati 5 (lima) hari kerja di bulan berikutnya dan memastikan setiap output atas pelaksanaan anggaran telah sesuai dengan target yang ditetapkan dan dapat dibuktikan.
        Rincian perbandingan IKPA dan indikator pembentuknya disajikan sebagai berikut:

    </p>
    @if(isset($chartImageIKPACaput))
        <img src="{{ $chartImageIKPACaput }}" id="chartImageIKPACaput" alt="Chart IKPA Caput" style="max-width: 600px; height: 300px;">
    @else
        <canvas id="mixedChartIKPACaput" style="max-width: 800px; height: 400px;"></canvas>
    @endif
</div>

<!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    var ctxSetjen = document.getElementById('mixedChartRealisasiSetjen').getContext('2d');
    var ctxDeviasiSetjen = document.getElementById('mixedChartDeviasiSetjen').getContext('2d');
    var ctxKegiatansetjen = document.getElementById('pieChartKegiatanSetjen').getContext('2d');
    var ctxIKPASetjen = document.getElementById('mixedChartIKPASetjen').getContext('2d');
    var ctxIKPARevisi = document.getElementById('mixedChartIKPARevisiSetjen').getContext('2d');
    var ctxIKPADeviasi = document.getElementById('mixedChartIKPADeviasiSetjen').getContext('2d');
    var ctxIKPAPenyelesaian = document.getElementById('mixedChartIKPAPenyelesaian').getContext('2d');
    var ctxIKPAPenyerapan = document.getElementById('mixedChartIKPAPenyerapan').getContext('2d');
    var ctxIKPAKontraktual = document.getElementById('mixedChartIKPAKontraktual').getContext('2d');
    var ctxIKPACaput = document.getElementById('mixedChartIKPACaput').getContext('2d');

    // Grafik Realisasi Satker Setjen
    var realisasiSetjenArray = Object.values(@json($anggaranDanRealisasiResult['realisasi'])); // Ubah objek menjadi array
    var prosentaseSetjenArray = Object.values(@json($anggaranDanRealisasiResult['prosentase'])); // Ubah objek menjadi array
    var prosentasesetjensdArray = Object.values(@json($anggaranDanRealisasiResult['prosentase_sd_periodik']));
    var prosentaseDeviasiSetjenArray = Object.values(@json($anggaranDanRealisasiResult['prosentase_deviasi_sd_periodik']));
    var pokikpasdpersensetjen = Object.values(@json($anggaranDanRealisasiResult['prosentasepokikpasd']));
    var kegiatan_terlaksana_setjen = {{$monitoringKegiatanResult['kegiatan_terlaksana']}};
    var kegiatan_terjadwal_setjen = {{$monitoringKegiatanResult['kegiatan_terjadwal']}};
    var historyikpasetjen = Object.values(@json($historyikpasetjen));
    var historyikparevisi = Object.values(@json($historyikparevisi));
    var historyrevisikemenkeu = Object.values(@json($historyrevisikemenkeu));
    var historyrevisipok = Object.values(@json($historyrevisipok));

    //deviasi
    var historyikpadeviasi = Object.values(@json($historyikpadeviasi));
    var historydeviasi51 = Object.values(@json($historydeviasi51));
    var historydeviasi52 = Object.values(@json($historydeviasi52));
    var historydeviasi53 = Object.values(@json($historydeviasi53));

    //penyelesaian
    var historyikpapenyelesaian = Object.values(@json($historyikpapenyelesaian));
    var historyjumlahtagihan = Object.values(@json($historyjumlahtagihan));
    var historytotalkumulatif = Object.values(@json($historytotalakumulatif));

    //penyerapan
    var historyikpapenyerapan = Object.values(@json($historyikpapenyerapan));
    var historytotalnominaltarget = Object.values(@json($historytotalnominaltarget));
    var historypenyerapansdperiodeini = Object.values(@json($historypenyerapansdperiodeini));

    //kontraktual
    var historyikpakontraktual = Object.values(@json($historyikpakontraktual));
    var historyjumlahkontrak = Object.values(@json($historyjumlahkontrak));

    //Caput
    var historyikpacaput = Object.values(@json($historyikpacaput));
    var historyikpaketepatan = Object.values(@json($historyikpaketepatan));
    var historyikpacapaian = Object.values(@json($historyikpacapaian));




    var mixedChartSetjen = new Chart(ctxSetjen, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Realisasi Anggaran (Rp)',
                    data: realisasiSetjenArray,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (%)',
                    data: prosentaseSetjenArray,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Realisasi Anggaran SD (%)',
                    data: prosentasesetjensdArray,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Realisasi Anggaran (Rp)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Realisasi Anggaran (%)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Realisasi Anggaran SD (%)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
                datalabels: {
                    align: 'top', // Align the labels to the top of the data points
                    anchor: 'end', // Ensures the label is positioned relative to the point
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value.toFixed(2) + '%'; // Format label dengan 2 angka di belakang koma
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartDeviasiSetjen = new Chart(ctxDeviasiSetjen, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (%)',
                    data: prosentasesetjensdArray,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
                },
                {
                    type: 'line',
                    label: 'Deviasi (%)',
                    data: prosentaseDeviasiSetjenArray,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y2',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Rencana Penarikan (%)',
                    data: pokikpasdpersensetjen,
                    fill: false,
                    borderColor: 'rgba(22, 245, 115, 0.8)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y3',

                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Realisasi Anggaran SD (%)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Deviasi SD (%)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                },
                y3: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Rencana Penarikan SD (%)',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2) + '%'; // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                }
            },
            plugins: {
                datalabels: {
                    align: 'top', // Align the labels to the top of the data points
                    anchor: 'end', // Ensures the label is positioned relative to the point
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value.toFixed(2) + '%'; // Format label dengan 2 angka di belakang koma
                    }
                },
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var kegiatanPieChart = new Chart(ctxKegiatansetjen, {
        type: 'pie',
        data: {
            labels: ['Kegiatan Terlaksana', 'Kegiatan Terjadwal'],
            datasets: [{
                data: [kegiatan_terlaksana_setjen, kegiatan_terjadwal_setjen],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Warna untuk kegiatan terlaksana
                    'rgba(255, 206, 86, 0.6)', // Warna untuk kegiatan terjadwal
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,  // Matikan aspect ratio agar lebih fleksibel
            animation: {
                onComplete: function() {
                    // Set nilai base64 ke input hidden setelah chart selesai dirender
                    var canvas3 = document.getElementById('pieChartKegiatanSetjen');
                    var base64Image = canvas3.toDataURL('image/png');
                    document.getElementById('chartImageKegiatan').value = base64Image;  // Isi input hidden
                    console.log(base64Image);  // Cek apakah base64 dihasilkan
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
                title: {
                    display: true,
                    text: 'Perbandingan Kegiatan Terlaksana dan Terjadwal',
                    padding: {
                        top: 10,
                        bottom: 10  // Mengurangi padding di bawah judul
                    }
                },
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 10,
                    bottom: 10
                }
            },
            datalabels: {
                align: 'top', // Align the labels to the top of the data points
                anchor: 'end', // Ensures the label is positioned relative to the point
                font: {
                    weight: 'bold'
                },
                formatter: function(value) {
                    return value.toFixed(2) + '%'; // Format label dengan 2 angka di belakang koma
                }
            }
        },
        plugins: [ChartDataLabels]

    });
    var mixedChartIKPASetjen = new Chart(ctxIKPASetjen, {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (Rp)',
                    data: historyikpasetjen,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nilai IKPA',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2); // Format persentase dengan 2 angka di belakang koma
                        }
                    }
                },
            },
            plugins: {
                datalabels: {
                    align: 'top', // Align the labels to the top of the data points
                    anchor: 'end', // Ensures the label is positioned relative to the point
                    font: {
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartRevisi = new Chart(ctxIKPARevisi, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Revisi Kemenkeu',
                    data: historyrevisikemenkeu,
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'bar',
                    label: 'Revisi POK',
                    data: historyrevisipok,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikparevisi,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revisi Kemenkeu',
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revisi POK',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Nilai IKPA Revisi',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                }
            },
            plugins: {
                datalabels: {
                    align: 'bottom', // Align the labels to the top of the data points
                    anchor: 'end', // Ensures the label is positioned relative to the point
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        if (typeof value === 'number') {
                            return value.toFixed(2) + '%'; // Format label dengan dua desimal
                        } else {
                            return value; // Jika bukan angka, tampilkan nilai apa adanya
                        }
                    }
                },
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartDeviasi = new Chart(ctxIKPADeviasi, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Deviasi 51',
                    data: historydeviasi51,
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'bar',
                    label: 'Deviasi 52',
                    data: historydeviasi52,
                    backgroundColor: 'rgba(50, 73, 231, 0.8)',
                    borderColor: 'rgba(208, 241, 207, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'bar',
                    label: 'Deviasi 53',
                    data: historydeviasi53,
                    backgroundColor: 'rgba(231, 50, 171, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y2',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikpadeviasi,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y3',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Deviasi 51',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Deviasi 52',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Deviasi 53',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y3: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Nilai IKPA Deviasi',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                }
            },
            plugins: {
                datalabels: {
                    align: 'bottom', // Align the labels to the top of the data points
                    anchor: 'end', // Ensures the label is positioned relative to the point
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        if (typeof value === 'number') {
                            return value.toFixed(2) + '%'; // Format label dengan dua desimal
                        } else {
                            return value; // Jika bukan angka, tampilkan nilai apa adanya
                        }
                    }
                },
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartPenyelesaian = new Chart(ctxIKPAPenyelesaian, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Jumlah Tagihan',
                    data: historyjumlahtagihan,
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    datalabels: {
                        align: 'bottom', // Align the labels to the top of the data points
                        anchor: 'end', // Ensures the label is positioned relative to the point
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    },
                },
                {
                    type: 'bar',
                    label: 'Total Kumulatif',
                    data: historytotalkumulatif,
                    backgroundColor: 'rgba(50, 73, 231, 0.8)',
                    borderColor: 'rgba(208, 241, 207, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikpapenyelesaian,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y2',
                    datalabels: {
                        align: 'bottom', // Align the labels to the top of the data points
                        anchor: 'end', // Ensures the label is positioned relative to the point
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    },
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Tagihan',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total Kumulatif',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Nilai IKPA Penyelesaian',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartPenyerapan = new Chart(ctxIKPAPenyerapan, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Nominal Target',
                    data: historytotalnominaltarget,
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'bar',
                    label: 'Penyerapan',
                    data: historypenyerapansdperiodeini,
                    backgroundColor: 'rgba(107, 110, 236, 0.8)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y1',
                    datalabels: {
                        display: false
                    }
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikpapenyerapan,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y2',
                    datalabels: {
                        align: 'bottom', // Align the labels to the top of the data points
                        anchor: 'end', // Ensures the label is positioned relative to the point
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    },
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nominal Target',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Penyerapan',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y2: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Nilai IKPA',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartKontraktual = new Chart(ctxIKPAKontraktual, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Jumlah Kontrak',
                    data: historyjumlahkontrak,
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikpakontraktual,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
                    datalabels: {
                        align: 'bottom', // Align the labels to the top of the data points
                        anchor: 'end', // Ensures the label is positioned relative to the point
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    },
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,  // Set false to show all ticks, but rotate them
                        maxRotation: 90,  // Rotate labels 90 degrees
                        minRotation: 45   // Minimal rotation angle
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    },
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Kontrak',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Nilai IKPA',
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%'; // Format angka dengan dua desimal
                            } else {
                                return value; // Jika bukan angka, kembalikan nilai apa adanya
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',  // Memindahkan legend ke bawah untuk mengurangi jarak
                    labels: {
                        boxWidth: 15,  // Ukuran kotak warna di legend
                        padding: 10   // Mengurangi padding antara legend dan chart
                    }
                },
            }
        },
        plugins: [ChartDataLabels]
    });
    var mixedChartCaput = new Chart(ctxIKPACaput, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Ketercapaian',
                    data: historyikpacapaian, // Data ketercapaian
                    backgroundColor: 'rgba(39, 245, 73, 1)',
                    borderColor: 'rgba(39, 245, 73, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    stack: 'stack1' // Menggunakan stack yang sama untuk stacking
                },
                {
                    type: 'bar',
                    label: 'Ketepatan',
                    data: historyikpaketepatan, // Data ketepatan
                    backgroundColor: 'rgba(255, 159, 64, 1)', // Warna yang berbeda untuk bar ketepatan
                    borderColor: 'rgba(255, 159, 64, 0.8)',
                    borderWidth: 1,
                    yAxisID: 'y',
                    stack: 'stack1' // Stack yang sama agar kedua dataset ini ditumpuk
                },
                {
                    type: 'line',
                    label: 'Nilai IKPA',
                    data: historyikpacaput,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1,
                    yAxisID: 'y1',
                    datalabels: {
                        align: 'bottom',
                        anchor: 'end',
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%';
                            } else {
                                return value;
                            }
                        }
                    }
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 90,
                        minRotation: 45
                    },
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    stacked: true, // Mengaktifkan stacked chart untuk sumbu Y
                    title: {
                        display: true,
                        text: 'IKPA Ketepatan dan Ketercapaian'
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toLocaleString('id-ID'); // Format angka dengan pemisah ribuan
                            } else {
                                return value;
                            }
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    stacked: false, // Tidak ditumpuk untuk line chart
                    title: {
                        display: true,
                        text: 'Nilai IKPA'
                    },
                    ticks: {
                        callback: function(value) {
                            if (typeof value === 'number') {
                                return value.toFixed(2) + '%';
                            } else {
                                return value;
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 15,
                        padding: 10
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    console.log(document.getElementById('chartImageKegiatan').value);



</script>

<!-- Script untuk tombol cetak PDF -->
<script>


    setTimeout(function() {
        var canvas1 = document.getElementById('mixedChartRealisasiSetjen');
        document.getElementById('chartImageRealisasi').value = canvas1.toDataURL('image/png');

        var canvas2 = document.getElementById('mixedChartDeviasiSetjen');
        document.getElementById('chartImageDeviasi').value = canvas2.toDataURL('image/png');

        //var canvas3 = document.getElementById('pieChartKegiatanSetjen');
        //canvas3.width = 500;  // Set lebar canvas
        //canvas3.height = 500;  // Set tinggi canvas
        //document.getElementById('chartImageKegiatan').value = canvas3.toDataURL('image/png');
        //document.getElementById('chartImageKegiatan').value = canvas3.toDataURL('image/png');

        var canvas4 = document.getElementById('mixedChartIKPASetjen');
        document.getElementById('chartImageIKPA').value = canvas4.toDataURL('image/png');

        var canvas5 = document.getElementById('mixedChartIKPADeviasiSetjen');
        document.getElementById('chartImageIKPADeviasi').value = canvas5.toDataURL('image/png');

        var canvas6 = document.getElementById('mixedChartIKPARevisiSetjen');
        document.getElementById('chartImageIKPARevisi').value = canvas6.toDataURL('image/png');

        var canvas7 = document.getElementById('mixedChartIKPAPenyelesaian');
        document.getElementById('chartImageIKPAPenyelesaian').value = canvas7.toDataURL('image/png');

        var canvas8 = document.getElementById('mixedChartIKPAPenyerapan');
        document.getElementById('chartImageIKPAPenyerapan').value = canvas8.toDataURL('image/png');

        var canvas9 = document.getElementById('mixedChartIKPAKontraktual');
        document.getElementById('chartImageIKPAKontraktual').value = canvas9.toDataURL('image/png');

        var canvas10 = document.getElementById('mixedChartIKPACaput');
        document.getElementById('chartImageIKPACaput').value = canvas10.toDataURL('image/png');

    }, 500);  // Tunggu sejenak agar grafik selesai di-render
</script>
<script type="text/javascript"></script>
</body>
</html>
