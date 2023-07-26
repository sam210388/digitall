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
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="importsppheader"> Import</a>
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="importseluruhcoa"> Import COA</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasi" class="table table-bordered table-striped tabelrealisasi">
                            <thead>
                            <tr>
                                <th>ID SPP</th>
                                <th>Satker</th>
                                <th>No SPP</th>
                                <th>Tgl SPP</th>
                                <th>No SPM</th>
                                <th>Tgl SPM</th>
                                <th>No SP2D</th>
                                <th>Tgl SP2D</th>
                                <th>Uraian</th>
                                <th>Nilai</th>
                                <th>Status Nilai</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID SPP</th>
                                <th>Satker</th>
                                <th>No SPP</th>
                                <th>Tgl SPP</th>
                                <th>No SPM</th>
                                <th>Tgl SPM</th>
                                <th>No SP2D</th>
                                <th>Tgl SP2D</th>
                                <th>Uraian</th>
                                <th>Nilai</th>
                                <th>Status Nilai</th>
                                <th>Action</th>
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
            $('#tabelrealisasi tfoot th').each( function (i) {
                var title = $('#tabelrealisasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelrealisasi').DataTable({
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
                    {data: 'ID_SPP', name: 'ID_SPP'},
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'NO_SPP', name: 'NO_SPP'},
                    {data: 'TGL_SPP', name: 'TGL_SPP'},
                    {data: 'NO_SPM', name: 'NO_SPM'},
                    {data: 'TGL_SPM', name: 'TGL_SPM'},
                    {data: 'NO_SP2D', name: 'NO_SP2D'},
                    {data: 'TGL_SP2D', name: 'TGL_SP2D'},
                    {data: 'URAIAN', name: 'URAIAN'},
                    {data: 'NILAI_SP2D', name: 'NILAI_SP2D'},
                    {data: 'statusnilai', name: 'statusnilai'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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

            $('#importsppheader').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import SPP Header?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importsppheader')}}";
                }
            });

            $('#importseluruhcoa').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Seluruh Coa?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importseluruhcoa')}}";
                }
            });

            $('body').on('click', '.detilcoa', function (e) {
                var ID_SPP = $(this).data('id');
                window.location="{{URL::to('lihatcoa')}}"+"/"+ID_SPP;

            });


            $('body').on('click', '.importcoa', function (e) {
                var ID_SPP = $(this).data('id');
                if( confirm("Apakah Anda Yakin Mau Import COA untuk SPP "+ID_SPP+" ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importcoa')}}"+"/"+ID_SPP;
                }
            });

        });
    </script>

@endsection
