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
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-number">
                                Anggaran Setjen: {{number_format($pagusetjen,0,",",".")}}
                            </span>
                                <span class="info-box-number">
                                Realisasi Setjen: {{number_format($realisasisetjen,0,",",".")}}
                            </span>
                                <span class="info-box-number">
                                Prosentase setjen: {{number_format($prosentasesetjen,2,",",".")}}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-number">
                                Anggaran Dewan: {{number_format($pagudewan,0,",",".")}}
                            </span>
                                <span class="info-box-number">
                                Realisasi Dewan: {{number_format($realisasidewan,0,",",".")}}
                            </span>
                                <span class="info-box-number">
                                Prosentase: {{number_format($prosentasedewan,2,",",".")}}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasi">Export</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="importrealisasi">Import Realisasi</a>
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="rekaprealisasiharian">Rekap Realisasi</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                                <th>No SPM</th>
                                <th>TGL SPM</th>
                                <th>No SP2D</th>
                                <th>TGL SP2D</th>
                                <th>Uraian</th>
                                <th>Status Data</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                                <th>No SPM</th>
                                <th>TGL SPM</th>
                                <th>No SP2D</th>
                                <th>TGL SP2D</th>
                                <th>Uraian</th>
                                <th>Status Data</th>
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
            // Setup - add a text input to each footer cell
            $('#tabelrealisasibagianperpengenal tfoot th').each( function (i) {
                var title = $('#tabelrealisasibagianperpengenal thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatarealisasisakti')}}",
                columns: [
                    {data: 'KDSATKER', name: 'KDSATKER'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'PENGENAL', name: 'PENGENAL'},
                    {data: 'NILAI_RUPIAH', name: 'NILAI_RUPIAH'},
                    {data: 'NO_SPM', name: 'NO_SPM'},
                    {data: 'TGL_SPM', name: 'TGL_SPM'},
                    {data: 'NO_SP2D', name: 'NO_SP2D'},
                    {data: 'TGL_SP2D', name: 'TGL_SP2D'},
                    {data: 'URAIAN', name: 'URAIAN'},
                    {data: 'STATUS_DATA', name: 'STATUS_DATA'},
                ],
                columnDefs: [
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
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
            } );
        });

        $('#exportrealisasi').click(function () {
            window.location="{{URL::to('exportrealisasisakti')}}";
        });

        $('#importrealisasi').click(function (e) {
            if( confirm("Apakah Anda Yakin Mau Import Realisasi SAKTI ?")){
                e.preventDefault();
                $(this).html('Importing..');
                window.location="{{URL::to('importrealisasisakti')}}";
            }
        });

        $('#rekaprealisasiharian').click(function (e) {
            if( confirm("Apakah Anda Yakin Mau Rekap Realisasi SAKTI ?")){
                e.preventDefault();
                $(this).html('Merekap..');
                window.location="{{URL::to('rekaprealisasiharian')}}";
            }
        });

    </script>
@endsection
