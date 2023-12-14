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
    <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(asset('storage/kopsurat/KOP2.png')))}}" width="100%" height="100%"/>
</header>
<center>
    <h5>Pengesahan Daftar Barang Ruangan</h5>
    <br>
</center>
@foreach($datareferensidbr as $drd)
<h6>DBR Untuk Gedung dengan ID {{$idgedung}} ini, Berlokasi pada Area: {{$drd->area}},
    Sub Area: {{$drd->subarea}}, dan Gedung: {{$drd->gedung}}
</h6>
<h7 position="right">Waktu Cetak : {{$waktucetak}}</h7>
@endforeach
<table>
    <thead>
    <tr>
        <td>DBR Ini Telah Disetujui secara elektronik oleh Unit Kerja</td>
    </tr>
    <tr>
        <td colspan="4">Bagian Administrasi BMN</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="4"><img src="{{'data:image/svg+xml;base64,'.base64_encode(file_get_contents(asset('storage/qrbmn/DBRGedung'.$idgedung.'.svg')))}}" /></td>
    </tr>
    <tr>
        <td colspan="4">{{$namapenandatangan}} S</td>
    </tr>
    <tr>
        <td colspan="4">NIP: {{$nippenandatangan}}</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align="center" colspan="13">DBR ini dapat Didownload Seluruhnya pada</td>
    </tr>
    <tr>
        <td align="center" colspan="13"><img src="{{'data:image/svg+xml;base64,'.base64_encode(file_get_contents(asset('storage/qrdbrfinal/DBRGedung'.$idgedung.'.svg')))}}" /></td>
    </tr>
    </tfoot>
</table>
<div class="page-break"></div>

<table class='table table-bordered table-dbr'>
    <thead>
    <tr>
        <td colspan="13" align="center"><h5> DAFTAR BARANG RUANGAN</h5></td>
    </tr>
    <tr>
       <td colspan="13">Lampiran Daftar Barang Ruangan Untuk Seluruh Gedung ID {{$idgedung}}</td>
    </tr>
    </thead>
    <thead>
    <tr>
        <th style="width: 5%">No</th>
        <th style="width: 10%">IDDBR</th>
        <th style="width: 30%">Ruangan</th>
        <th style="width: 30%">Status DBR</th>
        <th style="width: 30%">Last Update</th>
        <th style="width: 30%">IDBarang</th>
        <th style="width: 10%">Kode Barang</th>
        <th style="width: 30%">Uraian Barang</th>
        <th style="width: 5%">No Aset</th>
        <th style="width: 5%">Tahun</th>
        <th style="width: 30%">Merek</th>
        <th style="width: 15%">Status</th>
        <th style="width: 15%">Last Opname</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1
    @endphp
    @foreach($datadetildbr as $ddd)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{$ddd->iddbr}}</td>
            <td>{{$ddd->ruangan}}</td>
            <td>{{$ddd->statusdbr}}</td>
            <td>{{$ddd->terakhiredit}}</td>
            <td>{{$ddd->idbarang}}</td>
            <td>{{$ddd->kd_brg}}</td>
            <td>{{$ddd->uraianbarang}}</td>
            <td>{{$ddd->no_aset}}</td>
            <td>{{$ddd->tahunperolehan}}</td>
            <td>{{$ddd->merek}}</td>
            <td>{{$ddd->statusbarang}}</td>
            <td>{{$ddd->terakhirperiksa}}</td>
        </tr>

    @endforeach
    </tbody>
</table>
</body>
</html>
