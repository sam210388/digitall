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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtemuan"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeltemuan" class="table table-bordered table-striped tabeltemuan">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Anggaran</th>
                                <th>Kondisi</th>
                                <th>Kriteria</th>
                                <th>Sebab</th>
                                <th>Akibat</th>
                                <th>Nilai</th>
                                <th>Rekomendasi</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Didata Oleh</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tahun Anggaran</th>
                                <th>Kondisi</th>
                                <th>Kriteria</th>
                                <th>Sebab</th>
                                <th>Akibat</th>
                                <th>Nilai</th>
                                <th>Rekomendasi</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Didata Oleh</th>
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
            $('#tabeltemuan tfoot th').each( function (i) {
                var title = $('#tabeltemuan thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeltemuan').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('temuanbpkbagian.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kondisi', name: 'kondisi'},
                    {data: 'kriteria', name: 'kriteria'},
                    {data: 'sebab', name: 'sebab'},
                    {data: 'akibat', name: 'akibat'},
                    {data: 'nilai', name: 'nilai'},
                    {data: 'rekomendasi', name: 'rekomendasi'},
                    {data: 'bukti', name: 'bukti'},
                    {data: 'status', name: 'status'},
                    {data: 'created_by', name: 'created_by'},

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
            });

            $('body').on('click', '.tindaklanjut', function () {
                var idtemuan = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Menambahkan Data Tindaklanjut Pada Temuan Ini?")){
                    window.location.href = "{{route('tindaklanjutbagian','')}}"+"/"+idtemuan;
                }
            });
        });

    </script>
@endsection
