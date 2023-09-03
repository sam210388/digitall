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
                <div class="row">
                </div>
                <div class="row">
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="rekapbarang">Rekap Barang</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelbarang" class="table table-bordered table-striped tabelbarang">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>UAKPB</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Merek/Type</th>
                                <th>Tgl Oleh</th>
                                <th>Tgl Catat</th>
                                <th>Kondisi</th>
                                <th>Intra/Ekstra</th>
                                <th>Nilai Aset</th>
                                <th>Status barang</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>UAKPB</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Merek/Type</th>
                                <th>Tgl Oleh</th>
                                <th>Tgl Catat</th>
                                <th>Kondisi</th>
                                <th>Intra/Ekstra</th>
                                <th>Nilai Aset</th>
                                <th>Status barang</th>
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
        $(function () {
            $('#rekapbarang').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Rekap Data Barang?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('rekapbarang')}}";
                }
            });
            $('#tabelbarang tfoot th').each( function (i) {
                var title = $('#tabelbarang thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelbarang').DataTable({
                lengthAdjust: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                ajax:"{{route('getdatabarang')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'kd_lokasi', name: 'kd_lokasi'},
                    {data: 'kd_brg', name: 'kd_brg'},
                    {data: 'ur_sskel', name: 'kodebarangrelation.ur_sskel'},
                    {data: 'no_aset', name: 'no_aset'},
                    {data: 'merk_type', name: 'merk_type'},
                    {data: 'tgl_perlh', name: 'tgl_perlh'},
                    {data: 'tgl_buku', name: 'tgl_buku'},
                    {data: 'kondisi', name: 'kondisi'},
                    {data: 'flag_sap', name: 'flag_sap'},
                    {data: 'rph_aset', name: 'rph_aset'},
                    {data: 'statusdbr', name: 'statusdbr'}
                ],
            });
            table.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
            // Filter event handler
            $( table.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key == "Enter"){
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );
        });

    </script>
@endsection
