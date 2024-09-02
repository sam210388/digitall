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
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportikpa">Export</a>
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="hitungikpa">Rekap IKPA</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;">
                                    <option value="">Pilih Biro</option>
                                    @foreach($databiro as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraianbiro }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Periode</th>
                                <th>IKPA Penyerapan</th>
                                <th>IKPA Deviasi</th>
                                <th>IKPA Penyelesaian</th>
                                <th>IKPA Kontraktual</th>
                                <th>IKPA Revisi</th>
                                <th>IKPA Capaian Output</th>
                                <th>Nilai IKPA</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Periode</th>
                                <th>IKPA Penyerapan</th>
                                <th>IKPA Deviasi</th>
                                <th>IKPA Penyelesaian</th>
                                <th>IKPA Kontraktual</th>
                                <th>IKPA Revisi</th>
                                <th>IKPA Capaian Output</th>
                                <th>Nilai IKPA</th>
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
            $('.idbiro').select2({
                width: '100%',
                theme: 'bootstrap4',

            })


            // Setup - add a text input to each header cell
            $('#tabelrealisasibagianperpengenal thead th').each( function (i) {
                var title = $('#tabelrealisasibagianperpengenal thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });

            let idbiro = document.getElementById('idbiro').value;
            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatarekapikpabiro','')}}"+"/"+idbiro,
                columns: [
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'periode', name: 'periode'},
                    {data: 'ikpapenyerapan', name: 'ikpapenyerapan'},
                    {data: 'ikpadeviasi', name: 'ikpadeviasi'},
                    {data: 'ikpapenyelesaian', name: 'ikpapenyelesaian'},
                    {data: 'ikpakontraktual', name: 'ikpakontraktual'},
                    {data: 'ikparevisi', name: 'ikparevisi'},
                    {data: 'ikpacaput', name: 'ikpacaput'},
                    {data: 'ikpatotal', name: 'ikpatotal'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
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
                ],
            });
            table.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

            // Filter event handler
            $( table.table().container() ).on( 'keyup', 'thead input', function () {
                table
                    .column( $(this).data('index') )
                    .search( this.value )
                    .draw();
            });


            $('#idbiro').on('change',function (){
                let idbiro = document.getElementById('idbiro').value;
                var table = $('#tabelrealisasibagianperpengenal').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: true,
                    dom: 'lf<"floatright"B>rtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarekapikpabiro','')}}"+"/"+idbiro,
                    columns: [
                        {data: 'kodesatker', name: 'kodesatker'},
                        {data: 'biro', name: 'birorelation.uraianbiro'},
                        {data: 'periode', name: 'periode'},
                        {data: 'ikpapenyerapan', name: 'ikpapenyerapan'},
                        {data: 'ikpadeviasi', name: 'ikpadeviasi'},
                        {data: 'ikpapenyelesaian', name: 'ikpapenyelesaian'},
                        {data: 'ikpakontraktual', name: 'ikpakontraktual'},
                        {data: 'ikparevisi', name: 'ikparevisi'},
                        {data: 'ikpacaput', name: 'ikpacaput'},
                        {data: 'ikpatotal', name: 'ikpatotal'},
                    ],
                    columnDefs: [
                        {
                            targets: 3,
                            render: $.fn.dataTable.render.number('.', ',', 2, '')
                        },
                        {
                            targets: 4,
                            render: $.fn.dataTable.render.number('.', ',', 2, '')
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
                    ],
                });
                table.buttons().container()
                    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

                // Filter event handler
                $( table.table().container() ).on( 'keyup', 'thead input', function () {
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                });
            })

            $('#hitungikpa').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Menghitung IKPA Kontraktual Sekarang ? Pastikan Anda telah Melakukan Import Kontrak Header Terbaru dari Menu Detil Kontraktual")){
                    e.preventDefault();
                    $(this).html('Processing..');
                    window.location="{{URL::to('hitungrekapikpabiro')}}";
                }
            });

            $('#exportikpa').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Eksport Data Rekap IKPA Biro ?")){
                    e.preventDefault();
                    $(this).html('Exporting..');
                    window.location="{{URL::to('exportrekapikpabiro')}}";
                    $(this).html('Export');
                }
            });
        });

    </script>
@endsection
