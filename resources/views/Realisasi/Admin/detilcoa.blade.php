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
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="kembali">Kembali</a>
                            <input type="hidden"  id="ID_SPP" value="{{$ID_SPP}}">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelpengeluaran" class="table table-bordered table-striped tabelpengeluaran">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>ID SPP</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>ID SPP</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-body">
                        <table id="tabelpotongan" class="table table-bordered table-striped tabelpotongan">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>ID SPP</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>ID SPP</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
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
            $('#tabelpengeluaran tfoot th').each( function (i) {
                var title = $('#tabelpengeluaran thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            let ID_SPP = document.getElementById('ID_SPP').value;
            var table = $('.tabelpengeluaran').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','csv','print'],
                ajax:"{{route('getlistpengeluaran','')}}"+"/"+ID_SPP,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'ID_SPP', name: 'ID_SPP'},
                    {data: 'KODE_COA', name: 'KODE_COA'},
                    {data: 'NILAI_AKUN_PENGELUARAN', name: 'NILAI_AKUN_PENGELUARAN'},
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

            // Setup - add a text input to each footer cell
            $('#tabelpotongan tfoot th').each( function (i) {
                var title = $('#tabelpotongan thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table2 = $('.tabelpotongan').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getlistpotongan','')}}"+"/"+ID_SPP,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'ID_SPP', name: 'ID_SPP'},
                    {data: 'KODE_COA', name: 'KODE_COA'},
                    {data: 'NILAI_AKUN_POT', name: 'NILAI_AKUN_POT'},
                ],
            });
            table2.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table2.table().container() ) );
            // Filter event handler
            $( table2.table().container() ).on( 'keyup', 'tfoot input', function () {
                table
                    .column( $(this).data('index') )
                    .search( this.value )
                    .draw();
            });

            $('#kembali').click(function (e) {
                window.location="{{URL::to('sppheader')}}";
            });
        });
    </script>

@endsection
