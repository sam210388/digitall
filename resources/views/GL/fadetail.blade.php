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
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="Area" class="col-sm-6 control-label">Pilih Satker</label>
                            <div class="col-sm-12">
                                <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;">
                                    <option value="001012">Setjen</option>
                                    <option value="001030">Dewan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Area" class="col-sm-6 control-label">Pilih Periode</label>
                            <div class="col-sm-12">
                                <select class="form-control periode" name="periode" id="periode" style="width: 100%;">
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-success" id="importButton" value="create">Import</button>
                            <button type="button" class="btn btn-primary" id="exportButton" value="create">Export</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelfadetail" class="table table-bordered table-striped tabelfadetail">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Deskripsi Transaksi</th>
                                <th>Jenis Dokumen</th>
                                <th>Kode COA</th>
                                <th>Periode</th>
                                <th>Nilai Rupiah</th>
                                <th>No DOK</th>
                                <th>TGL DOK</th>
                                <th>TGL Jurnal</th>
                                <th>Kode Modul</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Deskripsi Transaksi</th>
                                <th>Jenis Dokumen</th>
                                <th>Kode COA</th>
                                <th>Periode</th>
                                <th>Nilai Rupiah</th>
                                <th>No DOK</th>
                                <th>TGL DOK</th>
                                <th>TGL Jurnal</th>
                                <th>Kode Modul</th>
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
            $('.periode').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelfadetail tfoot th').each( function (i) {
                var title = $('#tabelfadetail thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelfadetail').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('fadetail')}}",
                columns: [
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'DESKRIPSI_TRANS', name: 'tahunanggaran'},
                    {data: 'JENIS_DOKUMEN', name: 'JENIS_DOKUMEN'},
                    {data: 'KODE_COA', name: 'KODE_COA'},
                    {data: 'KODE_PERIODE', name: 'KODE_PERIODE'},
                    {data: 'NILAI_RUPIAH', name: 'NILAI_RUPIAH'},
                    {data: 'NO_DOK', name: 'NO_DOK'},
                    {data: 'TGL_DOK', name: 'TGL_DOK'},
                    {data: 'TGL_JURNAL', name: 'TGL_JURNAL'},
                    {data: 'ID_TRN_MODUL', name: 'ID_TRN_MODUL'},
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


            $('#importButton').click(function (e) {
                e.preventDefault();
                var periode = document.getElementById('periode').value;
                var kdsatker = document.getElementById('kdsatker').value;
                if( confirm("Apakah Anda Yakin Mau Import Fa Detail Periode "+periode+" Untuk Kode Satker "+kdsatker+" ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importfadetail','','')}}"+"/"+kdsatker+"/"+periode;
                }
            });

            $('#exportButton').click(function (e) {
                e.preventDefault();
                var periode = document.getElementById('periode').value;
                var kdsatker = document.getElementById('kdsatker').value;
                if( confirm("Apakah Anda Yakin Mau Export Fa Detail Periode "+periode+" Untuk Kode Satker "+kdsatker+" ?")){
                    e.preventDefault();
                    $(this).html('Exporting..');
                    window.location="{{URL::to('exportfadetail','','')}}"+"/"+kdsatker+"/"+periode;
                }
            });
        });
    </script>

@endsection
