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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembali"> Kembali</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="tambahtinjut"> Tambah Tinjut</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <p>Temuan: {{$temuan}}</p>
                    </div>
                    <div class="card-header">
                        <p>Rekomendasi: {{$rekomendasi}}</p>
                    </div>
                    <div class="card-header">
                        <p>Nilai Rekomendasi: {{isset($nilai)}}</p>
                    </div>
                    <div class="card-body">
                        <table id="tabeltindaklanjut" class="table table-bordered table-striped tabeltindaklanjut">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Dok</th>
                                <th>Nomor Dok</th>
                                <th>Nilai Bukti</th>
                                <th>Keterangan</th>
                                <th>Objek Temuan</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Disimpan Oleh</th>
                                <th>Tanggal Simpan</th>
                                <th>Diupdate Oleh</th>
                                <th>Tanggal Update</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Dok</th>
                                <th>Nomor Dok</th>
                                <th>Nilai Bukti</th>
                                <th>Keterangan</th>
                                <th>Objek Temuan</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Disimpan Oleh</th>
                                <th>Tanggal Simpan</th>
                                <th>Diupdate Oleh</th>
                                <th>Tanggal Update</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formpenjelasan" name="formpenjelasan" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="idtindaklanjut" id="idtindaklanjut">
                                            <input type="hidden" name="idrekomendasi" id="idrekomendasi" value="{{$idrekomendasi}}">
                                            <input type="hidden" name="idtemuan" id="idtemuan" value="{{$idtemuan}}">
                                            <div class="form-group">
                                                <label for="penjelasan" class="col-sm-6 control-label">Penjelasan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="penjelasan" name="penjelasan" placeholder="Penjelasan" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group fieldtanggapan">
                                                <label for="tanggapan" class="col-sm-6 control-label">Tanggapan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="tanggapan" name="tanggapan" placeholder="Tanggapan" value="" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10 btnsubmit">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="ajaxModelTinjut" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formtindaklanjut" name="formtindaklanjut" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="idrekomendasi" id="idrekomendasi" value="{{$idrekomendasi}}">
                                            <input type="hidden" name="filelama" id="filelama">
                                            <div class="form-group">
                                                <label for="TanggalDokumen" class="col-sm-6 control-label">Tanggal Dokumen</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control tanggaldokumen" id="tanggaldokumen" name="tanggaldokumen">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="NomorDokumen" class="col-sm-6 control-label">Nomor Dokumen</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="nomordokumen" name="nomordokumen" placeholder="Nomor DOkumen/NTPN" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilaibukti" class="col-sm-6 control-label">Nilai Bukti</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="nilaibukti" name="nilaibukti" placeholder="Nilai Bukti" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan" class="col-sm-6 control-label">Keterangan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="objektemuan" class="col-sm-6 control-label">Objek Temuan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="objektemuan" name="objektemuan" placeholder="Objek Temuan" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="file" name="file">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkbukti" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallinkbukti">Lihat Bukti</a>
                                                </div>
                                            </div>

                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtnTinjut" name="saveBtnTinjut" value="create">Simpan Data
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            bsCustomFileInput.init();
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabeltindaklanjut tfoot th').each( function (i) {
                var title = $('#tabeltindaklanjut thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var idrekomendasi = document.getElementById('idrekomendasi').value;
            var table = $('.tabeltindaklanjut').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                "ajax": {
                    "url": "{{route('getdatatindaklanjutbagian')}}",
                    "type": "POST",
                    "data": function (d){
                        d._token = "{{ csrf_token() }}";
                        d.idrekomendasi = idrekomendasi;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tanggaldokumen', name: 'tanggaldokumen'},
                    {data: 'nomordokumen', name: 'nomordokumen'},
                    {data: 'nilaibukti', name: 'nilaibukti'},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'objektemuan', name: 'objektemuan'},
                    {data: 'file', name: 'file'},
                    {data: 'status', name: 'status'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_by', name: 'updated_by'},
                    {data: 'updated_at', name: 'updated_at'},
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
        });


        $('#kembali').click(function () {
            $idtemuan = document.getElementById('idtemuan').value;
            window.location="{{URL::to('tampilrekomendasi'.'/'.$idtemuan)}}"
        });


        $('body').on('click', '.ditolak', function () {
            document.getElementById('idtindaklanjut').value = $(this).data("id")
            $('#saveBtn').val("tambah");
            $('#formpenjelasan').trigger("reset");
            $('#modelHeading').html("Penjelasan Penolakan");
            $('#ajaxModel').modal('show');
            $('.fieldtanggapan').hide();


        });

        $('body').on('click', '.tanggapan', function () {
            var idtindaklanjut = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('lihattanggapan','') }}" +'/' + idtindaklanjut,
                success: function (data) {
                    $('#modelHeading').html("Tanggapan Unit");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#tanggapan').val(data.tanggapan);
                    $('#penjelasan').val(data.penjelasan);
                    $('#saveBtn').hide();
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

                    $('#saveBtn').html('Simpan Data');
                },
            });
        });

        /*------------------------------------------
        --------------------------------------------
        Save Data
        --------------------------------------------
        --------------------------------------------*/
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            let form = document.getElementById('formpenjelasan');
            let fd = new FormData(form);
            let saveBtn = document.getElementById('saveBtn').value;
            fd.append('saveBtn',saveBtn)
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }
            $.ajax({
                data: fd,
                url: "{{url('simpanpenjelasan')}}",
                type: "POST",
                dataType: 'json',
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "berhasil"){
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Penjelasan Berhasil Disimpan, Data Dikembalikan Ke Unit Kerja',
                            icon: 'success'
                        })
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text: 'Simpan Data Gagal',
                            icon: 'error'
                        })
                    }
                    $('#formtindaklanjut').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#saveBtn').html('Simpan Data');
                    $('#tabeltindaklanjut').DataTable().ajax.reload();

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

                    $('#saveBtn').html('Simpan Data');
                },
            });
        });

        $('body').on('click', '.ajukankebpk', function () {
            var idtindaklanjut = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Kirim Data Ini Ke BPK?")){
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajukankebpk') }}"+"/"+idtindaklanjut,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Data Berhasil Dikirim Ke BPK',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Kirim Data Gagal, Data Tidak Ditemukan',
                                icon: 'error'
                            })
                        }
                        $('#tabeltindaklanjut').DataTable().ajax.reload();
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

                        $('#saveBtn').html('Simpan Data');
                    },
                });
            }
        });



        $('body').on('click', '.selesai', function () {
            var idtindaklanjut = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Merubah Status Data ini Menjadi Selesai?")){
                $.ajax({
                    type: "GET",
                    url: "{{ url('tindaklanjutselesai') }}"+"/"+idtindaklanjut,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Data Berhasil Diubah Status Menjadi Selesai',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Kirim Data Gagal, Data Tidak Ditemukan',
                                icon: 'error'
                            })
                        }
                        $('#tabeltindaklanjut').DataTable().ajax.reload();
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

                        $('#saveBtn').html('Simpan Data');
                    },
                });
            }
        });

        $('body').on('click', '.tddl', function () {
            var idtindaklanjut = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Merubah Status Data ini Menjadi TDDL?")){
                $.ajax({
                    type: "GET",
                    url: "{{ url('tindaklanjuttddl') }}"+"/"+idtindaklanjut,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Data Berhasil Diubah Status Menjadi TDDL',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Kirim Data Gagal, Data Tidak Ditemukan',
                                icon: 'error'
                            })
                        }
                        $('#tabeltindaklanjut').DataTable().ajax.reload();
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

                        $('#saveBtn').html('Simpan Data');
                    },
                });
            }
        });

        //input tinjut history
        $('#tambahtinjut').click(function () {
            $('#saveBtnTinjut').val("tambahtinjut");
            $('#id').val('');
            $('#formtindaklanjut').trigger("reset");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModelTinjut').modal('show');
        });

        $( "#tanggaldokumen" ).datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        ///save btn tinjut
        $('#saveBtnTinjut').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            let form = document.getElementById('formtindaklanjut');
            let fd = new FormData(form);
            let file = $('#file')[0].files;
            let saveBtn = document.getElementById('saveBtnTinjut').value;
            var id = document.getElementById('id').value;
            fd.append('file',file[0])
            fd.append('saveBtnTinjut',saveBtn)
            if(saveBtn == "edittinjut"){
                fd.append('_method','PUT')
            }
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }
            $.ajax({
                data: fd,
                url: saveBtn === "tambahtinjut" ? "{{route('simpantinjuthistory')}}":"{{route('updatetinjuthistory','')}}"+'/'+id,
                type: "POST",
                dataType: 'json',
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "berhasil"){
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Simpan Data Berhasil',
                            icon: 'success'
                        })
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text: 'Simpan Data Gagal',
                            icon: 'error'
                        })
                    }
                    $('#formtindaklanjut').trigger("reset");
                    $('#ajaxModelTinjut').modal('hide');
                    $('#saveBtnTinjut').html('Simpan Data');
                    $('#tabeltindaklanjut').DataTable().ajax.reload();

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

                    $('#saveBtnTinjut').html('Simpan Data');
                },
            });
        });

        //edit tinjut history
        $('body').on('click', '.edittinjuthistory', function () {
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('edittinjuthistory','') }}" +'/' + id,
                success: function (data) {
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtnTinjut').val("edittinjut");
                    $('#ajaxModelTinjut').modal('show');
                    $('#id').val(data.id);
                    $('#idrekomendasi').val(data.idtemuan);
                    $('#filelama').val(data.file);
                    $('#tanggaldokumen').val(data.tanggaldokumen);
                    $('#nomordokumen').val(data.nomordokumen);
                    $('#nilaibukti').val(data.nilaibukti);
                    $('#keterangan').val(data.keterangan);
                    $('#objektemuan').val(data.objektemuan);
                    document.getElementById('aktuallinkbukti').href = "{{env('APP_URL')."/".asset('storage')}}"+"/"+data.file
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

                    $('#saveBtnTinjut').html('Simpan Data');
                },
            });
        });

        //delete tinjut history
        $('body').on('click', '.deletetinjuthistory', function () {
            var id = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('destroytinjuthistory','') }}"+"/"+id,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Data Berhasil Dihapus ',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Hapus Data Gagal',
                                icon: 'error'
                            })
                        }
                        $('#tabeltindaklanjut').DataTable().ajax.reload();
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

                        $('#saveBtnTinjut').html('Simpan Data');
                    },
                });
            }
        });
    </script>

@endsection
