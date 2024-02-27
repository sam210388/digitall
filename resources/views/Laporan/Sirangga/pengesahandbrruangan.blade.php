<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang Ruangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
        @page {
            size: A5 portrait;
            margin: 20px; /* Add margin */
            border: 1px solid black; /* Add border */
        }

        body {
            margin: 0;
            padding: 1.5cm; /* Adjust padding */
            border: 1px solid black; /* Add border */
        }

        header img {
            width: 200px; /* Adjust logo size */
            display: block; /* Center logo */
            margin: 0 auto; /* Center logo */
        }

        header {
            text-align: center; /* Center text */
            padding-bottom: 10px; /* Add space after logo */
            margin-bottom: 10px; /* Add space after logo */
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Collapse table borders */
        }

        td {
            font-size: 9pt;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;

        }

        .right {
            text-align: right;
        }

        .qr-code {
            border: 1px solid black;
            padding: 5px;
            margin: 0 auto; /* Center QR code */
        }
    </style>
</head>
<body>
<header>
    <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(asset('storage/kopsurat/logosetjen.jpg')))}}">
</header>

@foreach($datareferensidbr as $drd)
    <table>
        <tbody>
        <tr>
            <td colspan="3" class="center"><h5>PENGESAHAN</h5></td>
        </tr>
        <tr>
            <td colspan="3" class="center"><h5>DAFTAR BARANG RUANGAN</h5></td>
        </tr>
        <tr>
            <td class="left">ID DBR</td>
            <td style="width: 10px;">:</td>
            <td class="left">{{$drd->iddbr}}</td>
        </tr>
        <tr>
            <td class="left">Gedung</td>
            <td style="width: 10px;">:</td>
            <td class="left">{{$drd->gedung}}</td>
        </tr>
        <tr>
            <td class="left">Lantai</td>
            <td style="width: 10px;">:</td>
            <td class="left">{{$drd->lantai}}</td>
        </tr>
        <tr>
            <td class="left">Ruangan</td>
            <td style="width: 10px;">:</td>
            <td class="left">{{$drd->ruangan}}</td>
        </tr>
        <tr class="penanggungjawab">
            <td class="left">Penanggungjawab</td>
            <td style="width: 10px;">:</td>
            <td class="left">{{$drd->penanggungjawab}}</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="3" class="left" style="padding-top: 20px;"><img class="qr-code" src="{{'data:image/svg+xml;base64,'.base64_encode(file_get_contents(asset('storage/qrdbrfinal/DBR'.$iddbr.'Versike'.$versike.'.svg')))}}" /></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Jakarta, {{$waktucetak}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Bagian Administrasi BMN</td>
        </tr>
        </tbody>
    </table>
@endforeach

</body>
</html>
