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
                        @if(session('prosesimport'))
                            <div class="alert alert-success">
                                {{session('prosesimport')}}
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        @if(session('rekapberhasil'))
                            <div class="alert alert-success">
                                {{session('rekapberhasil')}}
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        @if(session('rekonberhasil'))
                            <div class="alert alert-success">
                                {{session('rekonberhasil')}}
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        @if(session('updatestatusaktif'))
                            <div class="alert alert-success">
                                {{session('updatestatusaktif')}}
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
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
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="importrefstatus"> Import refstatus</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="updatestatusaktif"> Update Status</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>

                    </div>
                    <div class="card-body">
                        <table id="tabelrefstatus" class="table table-bordered table-striped tabelrefstatus">
                            <thead>
                            <tr>
                                <th>ID Ref</th>
                                <th>Kode Satker</th>
                                <th>Kode History</th>
                                <th>Jenis Revisi</th>
                                <th>Revisi Ke</th>
                                <th>Tanggal DIPA</th>
                                <th>Pagu Belanja</th>
                                <th>Pagu DataAng</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID Ref</th>
                                <th>Kode Satker</th>
                                <th>Kode History</th>
                                <th>Jenis Revisi</th>
                                <th>Revisi Ke</th>
                                <th>Tanggal DIPA</th>
                                <th>Pagu Belanja</th>
                                <th>Pagu DataAng</th>
                                <th>Action</th>
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
            $('#tabelrefstatus tfoot th').each( function (i) {
                var title = $('#tabelrefstatus thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelrefstatus').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: ['copy','excelHtml5','pdf','csv','print'],
                ajax:"{{route('getlistrefstatus')}}",
                columns: [
                    {data: 'idrefstatus', name: 'idrefstatus'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'kd_sts_history', name: 'kd_sts_history'},
                    {data: 'jenis_revisi', name: 'jenis_revisi'},
                    {data: 'revisi_ke', name: 'revisi_ke'},
                    {data: 'tgl_revisi', name: 'tgl_revisi'},
                    {data: 'pagu_belanja', name: 'pagu_belanja'},
                    {data: 'pagu_dataang', name: 'pagu_dataang'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                columnDefs: [
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 7,
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

            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#importrefstatus').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import refstatus?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importrefstatus')}}";
                }
            });

            $('#updatestatusaktif').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Update Status ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('updatestatusaktif')}}";
                }
            });



            $('body').on('click', '.importanggaran', function () {
                var idrefstatus = $(this).data('id');
                if( confirm("Apakah Anda Yakin Mau Import Ulang Data Anggaran ini?")){
                    $(this).html('Importing..');
                    window.location="{{URL::to('importanggaran')}}"+"/"+idrefstatus;
                }
            });

            $('body').on('click', '.rekonanggaran', function () {
                var idrefstatus = $(this).data('id');
                if( confirm("Apakah Anda Yakin Mau Rekon Data Anggaran ini?")){
                    $(this).html('Rekon..');
                    window.location="{{URL::to('rekondataang')}}"+"/"+idrefstatus;
                }
            });

            $('body').on('click', '.exportanggaran', function () {
                var idrefstatus = $(this).data('id');
                if( confirm("Apakah Anda Yakin Mau Export Data Anggaran ini?")){
                    $(this).html('Rekon..');
                    window.location="{{URL::to('exportanggaran')}}"+"/"+idrefstatus;
                }
            });
        });

    </script>
@endsection
