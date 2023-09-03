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
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="importbastkontrak"> Import Kontrak</a>
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="importcoakontrak"> Import COA</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelkontrakheader" class="table table-bordered table-striped tabelkontrakheader">
                            <thead>
                            <tr>
                                <th>IDKontrak</th>
                                <th>Satker</th>
                                <th>Tahun</th>
                                <th>No Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Nilai Kontrak</th>
                                <th>Uraian Kontrak</th>
                                <th>ID Supplier</th>
                                <th>Nama Supplier</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>IDKontrak</th>
                                <th>Satker</th>
                                <th>Tahun</th>
                                <th>No Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Nilai Kontrak</th>
                                <th>Uraian Kontrak</th>
                                <th>ID Supplier</th>
                                <th>Nama Supplier</th>
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
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelkontrakheader tfoot th').each( function (i) {
                var title = $('#tabelkontrakheader thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelkontrakheader').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','csv','print'],
                ajax:"{{route('sppheader')}}",
                columns: [
                    {data: 'ID_KONTRAK', name: 'ID_KONTRAK'},
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'THN_ANG', name: 'NO_SPP'},
                    {data: 'NO_KONTRAK', name: 'NO_KONTRAK'},
                    {data: 'NO_BAST', name: 'NO_BAST'},
                    {data: 'TGL_BAST', name: 'TGL_BAST'},
                    {data: 'KATEGORI_BAST', name: 'KATEGORI_BAST'},
                    {data: 'NILAI_BAST', name: 'NILAI_BAST'},
                    {data: 'NOMOR_DAN_STATUS_SPP', name: 'NOMOR_DAN_STATUS_SPP'},
                    {data: 'JENIS_SPP', name: 'JENIS_SPP'},
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

            $('#importbastkontrak').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import BAST Kontrak Header?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importkontrakheader')}}";
                }
            });

            $('#importcoakontrak').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Seluruh Coa BAST Kontrak?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importcoabastkontrak')}}";
                }
            });
        });
    </script>

@endsection
