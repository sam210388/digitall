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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdata"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabeljadwaltutup" class="table table-bordered table-striped tabeljadwaltutup">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Laporan</th>
                                <th>Bulan</th>
                                <th>Jadwal Buka</th>
                                <th>Jadwal Tutup</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Jenis Laporan</th>
                                <th>Bulan</th>
                                <th>Jadwal Buka</th>
                                <th>Jadwal Tutup</th>
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
                                        <form id="formjadwaltutup" name="formjadwaltutup" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="indexjadwal" id="indexjadwal">
                                            <div class="form-group">
                                                <label for="jenislaporan" class="col-sm-6 control-label">Jenis Laporan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control jenislaporan" name="jenislaporan" id="jenislaporan" style="width: 100%;">
                                                        <option value="">Pilih Jenis Laporan</option>
                                                        <option value="1">Tingkat Bagian</option>
                                                        <option value="2">Tingkat Biro</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="idbulan" class="col-sm-6 control-label">Bulan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbulan" name="idbulan" id="idbulan" style="width: 100%;">
                                                        <option value="">Pilih Bulan</option>
                                                        @foreach($databulan as $data)
                                                            <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jadwalbuka" class="col-sm-6 control-label">Tanggal Buka</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control jadwalbuka" id="jadwalbuka" name="jadwalbuka">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jadwaltutup" class="col-sm-6 control-label">Tanggal Tutup</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control jadwaltutup" id="jadwaltutup" name="jadwaltutup">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data
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
    <script type="text/javascript">
        $(function () {
            $( "#jadwalbuka" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $( "#jadwaltutup" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $('.idbulan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.jenislaporan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabeljadwaltutup tfoot th').each( function (i) {
                var title = $('#tabeljadwaltutup thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeljadwaltutup').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('jadwaltutup.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'jenislaporan', name: 'jenislaporan'},
                    {data: 'idbulan', name: 'idbulan'},
                    {data: 'jadwalbuka', name: 'jadwalbuka'},
                    {data: 'jadwaltutup', name: 'jadwaltutup'},
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

        $('#tambahdata').click(function () {
            $('#saveBtn').val("tambah");
            $('#indexjadwal').val('');
            $('#formjadwaltutup').trigger("reset");
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
        });

        /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
        $('body').on('click', '.editjadwal', function () {
            var indexjadwal = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('jadwaltutup.index') }}" +'/' + indexjadwal +'/edit',
                success: function (data) {
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#indexjadwal').val(data[0]['indexjadwal']);
                    $('#idbulan').val(data[0]['idbulan']).trigger('change');
                    $('#jenislaporan').val(data[0]['jenislaporan']).trigger('change');
                    $('#jadwalbuka').val(data[0]['jadwalbuka']).trigger('change');
                    $('#jadwaltutup').val(data[0]['jadwaltutup']).trigger('change');
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
            let form = document.getElementById('formjadwaltutup');
            let fd = new FormData(form);
            let saveBtn = document.getElementById('saveBtn').value;
            var indexjadwal = document.getElementById('indexjadwal').value;
            fd.append('saveBtn',saveBtn)
            if(saveBtn == "edit"){
                fd.append('_method','PUT')
            }
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }
            $.ajax({
                data: fd,
                url: saveBtn === "tambah" ? "{{route('jadwaltutup.store')}}":"{{route('jadwaltutup.update','')}}"+'/'+indexjadwal,
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
                    $('#formjadwaltutup').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#saveBtn').html('Simpan Data');
                    $('#tabeljadwaltutup').DataTable().ajax.reload();

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
        Delete Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deletejadwal', function () {
            var indexjadwal = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('jadwaltutup.destroy','') }}"+"/"+indexjadwal,
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
                        $('#tabeljadwaltutup').DataTable().ajax.reload();
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
    </script>

@endsection
