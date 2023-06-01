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
                        @if(session('rekapberhasil'))
                            <div class="alert alert-success">
                                {{session('rekapberhasil')}}
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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="importrefstatus"> Import refstatus</a>
                        <h3 class="card-title">{{$judul}}</h3>

                    </div>
                    <div class="card-body">
                        <table id="tabelrefstatus" class="table table-bordered table-striped tabelrefstatus">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Ref</th>
                                <th>Kode Satker</th>
                                <th>Kode History</th>
                                <th>Jenis Revisi</th>
                                <th>Revisi Ke</th>
                                <th>Tanggal DIPA</th>
                                <th>Pagu Belanja</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>ID Ref</th>
                                <th>Kode Satker</th>
                                <th>Kode History</th>
                                <th>Jenis Revisi</th>
                                <th>Revisi Ke</th>
                                <th>Tanggal DIPA</th>
                                <th>Pagu Belanja</th>
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
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'idrefstatus', name: 'idrefstatus'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'kd_sts_history', name: 'kd_sts_history'},
                    {data: 'jenis_revisi', name: 'jenis_revisi'},
                    {data: 'revisi_ke', name: 'revisi_ke'},
                    {data: 'tgl_dipa', name: 'tgl_dipa'},
                    {data: 'pagu_belanja', name: 'pagu_belanja'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
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



            $('body').on('click', '.importanggaran', function () {
                var id = $(this).data('id');
                var kdsatker = id.substr(0,6);
                var kd_sts_history = id.substr(7,3)
                $.ajax({
                    data: {kdsatker: kdsatker, kd_sts_history: kd_sts_history},
                    url: "{{route('checkdataang')}}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if(data == "Ada"){
                            if( confirm("Apakah Anda Yakin Mau Import Ulang Data Anggaran ini?")){
                                $(this).html('Importing..');
                                window.location="{{URL::to('importanggaran')}}"+"/"+kdsatker+"/"+kd_sts_history;
                            }
                        }else{
                            $(this).html('Importing..');
                            window.location="{{URL::to('importanggaran')}}"+"/"+kdsatker+"/"+kd_sts_history;
                        }
                    },
                });
            });
        });

    </script>
@endsection
