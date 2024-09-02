@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{session('status')}}
                            </div>
                        @endif
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
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="exportrealisasiindikatorro">Export Realisasi Output</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="exportrealisasianggaran">Export Realisasi Anggaran</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="normalisasidata">Normalisasi Data</a>
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
                            <label for="bagian" class="col-sm-6 control-label">Biro</label>
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
                                <th>Bagian</th>
                                <th>RO</th>
                                <th>Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Bagian</th>
                                <th>RO</th>
                                <th>Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        $('.idbiro').select2({
            theme: 'bootstrap4',
        })
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
            bsCustomFileInput.init();
            $('.idbulan').select2({
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
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatarealisasiindikatorroadmin','')}}"+"/"+idbulan,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'uraianbagian', name: 'uraianbagian'},
                    {data: 'ro', name: 'ro'},
                    {data: 'indikatorro', name: 'indikatorro'},
                    {data: 'target', name: 'target'},
                    {data: 'jumlah', name: 'jumlah'},
                    {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                    {data: 'prosentase', name: 'prosentase'},
                    {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                    {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                    {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                    {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'statusrealisasi', name: 'statusrealisasi'},
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
                var table = $('#tabelrealisasi').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: false,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarealisasiindikatorroadmin','')}}"+"/"+idbulan,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'uraianbagian', name: 'uraianbagian'},
                        {data: 'ro', name: 'ro'},
                        {data: 'indikatorro', name: 'indikatorro'},
                        {data: 'target', name: 'target'},
                        {data: 'jumlah', name: 'jumlah'},
                        {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                        {data: 'prosentase', name: 'prosentase'},
                        {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                        {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                        {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                        {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                        {data: 'keterangan', name: 'keterangan'},
                        {data: 'statusrealisasi', name: 'statusrealisasi'},
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
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarealisasiindikatorroadmin','')}}"+"/"+idbulan+"/"+idbiro,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'uraianbagian', name: 'uraianbagian'},
                        {data: 'uraianro', name: 'uraianro'},
                        {data: 'indikatorro', name: 'indikatorro'},
                        {data: 'target', name: 'target'},
                        {data: 'jumlah', name: 'jumlah'},
                        {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                        {data: 'prosentase', name: 'prosentase'},
                        {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                        {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                        {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                        {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                        {data: 'keterangan', name: 'keterangan'},
                        {data: 'statusrealisasi', name: 'statusrealisasi'},
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

        $('#exportrealisasiindikatorro').click(function (e) {
            if( confirm("Apakah Anda Yakin Mau Export Data Realisasi?")){
                e.preventDefault();
                $(this).html('Export Data..');
                window.location="{{URL::to('exportrealisasiindikatorro','')}}";
            }
        });
        $('#exportrealisasianggaran').click(function (e) {
            if( confirm("Apakah Anda Yakin Mau Export Data Realisasi?")){
                e.preventDefault();
                $(this).html('Export Data..');
                window.location="{{URL::to('exportrealisasianggaranindikatorro','')}}";
            }
        });

        $('#normalisasidata').click(function (e) {
            let idbulan = dapatkanidbulan();
            if( confirm("Apakah Anda Yakin Mau Melakukan Normalisasi Data Untuk Bulan "+idbulan+"?")){
                e.preventDefault();
                $(this).html('Normalisasi Data..');
                window.location="{{URL::to('normalisasidataindikatoroutput','')}}"+"/"+idbulan;
            }
        });
    </script>

@endsection
