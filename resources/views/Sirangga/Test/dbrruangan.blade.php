<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang Ruangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<style type="text/css">
    @page {
        margin: 0cm 0cm;
    }

    table tr td,
    table tr th{
        font-size: 9pt;
    }
    body {
        margin-top: 3.5cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
    }
    header {
        position: fixed;
        top: 0.5cm;
        left: 0cm;
        right: 0cm;
        height: 3cm;
    }
    .page-break {
        page-break-after: always;
    }

    .table-dbr {
        padding-bottom: 70px; //height of your footer
    }

    h6 {
        text-align: justify;
    }

</style>
<header>
    <img src="{{asset('storage/kopsurat/KOP2.png')}}" width="100%" height="100%"/>
</header>
<center>
    <h5>Pengesahan Daftar Barang Ruangan</h5>
    <br>
</center>
<h6>DBR dengan ID
</h6>
<h7 position="right">Waktu Cetak : </h7>
<table>
    <thead>
    <tr>
        <td>DBR Ini Telah Disetujui secara elektronik oleh Unit Kerja</td>
    </tr>
    <tr>
        <td colspan="4">Bagian Administrasi BMN</td>
        <td colspan="3">Penanggungjawab Unit Kerja</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="4"><img src="{{ asset('storage/qrbmn/DBR1.svg')}}" /></td>
        <td colspan="3"><img src="{{ asset('storage/qrunit/DBR1.svg')}}" /></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align="center" colspan="7">DBR ini dapat Didownload Seluruhnya pada</td>
    </tr>
    <tr>
        <td align="center" colspan="7"><img src="{{ asset('storage/qrdbrfinal/DBR1.svg')}}" /></td>
    </tr>
    </tfoot>
</table>
<div class="page-break"></div>

<table class='table table-bordered table-dbr'>
    <thead>
    <tr>
        <td colspan="7" align="center"><h5> DAFTAR BARANG RUANGAN</h5></td>
    </tr>
    <tr>
       <td colspan="7">Lampiran Daftar Barang Ruangan ID {{1}}</td>
    </tr>
    </thead>
    <thead>
    <tr>
        <th style="width: 5%">No</th>
        <th style="width: 10%">Kode Barang</th>
        <th style="width: 30%">Uraian Barang</th>
        <th style="width: 5%">No Aset</th>
        <th style="width: 5%">Tahun</th>
        <th style="width: 30%">Merek</th>
        <th style="width: 15%">Status</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</body>
</html>
