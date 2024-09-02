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
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasibagianperpengenal"> Export</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="Update Pagu">Update Pagu</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Pengenal</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pagu</th>
                                <th>Rencana TW I</th>
                                <th>Rencana TW II</th>
                                <th>Rencana TW III</th>
                                <th>Rencana TW IV</th>
                                <th>Realisasi SD TW I</th>
                                <th>Realisasi SD TW II</th>
                                <th>Realisasi SD TW III</th>
                                <th>Realisasi SD TW IV</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Pengenal</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pagu</th>
                                <th>Rencana TW I</th>
                                <th>Rencana TW II</th>
                                <th>Rencana TW III</th>
                                <th>Rencana TW IV</th>
                                <th>Realisasi SD TW I</th>
                                <th>Realisasi SD TW II</th>
                                <th>Realisasi SD TW III</th>
                                <th>Realisasi SD TW IV</th>
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
                ajax:"{{route('getdetilrealisasi','')}}",
                columns: [
                    {data: 'kdsatker', name: 'a.kdsatker'},
                    {data: 'biro', name: 'c.uraianbiro'},
                    {data: 'bagian', name: 'd.uraianbagian'},
                    {data: 'pengenal', name: 'a.pengenal'},
                    {data: 'nilai', name: 'a.NILAI_AKUN_PENGELUARAN'},
                    {data: 'no_spm', name: 'b.NO_SPM'},
                    {data: 'tgl_spm', name: 'b.TGL_SPM'},
                    {data: 'no_sp2d', name: 'b.NO_SP2D'},
                    {data: 'tgl_sp2d', name: 'b.TGL_SP2D'},
                    {data: 'uraian', name: 'b.URAIAN'},
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
            window.location="{{URL::to('exportdetilrealisasi')}}";
        });

    </script>
@endsection
