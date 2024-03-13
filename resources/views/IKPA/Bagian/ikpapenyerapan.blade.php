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
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>Pagu 51</th>
                                <th>Pagu 52</th>
                                <th>Pagu 53</th>
                                <th>Target 51</th>
                                <th>Target 52</th>
                                <th>Target 53</th>
                                <th>Total Pagu</th>
                                <th>Total Target</th>
                                <th>Target % Periode Ini</th>
                                <th>Penyerapan sd Periode Ini</th>
                                <th>% sd Periode Ini</th>
                                <th>Nilai Kinerja Penyerapan TW</th>
                                <th>Nilai IKPA</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>Pagu 51</th>
                                <th>Pagu 52</th>
                                <th>Pagu 53</th>
                                <th>Target 51</th>
                                <th>Target 52</th>
                                <th>Target 53</th>
                                <th>Total Pagu</th>
                                <th>Total Target</th>
                                <th>Target % Periode Ini</th>
                                <th>Penyerapan sd Periode Ini</th>
                                <th>% sd Periode Ini</th>
                                <th>Nilai Kinerja Penyerapan TW</th>
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

            var table = $('.tabelrealisasibagianperpengenal').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatakinerjapenyerapanbagian')}}",
                columns: [
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'periode', name: 'periode'},
                    {data: 'pagu51', name: 'pagu51'},
                    {data: 'pagu52', name: 'pagu52'},
                    {data: 'pagu53', name: 'pagu53'},
                    {data: 'nominaltarget51', name: 'nominaltarget51'},
                    {data: 'nominaltarget52', name: 'nominaltarget52'},
                    {data: 'nominaltarget53', name: 'nominaltarget53'},
                    {data: 'totalpagu', name: 'totalpagu'},
                    {data: 'totalnominaltarget', name: 'totalnominaltarget'},
                    {data: 'targetpersenperiodeini', name: 'targetpersenperiodeini'},
                    {data: 'penyerapansdperiodeini', name: 'penyerapansdperiodeini'},
                    {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                    {data: 'nilaikinerjapenyerapantw', name: 'nilaikinerjapenyerapantw'},
                    {data: 'nilaiikpapenyerapan', name: 'nilaiikpapenyerapan'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
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
