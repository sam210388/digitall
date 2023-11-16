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
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="rekapbarang">Rekap Barang</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="exportpenghapusanbarang"> Export</a>
                        </div>

                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelbarang" class="table table-bordered table-striped tabelbarang">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Merek/Type</th>
                                <th>Tgl Oleh</th>
                                <th>Tgl Catat</th>
                                <th>Kondisi</th>
                                <th>Intra/Ekstra</th>
                                <th>Nilai Aset</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Merek/Type</th>
                                <th>Tgl Oleh</th>
                                <th>Tgl Catat</th>
                                <th>Kondisi</th>
                                <th>Intra/Ekstra</th>
                                <th>Nilai Aset</th>
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
                if( confirm("Apakah Anda Yakin Mau Rekap Data Penghapusan Barang?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('rekappenghapusanbarang')}}";
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
                ajax:"{{route('getdatapenghapusanbarang')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'kdbrg', name: 'kdbrg'},
                    {data: 'ur_sskel', name: 'kodebarangrelation.ur_sskel'},
                    {data: 'nup', name: 'nup'},
                    {data: 'merek_tipe', name: 'merek_tipe'},
                    {data: 'tgl_oleh', name: 'tgl_oleh'},
                    {data: 'tgl_buku', name: 'tgl_buku'},
                    {data: 'kondisi', name: 'kondisi'},
                    {data: 'jns_aset', name: 'jns_aset'},
                    {data: 'nilaiaset', name: 'nilaiaset'},
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
