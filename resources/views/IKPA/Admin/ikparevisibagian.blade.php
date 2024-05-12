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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="hitungikpa">Hitung IKPA Revisi</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbagian" name="idbagian" id="idbagian" style="width: 100%;">
                                    <option value="">Pilih Bagian</option>
                                    @foreach($databagian as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraianbagian }}</option>
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
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>Jumlah Revisi POK</th>
                                <th>Jumlah Revisi Kemenkeu</th>
                                <th>Nilai IKPA POK</th>
                                <th>Nilai IKPA Kemenkeu</th>
                                <th>Nilai IKPA Bulanan</th>
                                <th>Nilai IKPA</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>Jumlah Revisi POK</th>
                                <th>Jumlah Revisi Kemenkeu</th>
                                <th>Nilai IKPA POK</th>
                                <th>Nilai IKPA Kemenkeu</th>
                                <th>Nilai IKPA Bulanan</th>
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
            $('.idbagian').select2({
                width: '100%',
                theme: 'bootstrap4',

            })


            // Setup - add a text input to each header cell
            $('#tabelrealisasibagianperpengenal thead th').each( function (i) {
                var title = $('#tabelrealisasibagianperpengenal thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });

            let idbagian = document.getElementById('idbagian').value;
            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdataikparevisibagian','')}}"+"/"+idbagian,
                columns: [
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'periode', name: 'periode'},
                    {data: 'jumlahrevisipok', name: 'jumlahrevisipok'},
                    {data: 'jumlahrevisikemenkeu', name: 'jumlahrevisikemenkeu'},
                    {data: 'nilaiikpapok', name: 'nilaiikpapok'},
                    {data: 'nilaiikpakemenkeu', name: 'nilaiikpakemenkeu'},
                    {data: 'nilaiikpabulanan', name: 'nilaiikpabulanan'},
                    {data: 'nilaiikpa', name: 'nilaiikpa'},
                ],
                columnDefs: [
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


            $('#idbagian').on('change',function (){
                let idbagian = document.getElementById('idbagian').value;
                var table = $('#tabelrealisasibagianperpengenal').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: true,
                    dom: 'lf<"floatright"B>rtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdataikparevisibagian','')}}"+"/"+idbagian,
                    columns: [
                        {data: 'kodesatker', name: 'kodesatker'},
                        {data: 'biro', name: 'birorelation.uraianbiro'},
                        {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                        {data: 'periode', name: 'periode'},
                        {data: 'jumlahrevisipok', name: 'jumlahrevisipok'},
                        {data: 'jumlahrevisikemenkeu', name: 'jumlahrevisikemenkeu'},
                        {data: 'nilaiikpapok', name: 'nilaiikpapok'},
                        {data: 'nilaiikpakemenkeu', name: 'nilaiikpakemenkeu'},
                        {data: 'nilaiikpabulanan', name: 'nilaiikpabulanan'},
                        {data: 'nilaiikpa', name: 'nilaiikpa'},
                    ],
                    columnDefs: [
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
                if( confirm("Apakah Anda Yakin Mau Menghitung IKPA Revisi Sekarang ?")){
                    e.preventDefault();
                    $(this).html('Processing..');
                    window.location="{{URL::to('hitungikparevisibagian')}}";
                }
            });

            $('#exportikpa').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Eksport Data IKPA Revisi ?")){
                    e.preventDefault();
                    $(this).html('Exporting..');
                    window.location="{{URL::to('exportikparevisibagian')}}";
                    $(this).html('Export');
                }
            });
        });

    </script>
@endsection
