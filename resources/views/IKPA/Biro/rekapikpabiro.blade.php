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
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasibagianperpengenal" class="table table-bordered table-striped tabelrealisasibagianperpengenal">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Periode</th>
                                <th>IKPA Penyerapan</th>
                                <th>IKPA Deviasi</th>
                                <th>IKPA Penyelesaian</th>
                                <th>IKPA Kontraktual</th>
                                <th>Nilai IKPA</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Periode</th>
                                <th>IKPA Penyerapan</th>
                                <th>IKPA Deviasi</th>
                                <th>IKPA Penyelesaian</th>
                                <th>IKPA Kontraktual</th>
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

            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatarekapikpabiroaksesbiro')}}",
                columns: [
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'periode', name: 'periode'},
                    {data: 'ikpapenyerapan', name: 'ikpapenyerapan'},
                    {data: 'ikpadeviasi', name: 'ikpadeviasi'},
                    {data: 'ikpapenyelesaian', name: 'ikpapenyelesaian'},
                    {data: 'ikpakontraktual', name: 'ikpakontraktual'},
                    {data: 'ikpatotal', name: 'ikpatotal'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    },
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
        });

    </script>
@endsection
