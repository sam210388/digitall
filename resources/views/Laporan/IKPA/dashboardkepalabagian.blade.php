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
        .content p {
            border: 1px solid #ddd; /* Border untuk paragraf */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js" integrity="sha512-MpDFIChbcXl2QgipQrt1VcPHMldRILetapBl5MPCA9Y8r7qvlwx1/Mc9hNTzY+kS5kX6PdoDq41ws1HiVNLdZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<header>
    <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(asset('storage/kopsurat/KOP2.png')))}}" width="100%" height="100%"/>
</header>
<div id="content" class="content">
    <button id="printButton" class="btn btn-primary">Cetak PDF</button>
    <center>
        <h4>Ringkasan Kinerja Keuangan</h4>
        <h6>Bagian {{$uraianbagian}}</h6>
        <h7>Untuk Periode Sampai Dengan {{$uraianbulan}}</h7>
        <h8 position="right">Waktu Cetak : {{$waktucetak}}</h8>
        <br>
        <br>
    </center>
    <h5>I. Realisasi Anggaran Berdasarkan SAKTI</h5>
    <p style="text-align: justify;">
        Realisasi Anggaran Untuk Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($realisasisetjensakti, 0, ',', '.') }} atau sebesar {{ number_format($prosentasesetjensakti, 2, ',', '.') }}% dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }}.
    </p>
    <p style="text-align: justify;">
        Realisasi Anggaran Untuk Bagian {{$uraianbagian}} Satker Dewan untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($realisasidewansakti, 0, ',', '.') }} atau sebesar {{ number_format($prosentasedewansakti, 2, ',', '.') }}% dari Pagu sebesar {{ number_format($paguanggarandewan, 0, ',', '.') }}.
    </p>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    <!-- Tambahkan canvas untuk chart -->
    <canvas id="mixedChartRealisasiSetjen"></canvas>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi Satker Dewan dapat digambarkan sebagai berikut:
    </p>
    <!-- Tambahkan canvas untuk chart -->
    <canvas id="mixedChartRealisasiDewan"></canvas>

    <h5>II. Realisasi Vs Rencana Penarikan</h5>
    <p style="text-align: justify;">
        Rencana Penarikan Bagian {{$uraianbagian}} Satker Setjen untuk periode sampai dengan Bulan {{$uraianbulan}} adalah sebesar
        {{ number_format($pokikpasdbulanberjalan, 0, ',', '.') }}  dari Pagu sebesar {{ number_format($paguanggaransetjen, 0, ',', '.') }} atau sebesar {{ number_format($rencanasetjensdbulanberjalan, 2, ',', '.') }}% dari pagu.
        Terdapat Deviasi sebesar {{ number_format($deviasisetjenbulanberjalan, 0, ',', '.') }} atau {{ number_format($prosentasedeviasisetjenbulanberjalan, 2, ',', '.') }}% jika dibandingkan dengan Realisasi sampai dengan Bulan berjalan.
    </p>
    <p style="text-align: justify;">
        Grafik Perkembangan Realisasi VS Rencana Satker Setjen dapat digambarkan sebagai berikut:
    </p>
    <canvas id="mixedChartDeviasiSetjen"></canvas>
</div>

<!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxSetjen = document.getElementById('mixedChartRealisasiSetjen').getContext('2d');
    var ctxDewan = document.getElementById('mixedChartRealisasiDewan').getContext('2d');
    var ctxDeviasiSetjen = document.getElementById('mixedChartDeviasiSetjen').getContext('2d');

    // Grafik Realisasi Satker Setjen
    var realisasiSetjenArray = Object.values(@json($realisasisetjen)); // Ubah objek menjadi array
    var prosentaseSetjenArray = Object.values(@json($prosentasesetjen)); // Ubah objek menjadi array
    console.log(prosentaseSetjenArray);
    var prosentasesetjensdArray = Object.values(@json($prosentasesetjensdperiodik));
    var prosentaseDeviasiSetjenArray = Object.values(@json($deviasisetjensdperiodik));
    var pokikpasdpersen = Object.values(@json($pokikpasdpersen));
    console.log(pokikpasdpersen);

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
                },
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (%)',
                    data: prosentaseSetjenArray,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
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
            }
        }
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
                },
                {
                    type: 'line',
                    label: 'Rencana Penarikan (%)',
                    data: pokikpasdpersen,
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
            }
        }
    });

    // Grafik Realisasi Satker Dewan
    var realisasiDewanArray = Object.values(@json($realisasidewan)); // Ubah objek menjadi array
    var prosentaseDewanArray = Object.values(@json($prosentasedewan)); // Ubah objek menjadi array
    var prosentasedewansdArray = Object.values(@json($prosentasedewansdperiodik));

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
                },
                {
                    type: 'line',
                    label: 'Realisasi Anggaran (%)',
                    data: prosentaseDewanArray,
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna berbeda
                    tension: 0.1,
                    yAxisID: 'y1',
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
            }
        }
    });
</script>

<!-- Script untuk tombol cetak PDF -->
<script>
    document.getElementById('printButton').addEventListener('click', function () {
        var element = document.querySelector('.content');
        if (!element) {
            console.error('Element .content tidak ditemukan.');
            return;
        } else {
            var opt = {
                margin: 1,
                filename: 'ringkasan_kinerja_keuangan.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 4 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().from(element).set(opt).save();
        }
    });
</script>
</body>
</html>
