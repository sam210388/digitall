@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{$judul}}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="importrealisasirosakti"> Import</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbulan" name="idbulan" id="idbulan" style="width: 100%;">
                                    <option value="">Pilih Bulan</option>
                                    @foreach($databulan as $data)
                                        <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Biro</label>
                            <div class="col-sm-12">
                                <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;">
                                    <option value="">Pilih Biro</option>
                                    @foreach($databiro as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraianbiro }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasi" class="table table-bordered table-striped tabelrealisasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Suboutput</th>
                                <th>Periode</th>
                                <th>Rencana</th>
                                <th>Penambahan Realisasi</th>
                                <th>Total Realisasi</th>
                                <th>Penambahan Proses</th>
                                <th>Total Proses</th>
                                <th>Anggaran</th>
                                <th>Realisasi</th>
                                <th>Gap</th>
                                <th>Penambahan Realisasi DigitALl</th>
                                <th>Total Realisasi DigitALl</th>
                                <th>Penambahan Proses DigitALl</th>
                                <th>Total Proses DigitALl</th>
                                <th>Status Rekon</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Kode Suboutput</th>
                                <th>Periode</th>
                                <th>Rencana</th>
                                <th>Penambahan Realisasi</th>
                                <th>Total Realisasi</th>
                                <th>Penambahan Proses</th>
                                <th>Total Proses</th>
                                <th>Anggaran</th>
                                <th>Realisasi</th>
                                <th>Gap</th>
                                <th>Penambahan Realisasi DigitALl</th>
                                <th>Total Realisasi DigitALl</th>
                                <th>Penambahan Proses DigitALl</th>
                                <th>Total Proses DigitALl</th>
                                <th>Status Rekon</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <script type="text/javascript">
        function dapatkanidbulan(){
            let idbulan = document.getElementById('idbulan').value;
            if(idbulan === ""){
                date = new Date();
                nilaibulan = date.getMonth();
                nilaibulan = nilaibulan+1;
                return parseInt(nilaibulan);
            }else{
                nilaibulan = idbulan;
                return parseInt(nilaibulan);
            }
        }

        $(function () {
            $('.idbulan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idbiro').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelrealisasi tfoot th').each( function (i) {
                var title = $('#tabelrealisasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            idbulan = dapatkanidbulan();
            var table = $('.tabelrealisasi').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: ['copy','excel','csv','print'],
                ajax:"{{route('getdatarealisasirosakti','')}}"+"/"+idbulan,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'KodeSubOutput', name: 'KodeSubOutput'},
                    {data: 'Periode', name: 'Periode'},
                    {data: 'Rencana', name: 'Rencana'},
                    {data: 'RealisasiSaktiPeriodeIni', name: 'RealisasiSaktiPeriodeIni'},
                    {data: 'TotalRealisasiSakti', name: 'TotalRealisasiSakti'},
                    {data: 'ProsentaseSaktiPeriodeIni', name: 'ProsentaseSaktiPeriodeIni'},
                    {data: 'TotalProsentaseSakti', name: 'TotalProsentaseSakti'},
                    {data: 'Anggaran', name: 'Anggaran'},
                    {data: 'Realisasi', name: 'Realisasi'},
                    {data: 'Gap', name: 'Gap'},
                    {data: 'JumlahDigitAll', name: 'JumlahDigitAll'},
                    {data: 'TotalRealisasiDigitAll', name: 'TotalRealisasiDigitAll'},
                    {data: 'ProsentasePeriodeIniDigitAll', name: 'ProsentasePeriodeIniDigitAll'},
                    {data: 'TotalProsentaseDigitAll', name: 'TotalProsentaseDigitAll'},
                    {
                        data: 'StatusRekon',
                        name: 'StatusRekon',
                        orderable: true,
                        searchable: true
                    },
                ],
            });
            table.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
            // Filter event handler
            $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
                table
                    .column( $(this).data('index') )
                    .search( this.value )
                    .draw();
            });

            $('#idbulan').on('change',function (){
                let idbulan = dapatkanidbulan();
                let idbiro = document.getElementById('idbiro').value;
                var table = $('#tabelrealisasi').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: false,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarealisasirosakti','')}}"+"/"+idbulan+"/"+idbiro,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'KodeSubOutput', name: 'KodeSubOutput'},
                        {data: 'Periode', name: 'Periode'},
                        {data: 'Rencana', name: 'Rencana'},
                        {data: 'RealisasiSaktiPeriodeIni', name: 'RealisasiSaktiPeriodeIni'},
                        {data: 'TotalRealisasiSakti', name: 'TotalRealisasiSakti'},
                        {data: 'ProsentaseSaktiPeriodeIni', name: 'ProsentaseSaktiPeriodeIni'},
                        {data: 'TotalProsentaseSakti', name: 'TotalProsentaseSakti'},
                        {data: 'Anggaran', name: 'Anggaran'},
                        {data: 'Realisasi', name: 'Realisasi'},
                        {data: 'Gap', name: 'Gap'},
                        {data: 'JumlahDigitAll', name: 'JumlahDigitAll'},
                        {data: 'TotalRealisasiDigitAll', name: 'TotalRealisasiDigitAll'},
                        {data: 'ProsentasePeriodeIniDigitAll', name: 'ProsentasePeriodeIniDigitAll'},
                        {data: 'TotalProsentaseDigitAll', name: 'TotalProsentaseDigitAll'},
                        {
                            data: 'StatusRekon',
                            name: 'StatusRekon',
                            orderable: true,
                            searchable: true
                        },
                    ],
                });
                table.buttons().container()
                    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
                // Filter event handler
                $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                });
            })

            $('#idbiro').on('change',function (){
                let idbulan = dapatkanidbulan();
                let idbiro = document.getElementById('idbiro').value;
                var table = $('#tabelrealisasi').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: false,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','csv','print'],
                    ajax:"{{route('getdatarealisasirosakti','')}}"+"/"+idbulan+"/"+idbiro,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'KodeSubOutput', name: 'KodeSubOutput'},
                        {data: 'Periode', name: 'Periode'},
                        {data: 'Rencana', name: 'Rencana'},
                        {data: 'RealisasiSaktiPeriodeIni', name: 'RealisasiSaktiPeriodeIni'},
                        {data: 'TotalRealisasiSakti', name: 'TotalRealisasiSakti'},
                        {data: 'ProsentaseSaktiPeriodeIni', name: 'ProsentaseSaktiPeriodeIni'},
                        {data: 'TotalProsentaseSakti', name: 'TotalProsentaseSakti'},
                        {data: 'Anggaran', name: 'Anggaran'},
                        {data: 'Realisasi', name: 'Realisasi'},
                        {data: 'Gap', name: 'Gap'},
                        {data: 'JumlahDigitAll', name: 'JumlahDigitAll'},
                        {data: 'TotalRealisasiDigitAll', name: 'TotalRealisasiDigitAll'},
                        {data: 'ProsentasePeriodeIniDigitAll', name: 'ProsentasePeriodeIniDigitAll'},
                        {data: 'TotalProsentaseDigitAll', name: 'TotalProsentaseDigitAll'},
                        {
                            data: 'StatusRekon',
                            name: 'StatusRekon',
                            orderable: true,
                            searchable: true
                        },
                    ],
                });
                table.buttons().container()
                    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
                // Filter event handler
                $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                });
            })
        });

        $('#importrealisasirosakti').click(function (e) {
            if( confirm("Apakah Anda Yakin Mau Import Realisasi RO dari Mosankti ?")){
                e.preventDefault();
                $(this).html('Importing..');
                window.location="{{URL::to('importrealisasirosakti')}}";
            }
        });
    </script>

@endsection
