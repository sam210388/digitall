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

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportdata"> ExportData </a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="rekaprealisasiberjalan"> Rekap Realisasi </a>

                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelkasbon" class="table table-bordered table-striped tabelkasbon">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Pagu Anggaran</th>
                                <th>Total Rencana</th>
                                <th>Sisa Dialokasikan</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Pagu Anggaran</th>
                                <th>Total Rencana</th>
                                <th>Sisa Dialokasikan</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
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
            $('#tabelkasbon tfoot th').each( function (i) {
                var title = $('#tabelkasbon thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelkasbon').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getmonitoringrencanakegiatanbagian')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'tahunanggaran',name:'tahunanggaran'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name:'bagianrelation.uraianbagian'},
                    {data: 'pengenal', name:'pengenal'},
                    {data: 'paguanggaran', name: 'paguanggaran'},
                    {data: 'totalrencana', name: 'totalrencana'},
                    {data: 'sisadialokasikan', name: 'sisadialokasikan'},
                    {data: 'pok1', name: 'pok1'},
                    {data: 'pok2', name: 'pok2'},
                    {data: 'pok3', name: 'pok3'},
                    {data: 'pok4', name: 'pok4'},
                    {data: 'pok5', name: 'pok5'},
                    {data: 'pok6', name: 'pok6'},
                    {data: 'pok7', name: 'pok7'},
                    {data: 'pok8', name: 'pok8'},
                    {data: 'pok9', name: 'pok9'},
                    {data: 'pok10', name: 'pok10'},
                    {data: 'pok11', name: 'pok11'},
                    {data: 'pok12', name: 'pok12'},
                ],
                columnDefs: [
                    {
                        targets: 5,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 7,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 8,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 9,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 10,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 11,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 12,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 13,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 14,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 15,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 16,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 17,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 18,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 19,
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

            $('#exportdata').click(function () {
                window.location="{{URL::to('exportrencanapenarikanbagian')}}";
            });

            $('#rekaprealisasiberjalan').click(function () {
                window.location="{{URL::to('rekaprealisasiberjalan')}}";
            });
        });

    </script>
@endsection
