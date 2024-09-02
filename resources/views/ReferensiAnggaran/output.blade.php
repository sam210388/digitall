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
                            @if(session('datainsert'))
                                <div class="alert alert-success">
                                    {{session('datainsert')}}
                                </div>
                            @endif
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="importoutput"> Import output</a>
                        <h3 class="card-title">{{$judul}}</h3>

                    </div>
                    <div class="card-body">
                        <table id="tabeloutput" class="table table-bordered table-striped tabeloutput">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Anggaran</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tahun Anggaran</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
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
            $('#tabeloutput tfoot th').each( function (i) {
                var title = $('#tabeloutput thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeloutput').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: ['copy','excelHtml5','pdf','csv','print'],
                ajax:"{{route('getlistoutput')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kode', name: 'kode'},
                    {data: 'deskripsi', name: 'deskripsi'},
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

            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#importoutput').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import output?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importoutput')}}";
                }
            });



        });

    </script>
@endsection
