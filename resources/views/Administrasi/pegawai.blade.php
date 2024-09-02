@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="col-sm-6">
                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{session('status')}}
                                </div>
                            @endif
                        </div>
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
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="importpegawai"> Import</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelpegawai" class="table table-bordered table-striped tabelpegawai">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Unit Kerja</th>
                                <th>Sub Unit Kerja</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Unit Kerja</th>
                                <th>Sub Unit Kerja</th>
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
            $('#tabelpegawai tfoot th').each( function (i) {
                var title = $('#tabelpegawai thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelpegawai').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getlistpegawai')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nama', name: 'nama'},
                    {data: 'nip', name: 'nip'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'satker', name: 'satker'},
                    {data: 'subsatker', name: 'subsatker'},
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
            } );


            $('#importpegawai').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Pegawai dari SIAP?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importsiap')}}";
                }
            });
        });


    </script>
@endsection
