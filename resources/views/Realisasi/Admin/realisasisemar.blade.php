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
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="importrealisasisemar"> Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasi" class="table table-bordered table-striped tabelrealisasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>No SPBy</th>
                                <th>No SPP</th>
                                <th>Tanggal SPBy/SPP</th>
                                <th>No SP2D</th>
                                <th>Tanggal SP2D</th>
                                <th>Pengenal</th>
                                <th>Pekerjaan</th>
                                <th>Nilai</th>
                                <th>Tahapan</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>No SPBy</th>
                                <th>No SPP</th>
                                <th>Tanggal SPBy/SPP</th>
                                <th>No SP2D</th>
                                <th>Tanggal SP2D</th>
                                <th>Pengenal</th>
                                <th>Pekerjaan</th>
                                <th>Nilai</th>
                                <th>Tahapan</th>
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
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('realisasisemar')}}",
                columns: [
                    {data: 'id', name:'id'},
                    {data: 'idbiro', name: 'idbiro'},
                    {data: 'idbagian', name: 'idbagian'},
                    {data: 'no_spby', name: 'no_spby'},
                    {data: 'no_spp', name: 'no_spp'},
                    {data: 'tanggal_spp_spby', name: 'tanggal_spp_spby'},
                    {data: 'no_sp2d', name: 'no_sp2d'},
                    {data: 'tanggal_sp2d', name: 'tanggal_sp2d'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'uraian_pekerjaan', name: 'uraian_pekerjaan'},
                    {data: 'nilai_tagihan', name: 'nilai_tagihan'},
                    {data: 'tahapan', name: 'tahapan'},
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

            $('#importrealisasisemar').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Realisasi SEMAR?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importrealisasisemar')}}";
                }
            });

        });
    </script>

@endsection
