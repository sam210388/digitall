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
                </div>
                <div class="row">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                        <input type="hidden" name="iddbr" id="iddbr" value="{{$iddbr}}">
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembalikedatadbr"> Kembali ke Data DBR</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetildbr" class="table table-bordered table-striped tabeldetildbr">
                            <thead>
                            <tr>
                                <th>IDDetil</th>
                                <th>IDDBR</th>
                                <th>ID BRG</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Tahun Perolehan</th>
                                <th>Merek</th>
                                <th>Status Barang</th>
                                <th>Terakhir Periksa</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>IDDetil</th>
                                <th>IDDBR</th>
                                <th>ID BRG</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Tahun Perolehan</th>
                                <th>Keterangan Barang</th>
                                <th>Status Barang</th>
                                <th>Terakhir Periksa</th>
                                <th>Aksi</th>
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

            $('#tabeldetildbr tfoot th').each( function (i) {
                var title = $('#tabeldetildbr thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var iddbr = document.getElementById('iddbr').value;
            var table = $('.tabeldetildbr').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('bagiangetdatadetildbr','')}}"+"/"+iddbr,
                columns: [
                    {data: 'iddetil', name: 'iddetil'},
                    {data: 'iddbr', name: 'iddbr'},
                    {data: 'idbarang', name: 'idbarang'},
                    {data: 'kd_brg', name: 'kd_brg'},
                    {data: 'uraianbarang', name: 'uraianbarang'},
                    {data: 'no_aset', name: 'no_aset'},
                    {data: 'tahunperolehan', name: 'tahunperolehan'},
                    {data: 'merek', name: 'merek'},
                    {data: 'statusbarang', name: 'statusbarang'},
                    {data: 'terakhirperiksa', name: 'terakhirperiksa'},
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


            $('#kembalikedatadbr').click(function () {
                window.location="{{URL::to('dbrindukbagian')}}"
            });

            $('#exportdatabartender').click(function () {
                iddbr = document.getElementById('iddbr').value;
                window.location="{{URL::to('exportdatabartender')}}"+"/"+iddbr;
            });

            $('body').on('click', '.konfirmasiada', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Benar Benar Ada dan Dalam Penguasaan Anda?")){
                    $.ajax({
                        url: "{{url('bagiankonfirmbarangada')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmas Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            tablebarang.draw();
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if(xhr.responseJSON.errors){
                                var errorsArr = [];
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorsArr.push(value);
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorsArr,
                                    icon: 'error'
                                })
                            }else{
                                var jsonValue = jQuery.parseJSON(xhr.responseText);
                                Swal.fire({
                                    title: 'Error!',
                                    text: jsonValue.message,
                                    icon: 'error'
                                })
                            }
                        },
                    });
                }

            });

            $('body').on('click', '.konfirmasitidakada', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Benar Benar Tidak Ada dan Tidak Dalam Penguasaan Anda?")){
                    $.ajax({
                        url: "{{url('bagiankonfirmbarangtidakada')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmasi Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            tablebarang.draw();
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if(xhr.responseJSON.errors){
                                var errorsArr = [];
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorsArr.push(value);
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorsArr,
                                    icon: 'error'
                                })
                            }else{
                                var jsonValue = jQuery.parseJSON(xhr.responseText);
                                Swal.fire({
                                    title: 'Error!',
                                    text: jsonValue.message,
                                    icon: 'error'
                                })
                            }
                        },
                    });
                }
            });

            $('body').on('click', '.konfirmasihilang', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Benar Benar Hilang dan Tidak Dalam Penguasaan Anda Lagi?")){
                    $.ajax({
                        url: "{{url('bagiankonfirmbaranghilang')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmasi Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            tablebarang.draw();
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if(xhr.responseJSON.errors){
                                var errorsArr = [];
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorsArr.push(value);
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorsArr,
                                    icon: 'error'
                                })
                            }else{
                                var jsonValue = jQuery.parseJSON(xhr.responseText);
                                Swal.fire({
                                    title: 'Error!',
                                    text: jsonValue.message,
                                    icon: 'error'
                                })
                            }
                        },
                    });
                }
            });

            $('body').on('click', '.pemeliharaan', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Mengalami Kerusakan Ringan/Sedang dan Dapat Dipergunakan Kembali dengan Perbaikan Ringan Hingga Menengah?")){
                    $.ajax({
                        url: "{{url('bagiankonfirmpemeliharaan')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmasi Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            tablebarang.draw();
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if(xhr.responseJSON.errors){
                                var errorsArr = [];
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorsArr.push(value);
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorsArr,
                                    icon: 'error'
                                })
                            }else{
                                var jsonValue = jQuery.parseJSON(xhr.responseText);
                                Swal.fire({
                                    title: 'Error!',
                                    text: jsonValue.message,
                                    icon: 'error'
                                })
                            }
                        },
                    });
                }
            });

            $('body').on('click', '.pengembalian', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Mengalami Kerusakan Ringan/Sedang dan Dapat Dipergunakan Kembali dengan Perbaikan Ringan Hingga Menengah?")){
                    $.ajax({
                        url: "{{url('bagiankonfirmpengembalian')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmasi Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            tablebarang.draw();
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if(xhr.responseJSON.errors){
                                var errorsArr = [];
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorsArr.push(value);
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorsArr,
                                    icon: 'error'
                                })
                            }else{
                                var jsonValue = jQuery.parseJSON(xhr.responseText);
                                Swal.fire({
                                    title: 'Error!',
                                    text: jsonValue.message,
                                    icon: 'error'
                                })
                            }
                        },
                    });
                }
            });
        });

    </script>
@endsection
