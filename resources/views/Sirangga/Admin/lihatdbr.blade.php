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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahbarang">Tambah Barang</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="exportdatabartender">Export Bartender</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">
                                        {{$gedung}}
                                    </span>
                                    <span class="info-box-number">
                                        {{$ruangan}}
                                    </span>
                                </div>
                            </div>
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
                    <div class="modal fade" id="ajaxModel">
                        <div class="modal-dialog" style="max-width: 80%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table id="tabelbarang" class="table table-bordered table-striped tabelbarang">
                                            <thead>
                                            <tr>
                                                <th>IDBarang</th>
                                                <th>KD BRG</th>
                                                <th>Uraian BRG</th>
                                                <th>NUP</th>
                                                <th>Tgl Oleh</th>
                                                <th>Merek</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>IDBarang</th>
                                                <th>KD BRG</th>
                                                <th>Uraian BRG</th>
                                                <th>NUP</th>
                                                <th>Tgl Oleh</th>
                                                <th>Merek</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
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
                ajax:"{{route('getdatadetildbr','')}}"+"/"+iddbr,
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
            $( table.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key == "Enter"){
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );

            $('#ajaxModel').on('shown.bs.modal', function (e) {
                $("#tabelbarang").DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });

            $('#kembalikedatadbr').click(function () {
                window.location="{{URL::to('dbrinduk')}}"
            });

            $('#exportdatabartender').click(function () {
                iddbr = document.getElementById('iddbr').value;
                window.location="{{URL::to('exportdatabartender')}}"+"/"+iddbr;
            });

            $('#tambahbarang').click(function () {
                $('#saveBtn').val("tambah");
                $('#iddbr').val('');
                $('#modelHeading').html("Tambah Barang");
                $('#ajaxModel').modal('show');
            });




            $('#tabelbarang tfoot th').each( function (i) {
                var title = $('#tabelbarang thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var tablebarang = $('.tabelbarang').DataTable({
                scrollX:true,
                scrollY: 200,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax:"{{route('getdatabarangtambah')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'kd_brg', name: 'kd_brg'},
                    {data: 'ur_sskel', name: 'kodebarangrelation.ur_sskel'},
                    {data: 'no_aset', name: 'no_aset'},
                    {data: 'tgl_perlh', name: 'tgl_perlh'},
                    {data: 'merk_type', name: 'merk_type'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
            });
            tablebarang.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', tablebarang.table().container() ) );
            // Filter event handler
            $( tablebarang.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key == "Enter"){
                    tablebarang
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );

            $('body').on('click', '.pilihbarang', function () {
                var idbarang = $(this).data('id');
                $.ajax({
                    url: "{{url('insertbarangdipilih')}}",
                    type: "POST",
                    data: {
                        idbarang: idbarang,
                        iddbr: iddbr,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (data) {
                        //$('#ajaxModel').modal('hide');
                        table.draw();
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
            });

            $('body').on('click', '.deletebarang', function () {
                var iddetil = $(this).data('id');
                $.ajax({
                    url: "{{url('deletebarangdipilih')}}",
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
                                text: 'Delete Barang Berhasil',
                                icon: 'success'
                            })
                            table.draw();
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Delete Barang Gagal',
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
            });

            $('body').on('click', '.konfirmasibarang', function () {
                var iddetil = $(this).data('id');
                $.ajax({
                    url: "{{url('konfirmasibarangada')}}",
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
            });
        });

    </script>
@endsection
