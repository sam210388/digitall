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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="rekapbarang">Rekap Barang</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="updatemasamanfaat">Update Masa Manfaat</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="exportbarang">Export Barang</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Barang</span>
                                    <span class="info-box-number">
                                 {{$totalbarang}}
                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dihentikan Penggunaan</span>
                                    <span class="info-box-number">
                                 {{$statushenti}}
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diusulkan Hapus</span>
                                    <span class="info-box-number">
                                 {{$statususul}}
                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sudah Dihapuskan</span>
                                    <span class="info-box-number">
                                 {{$statushapus}}
                            </span>
                                </div>
                            </div>
                        </div>
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
                                <th>Status Barang</th>
                                <th>Status Henti</th>
                                <th>Status Usul</th>
                                <th>Status Hapus</th>
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
                                <th>Status Barang</th>
                                <th>Status Henti</th>
                                <th>Status Usul</th>
                                <th>Status Hapus</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading"></h4>
                            </div>
                            <div class="modal-body">
                                <form id="formexport" name="formexport" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="Area" class="col-sm-6 control-label">Status Barang</label>
                                        <div class="col-sm-12">
                                            <select class="form-control statusbarang" name="statusbarang" id="statusbarang" style="width: 100%;">
                                                <option value="statushenti">Status Henti</option>
                                                <option value="statususul">Status Usul</option>
                                                <option value="statushapus">Status Hapus</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Export
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
            $('#updatemasamanfaat').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Update Masa Manfaat Seluruh Barang?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('updatemasamanfaat')}}";
                }
            });
            $('#exportbarang').click(function (e) {
                e.preventDefault();
                $('#ajaxModel').modal('show');
                $('#saveBtn').html('Export Data');
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Exporting..');
                let statusbarang = document.getElementById('statusbarang').value;
                window.location="{{URL::to('exportdatabarang')}}"+"/"+statusbarang;
                $('#formexport').trigger("reset");
                $('#ajaxModel').modal('hide');
                $('#saveBtn').html('Export Data');

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
                    {data: 'statusdbr', name: 'statusdbr'},
                    {data: 'statushenti', name: 'statushenti'},
                    {data: 'statususul', name: 'statususul'},
                    {data: 'statushapus', name: 'statushapus'}
                ],
            });
            table.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
            // Filter event handler
            $( table.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key === "Enter" || e.key === "Unidentified" || e.keycode === 229){
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );
        });

    </script>
@endsection
