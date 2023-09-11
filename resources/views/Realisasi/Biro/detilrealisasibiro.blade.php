@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasibagianperpengenal"> Export</a>
                        <h3 class="card-title">{{$judul}} | {{{$uraianbiro}}}</h3>
                        <input type="hidden" name="idbiro" id="idbiro" value="{{$idbiro}}">
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                                <th>No SPM</th>
                                <th>TGL SPM</th>
                                <th>No SP2D</th>
                                <th>TGL SP2D</th>
                                <th>Uraian</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Nilai</th>
                                <th>No SPM</th>
                                <th>TGL SPM</th>
                                <th>No SP2D</th>
                                <th>TGL SP2D</th>
                                <th>Uraian</th>
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
            let idbiro = document.getElementById('idbiro').value;
            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdetilrealisasibiro','')}}"+"/"+idbiro,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name: 'bagian'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'nilai', name: 'nilai'},
                    {data: 'no_spm', name: 'no_spm'},
                    {data: 'tgl_spm', name: 'tgl_spm'},
                    {data: 'no_sp2d', name: 'no_sp2d'},
                    {data: 'tgl_sp2d', name: 'tgl_sp2d'},
                    {data: 'uraian', name: 'uraian'},
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

        $('#exportrealisasibagianperpengenal').click(function () {
            idbiro = document.getElementById('idbiro').value;
            window.location="{{URL::to('exportdetilrealisasibiro','')}}"+"/"+idbiro;
        });

    </script>
@endsection
