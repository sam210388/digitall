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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasi"> Export</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasiperbagian" class="table table-bordered table-striped tabelrealisasiperbagian">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Satker</th>
                                <th>Pagu Anggaran</th>
                                <th>Realisasi</th>
                                <th>Prosentase</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Satker</th>
                                <th>Pagu Anggaran</th>
                                <th>Realisasi</th>
                                <th>Prosentase</th>
                                <th>Aksi</th>
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
            $('#tabelrealisasiperbagian tfoot th').each( function (i) {
                var title = $('#tabelrealisasiperbagian thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelrealisasiperbagian').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('admingetrealisasiperbagian')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'biro', name: 'biro'},
                    {data: 'uraianbagian', name: 'uraianbagian'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'paguanggaran', name: 'paguanggaran'},
                    {data: 'realisasi', name: 'realisasi'},
                    {data: 'prosentase', name: 'prosentase'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 5,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    }
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

            $('body').on('click', '.realisasiperpengenal', function () {
                var idbagian = $(this).data("id");
                window.location="{{URL::to('adminrealisasibagianperpengenal','')}}"+"/"+idbagian;

            });

            $('#exportrealisasi').click(function () {
                window.location="{{URL::to('exportrealisasiperbagian')}}";
            });
        });

    </script>
@endsection
