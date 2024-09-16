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
                max-width: 100%;
                height: auto;  /* Biarkan tinggi menyesuaikan agar proporsional di halaman cetak */
            }
        }
        /* Untuk menampilkan pie chart di tengah layar dan cetakan */
        #pieChartKegiatanSetjen {
            display: block;
            margin: 0 auto;
            max-width: 400px;  /* Ubah ukuran max-width untuk lebih optimal */
            height: 400px;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            padding: 20px;  /* Memberi padding secukupnya */
        }

    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js" integrity="sha512-MpDFIChbcXl2QgipQrt1VcPHMldRILetapBl5MPCA9Y8r7qvlwx1/Mc9hNTzY+kS5kX6PdoDq41ws1HiVNLdZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div id="content" class="content">
    <button id="printButton" class="btn btn-primary">Cetak PDF</button>
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
    <h5>I. Realisasi Anggaran Berdasarkan SAKTI</h5>
    <p style="text-align: justify;">
        Realisasi Anggaran Untuk Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($realisasisetjensd, 0, ',', '.') }} atau sebesar {{ number_format($prosentasesetjensd, 2, ',', '.') }}% dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }}.
    </p>
    <p style="text-align: justify;">
        Realisasi Anggaran Untuk Bagian {{$uraianbagian}} Satker Dewan untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($realisasidewansd, 0, ',', '.') }} atau sebesar {{ number_format($prosentasedewansd, 2, ',', '.') }}% dari Pagu sebesar {{ number_format($paguanggarandewan, 0, ',', '.') }}.
    </p>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    <!-- Tambahkan canvas untuk chart -->
    <canvas id="mixedChartRealisasiSetjen" style="max-width: 800px; height: 400px;"></canvas>

    <div class="page-break"></div>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi Satker Dewan dapat digambarkan sebagai berikut:
    </p>
    <!-- Tambahkan canvas untuk chart -->
    <canvas id="mixedChartRealisasiDewan" style="max-width: 800px; height: 400px;"></canvas>

    <h5>II. Realisasi Vs Rencana Penarikan</h5>
    <p style="text-align: justify;">
        Rencana Penarikan Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($anggaranDanRealisasiResult['pokikpasdbulanberjalan'], 0, ',', '.') }}  dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }} atau sebesar {{ number_format($anggaranDanRealisasiResult['rencanasetjensdbulanberjalan'], 2, ',', '.') }}% dari pagu.
        Terdapat Deviasi sebesar {{ number_format($anggaranDanRealisasiResult['deviasisetjenbulanberjalan'], 0, ',', '.') }} atau {{ number_format($anggaranDanRealisasiResult['prosentasedeviasisetjenbulanberjalan'], 2, ',', '.') }}% jika dibandingkan dengan Realisasi sampai dengan Bulan berjalan.
    </p>
    <div class="page-break"></div>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi VS Rencana Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    <canvas id="mixedChartDeviasiSetjen" style="max-width: 800px; height: 400px;"></canvas>

    <p style="text-align: justify;">
        Rencana Penarikan Bagian {{$uraianbagian}} Satker Dewan untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($anggaranDanRealisasiDewanResult['pokikpasdbulanberjalan'], 0, ',', '.') }}  dari Pagu sebesar {{ number_format($paguanggarandewan, 0, ',', '.') }} atau sebesar {{ number_format($anggaranDanRealisasiDewanResult['rencanasetjensdbulanberjalan'], 2, ',', '.') }}% dari pagu.
        Terdapat Deviasi sebesar {{ number_format($anggaranDanRealisasiDewanResult['deviasisetjenbulanberjalan'], 0, ',', '.') }} atau {{ number_format($anggaranDanRealisasiDewanResult['prosentasedeviasisetjenbulanberjalan'], 2, ',', '.') }}% jika dibandingkan dengan Realisasi sampai dengan Bulan berjalan.
    </p>
    <div class="page-break"></div>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi VS Rencana Satker Dewan dapat digambarkan sebagai berikut:
    </p>
    <canvas id="mixedChartDeviasiDewan" style="max-width: 800px; height: 400px;"></canvas>

    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} Satker Setjen memiliki Total {{$monitoringKegiatanResult['total_kegiatan']}}.
        Dari jumlah tersebut, sebanyak {{$monitoringKegiatanResult['kegiatan_terlaksana']}} telah dilaksanakan dan sebanyak {{$monitoringKegiatanResult['kegiatan_terjadwal']}} masih berstatus terjadwal.
        Untuk kegiatan yang masih terjadwal, segera lakukan perubahan status yang diikuti dengan updating rencana penarikan agar capaian nilai IKPA
        dapat tetap terjaga. Rincian kegiatan dapat digambarkan sebagai berikut:
    </p>
    <div class="chart-container">
        <canvas id="pieChartKegiatanSetjen" style="max-width: 500px; height: auto;"></canvas>
    </div>
    <div class="page-break"></div>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} Satker Dewan memiliki Total {{$monitoringKegiatanDewanResult['total_kegiatan']}}.
        Dari jumlah tersebut, sebanyak {{$monitoringKegiatanDewanResult['kegiatan_terlaksana']}} telah dilaksanakan dan sebanyak {{$monitoringKegiatanDewanResult['kegiatan_terjadwal']}} masih berstatus terjadwal.
        Untuk kegiatan yang masih terjadwal, segera lakukan perubahan status yang diikuti dengan updating rencana penarikan agar capaian nilai IKPA
        dapat tetap terjaga. Rincian kegiatan dapat digambarkan sebagai berikut:
    </p>
    <div class="chart-container">
        <canvas id="pieChartKegiatanDewan" style="max-width: 500px; height: auto;"></canvas>
    </div>

    <h5>III. Indikator Kinerja Pelaksanaan Anggaran</h5>
    <h6>1. Penilaian Umum</h6>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} Satker Setjen memiliki raihan ikpa total adalah sebesar {{$dataikpasetjen['ikpatotal']}}.
        Nilai tersebut terdiri atas IKPA Revisi {{$dataikpasetjen['ikparevisi']}}, IKPA Deviasi {{$dataikpasetjen['ikpadeviasi']}}, IKPA Penyerapan {{$dataikpasetjen['ikpapenyerapan']}},
        IKPA Kontraktual {{$dataikpasetjen['ikpakontraktual']}}, IKPA Penyelesaian {{$dataikpasetjen['ikpapenyelesaian']}}, dan IKPA Caput {{$dataikpasetjen['ikpacaput']}}. Nilai IKPA Tersebut
        sudah mengalami konversi bobot pada area Pengelolaan Uang Muka akibat kendala sistem.
        Bapak/Ibu diharapkan memperhatikan capaian IKPA apabila masih berada dibawah target, yaitu {{$dataikpasetjen['targetikpa']}}. Perhatikan capaian pada setiap detil indikator
        dan lakukan perbaikan yang diperlukan. Historis capaian IKPA Bagian digambarkan sebagai berikut:
    </p>
    <canvas id="mixedChartIKPASetjen" style="max-width: 800px; height: 400px;"></canvas>

    <h6>2. Penilaian Per Indikator</h6>
    <h7>a. IKPA Revisi</h7>
    <p style="text-align: justify;">
        Sampai dengan Periode Bulan {{$uraianbulanterakhir}}, Bagian {{$uraianbagian}} meraih nilai IKPA Revisi sebesar {{$dataikparevisi['nilaiikparevisi']}}. Sampai dengan periode tersebut,
        telah dilakukan {{$dataikparevisi['jumlahrevisipok']}} revisi POK sehingga memperoleh nilai {{$dataikparevisi['nilaiikpapok']}} dan {{$dataikparevisi['jumlahrevisikemenkeu']}} revisi kewenangan Kemenkeu sehingga memperoleh
        nilai {{$dataikparevisi['nilaiikpakemenkeu']}}. Historis Capaian IKPA Revisi Bagian digambarkan sebagai berikut:
    </p>
    <canvas id="mixedChartIKPARevisiSetjen" style="max-width: 800px; height: 400px;"></canvas>

</div>

<!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    var ctxSetjen = document.getElementById('mixedChartRealisasiSetjen').getContext('2d');
    var ctxDewan = document.getElementById('mixedChartRealisasiDewan').getContext('2d');
    var ctxDeviasiSetjen = document.getElementById('mixedChartDeviasiSetjen').getContext('2d');
    var ctxDeviasiDewan = document.getElementById('mixedChartDeviasiDewan').getContext('2d');
    var ctxKegiatansetjen = document.getElementById('pieChartKegiatanSetjen').getContext('2d');
    var ctxKegiatanDewan = document.getElementById('pieChartKegiatanDewan').getContext('2d');
    var ctxIKPASetjen = document.getElementById('mixedChartIKPASetjen').getContext('2d');

    // Grafik Realisasi Satker Setjen
    var realisasiSetjenArray = Object.values(@json($anggaranDanRealisasiResult['realisasi'])); // Ubah objek menjadi array
    var prosentaseSetjenArray = Object.values(@json($anggaranDanRealisasiResult['prosentase'])); // Ubah objek menjadi array
    var prosentasesetjensdArray = Object.values(@json($anggaranDanRealisasiResult['prosentase_sd_periodik']));
    var prosentaseDeviasiSetjenArray = Object.values(@json($anggaranDanRealisasiResult['prosentase_deviasi_sd_periodik']));
    var pokikpasdpersensetjen = Object.values(@json($anggaranDanRealisasiResult['prosentasepokikpasd']));
    var kegiatan_terlaksana_setjen = {{$monitoringKegiatanResult['kegiatan_terlaksana']}};
    var kegiatan_terjadwal_setjen = {{$monitoringKegiatanResult['kegiatan_terjadwal']}};
    var kegiatan_terlaksana_dewan = {{$monitoringKegiatanDewanResult['kegiatan_terlaksana']}};
    var kegiatan_terjadwal_dewan = {{$monitoringKegiatanDewanResult['kegiatan_terjadwal']}};
    var historyikpasetjen = Object.values(@json($historyikpasetjen));

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
                    position: 'left',
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
                }
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
            responsive: true,
            maintainAspectRatio: false,  // Matikan aspect ratio agar lebih fleksibel
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
    console.log(typeof ChartDataLabels);

    // Grafik Realisasi Satker Dewan
    var realisasiDewanArray = Object.values(@json($anggaranDanRealisasiDewanResult['realisasi'])); // Ubah objek menjadi array
    var prosentaseDewanArray = Object.values(@json($anggaranDanRealisasiDewanResult['prosentase'])); // Ubah objek menjadi array
    var prosentasedewansdArray = Object.values(@json($anggaranDanRealisasiDewanResult['prosentase_sd_periodik']));
    var prosentaseDeviasiDewanArray = Object.values(@json($anggaranDanRealisasiDewanResult['prosentase_deviasi_sd_periodik']));
    var pokikpasdpersendewan = Object.values(@json($anggaranDanRealisasiDewanResult['prosentasepokikpasd']));
    var mixedChartDewan = new Chart(ctxDewan, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'bar',
                    label: 'Realisasi Anggaran (Rp)',
                    data: realisasiDewanArray,
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
                    data: prosentaseDewanArray,
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
                    data: prosentasedewansdArray,
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
            },plugins: {
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
    var mixedChartDeviasiDewan = new Chart(ctxDeviasiDewan, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (%)',
                    data: prosentasedewansdArray,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
                },
                {
                    type: 'line',
                    label: 'Deviasi (%)',
                    data: prosentaseDeviasiDewanArray,
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
                    data: pokikpasdpersendewan,
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
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    var kegiatanDewanPieChart = new Chart(ctxKegiatanDewan, {
        type: 'pie',
        data: {
            labels: ['Kegiatan Terlaksana', 'Kegiatan Terjadwal'],
            datasets: [{
                data: [kegiatan_terlaksana_dewan, kegiatan_terjadwal_dewan],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Warna untuk kegiatan terlaksana
                    'rgba(255, 206, 86, 0.6)', // Warna untuk kegiatan terjadwal
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,  // Matikan aspect ratio agar lebih fleksibel
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
            }
            ,datalabels: {
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
</script>

<!-- Script untuk tombol cetak PDF -->
<script>
    document.getElementById('printButton').addEventListener('click', function () {
        var element = document.querySelector('.content');
        var printButton = document.getElementById('printButton');
        if (!element) {
            console.error('Element .content tidak ditemukan.');
            return;
        } else {
            printButton.style.display = 'none';
            var opt = {
                margin: [0.5, 0.5, 0.5, 0.5],  // Margin lebih kecil agar lebih pas
                filename: 'ringkasan_kinerja_keuangan.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 }, // Tingkatkan skala untuk kualitas yang lebih baik
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().from(element).set(opt).save();
        }
    });
</script>
</body>
</html>
