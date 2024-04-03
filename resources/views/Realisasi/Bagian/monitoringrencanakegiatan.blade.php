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
                                <span class="info-box-number">
                                Total Rencana setjen: {{number_format($totalrencanasetjen,2,",",".")}}
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
                                <span class="info-box-number">
                                Total Rencana Dewan: {{number_format($totalrencanadewan,2,",",".")}}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasibagianperpengenal"> Export</a>
                            <h3 class="card-title">{{$judul}} | {{{$uraianbagian}}}</h3>
                            <input type="hidden" name="idbagian" id="idbagian" value="{{$idbagian}}">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Pagu Anggaran</th>
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
                                <th>No</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Pagu Anggaran</th>
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
            // Setup - add a text input to each footer cell
            $('#tabelrealisasibagianperpengenal tfoot th').each( function (i) {
                var title = $('#tabelrealisasibagianperpengenal thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            let idbagian = document.getElementById('idbagian').value;
            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getmonitoringrencanakegiatan','')}}"+"/"+idbagian,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'bagian', name: 'bagian.uraianbagian'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'paguanggaran', name: 'paguanggaran'},
                    {data: 'januari', name: 'januari'},
                    {data: 'februari', name: 'februari'},
                    {data: 'maret', name: 'maret'},
                    {data: 'april', name: 'april'},
                    {data: 'mei', name: 'mei'},
                    {data: 'juni', name: 'juni'},
                    {data: 'juli', name: 'juli'},
                    {data: 'agustus', name: 'agustus'},
                    {data: 'september', name: 'september'},
                    {data: 'oktober', name: 'oktober'},
                    {data: 'november', name: 'november'},
                    {data: 'desember', name: 'desember'},
                ],
                columnDefs: [
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 5,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 7,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 8,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 9,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 10,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 11,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 12,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 13,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 14,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 15,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 16,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
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

    </script>
@endsection
