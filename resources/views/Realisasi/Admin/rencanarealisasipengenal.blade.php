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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="exportrealisasiperpengenal"> Export</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="rekaprencana"> Rekap Rencana</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbulan" name="idbulan" id="idbulan" style="width: 100%;">
                                    <option value="">Pilih Bulan</option>
                                    @foreach($databulan as $data)
                                        <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasiperpengenal" class="table table-bordered table-striped tabelrealisasiperpengenal">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>Pengenal</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pagu Anggaran</th>
                                <th>Rencana</th>
                                <th>Realisasi</th>
                                <th>Prosentase Realisasi</th>
                                <th>Prosentase GAP</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Satker</th>
                                <th>Pengenal</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Pagu Anggaran</th>
                                <th>Rencana</th>
                                <th>Realisasi</th>
                                <th>Prosentase Realisasi</th>
                                <th>Prosentase GAP</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        function dapatkanidbulan(){
            let idbulan = document.getElementById('idbulan').value;
            if(idbulan === ""){
                date = new Date();
                nilaibulan = date.getMonth();
                nilaibulan = nilaibulan+1;
                return parseInt(nilaibulan);
            }else{
                nilaibulan = idbulan;
                return parseInt(nilaibulan);
            }
        }

        $(function () {
            bsCustomFileInput.init();
            $('.idbulan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelrealisasiperpengenal tfoot th').each( function (i) {
                var title = $('#tabelrealisasiperpengenal thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            idbulan = dapatkanidbulan();
            var table = $('.tabelrealisasiperpengenal').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getrencanarealisasipengenal','')}}"+"/"+idbulan,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'biro', name: 'biro'},
                    {data: 'bagian', name: 'bagian'},
                    {data: 'pagu', name: 'pagu'},
                    {data: 'rencana', name: 'rencana'},
                    {data: 'realisasi', name: 'realisasi'},
                    {data: 'prosentaserealisasi', name: 'prosentaserealisasi'},
                    {data: 'gap', name: 'gap'},
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
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
                    {
                        targets: 9,
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
            });

            $('#idbulan').on('change',function (){
                let idbulan = dapatkanidbulan();
                var table = $('#tabelrealisasiperpengenal').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: false,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getrencanarealisasipengenal','')}}"+"/"+idbulan,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'kodesatker', name: 'kodesatker'},
                        {data: 'pengenal', name: 'pengenal'},
                        {data: 'biro', name: 'biro'},
                        {data: 'bagian', name: 'bagian'},
                        {data: 'pagu', name: 'pagu'},
                        {data: 'rencana', name: 'rencana'},
                        {data: 'realisasi', name: 'realisasi'},
                        {data: 'prosentaserealisasi', name: 'prosentaserealisasi'},
                        {data: 'gap', name: 'gap'},
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
                            render: $.fn.dataTable.render.number('.', ',', 2, '')
                        },
                        {
                            targets: 9,
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
                });
            })

            $('#exportrealisasiperpengenal').click(function (){
                let idbulan = document.getElementById('idbulan').value;
                if(idbulan === ""){
                    date = new Date();
                    nilaibulan = date.getMonth();
                    nilaibulan = nilaibulan+1;
                }else{
                    nilaibulan = idbulan;
                }
                window.location="{{URL::to('exportrencanarealisasipengenal')}}"+"/"+nilaibulan;
            });

            $('#rekaprencana').click(function () {
                window.location="{{URL::to('rekaprencana')}}";
            });
        });
    </script>

@endsection
