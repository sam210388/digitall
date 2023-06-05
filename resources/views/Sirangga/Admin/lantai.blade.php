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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahlantai"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabellantai" class="table table-bordered table-striped tabellantai">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th>Gedung</th>
                                <th>Kode lantai</th>
                                <th>Uraian lantai</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th>Gedung</th>
                                <th>Kode lantai</th>
                                <th>Uraian lantai</th>
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
                                        <form id="formlantai" name="formlantai" class="form-horizontal">
                                            <input type="hidden" name="idsubareaawal" id="idsubareaawal">
                                            <input type="hidden" name="idgedungawal" id="idgedungawal">
                                            <input type="hidden" name="idlantai" id="idlantai">
                                            <div class="form-group">
                                                <label for="Area" class="col-sm-6 control-label">Area</label>
                                                <div class="col-sm-12">
                                                <select class="form-control idarea" name="idarea" id="idarea" style="width: 100%;">
                                                    <option>Pilih Area</option>
                                                    @foreach($dataarea as $data)
                                                        <option value="{{ $data->id }}">{{ $data->uraianarea }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Subarea" class="col-sm-6 control-label">Sub Area</label>
                                                <div class="col-sm-12">
                                                <select class="form-control idsubarea" name="idsubarea" id="idsubarea" style="width: 100%;">
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Gedung" class="col-sm-6 control-label">Gedung</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idgedung" name="idgedung" id="idgedung" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Kode lantai" class="col-sm-6 control-label">Kode lantai</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="kodelantai" name="kodelantai" placeholder="Masukan Kode lantai" value="" maxlength="4" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian lantai" class="col-sm-6 control-label">Uraian lantai</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianlantai" name="uraianlantai" placeholder="Masukan Uraian Sub Area" value="" maxlength="200" required="">
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan Data
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
            $('.idarea').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.idsubarea').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.idgedung').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabellantai tfoot th').each( function (i) {
                var title = $('#tabellantai thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabellantai').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('lantai.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'idarea', name: 'idarea'},
                    {data: 'idsubarea', name: 'idsubarea'},
                    {data: 'idgedung', name: 'idgedung'},
                    {data: 'kodelantai', name: 'kodelantai'},
                    {data: 'uraianlantai', name: 'uraianlantai'},
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
            Click to Button
            --------------------------------------------
            --------------------------------------------*/
            $('#tambahlantai').click(function () {
                $('#saveBtn').val("tambah");
                $('#idarea').val('');
                $('#idsubarea').val('');
                $('#idgedung').val('');
                $('#formlantai').trigger("reset");
                $('#modelHeading').html("Tambah lantai");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editlantai', function () {
                var idlantai = $(this).data('id');
                $.get("{{ route('lantai.index') }}" +'/' + idlantai +'/edit', function (data) {
                    $('#modelHeading').html("Edit Lantai");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idlantai').val(data.id);
                    $('#idsubareaawal').val(data.idsubarea);
                    $('#idgedungawalal').val(data.idgedung);
                    $('#idarea').val(data.idarea).trigger('change');
                    $('#idsubarea').val(data.idsubarea).trigger('change');
                    $('#idgedung').val(data.idgedung).trigger('change');
                    $('#kodelantai').val(data.kodelantai);
                    $('#uraianlantai').val(data.uraianlantai);
                })
            });

            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formlantai');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('idlantai').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('lantai.store')}}":"{{route('lantai.update','')}}"+'/'+id,
                    type: "POST",
                    dataType: 'json',
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
                        $('#formlantai').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Simpan Data');
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

                        $('#saveBtn').html('Simpan Data');
                    },
                });
            });

            /*------------------------------------------
            --------------------------------------------
            Delete Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.deletelantai', function () {

                var idlantai = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('lantai.destroy','') }}"+'/'+idlantai,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Dihapus',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Hapus Data Gagal',
                                    icon: 'error'
                                })
                            }
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

                            $('#saveBtn').html('Simpan Data');
                        },
                    });
                }
            });

            $('#idarea').on('change', function () {
                var idarea = this.value;

                $.ajax({
                    url: "{{url('ambildatasubarea')}}",
                    type: "POST",
                    data: {
                        idarea: idarea,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var idsubarea = document.getElementById('idsubareaawal').value;
                        $('#idsubarea').html('<option value="">Pilih Sub Area</option>');
                        $.each(result.subarea, function (key, value) {
                            if (idsubarea == value.id) {
                                $('select[name="idsubarea"]').append('<option value="'+value.id+'" selected>'+value.uraiansubarea+'</option>').trigger('change')
                            }else{
                                $("#idsubarea").append('<option value="' + value.id + '">' + value.uraiansubarea + '</option>');
                            }

                        });
                    }

                });
            });
            $('#idsubarea').on('change', function () {
                var idsubarea = this.value;

                $.ajax({
                    url: "{{url('ambildatagedung')}}",
                    type: "POST",
                    data: {
                        idsubarea: idsubarea,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var idgedung = document.getElementById('idgedungawal').value;
                        $('#idgedung').html('<option value="">Pilih Gedung</option>');
                        $.each(result.gedung, function (key, value) {
                            if (idsubarea == value.id) {
                                $('select[name="idgedung"]').append('<option value="'+value.id+'" selected>'+value.uraiangedung+'</option>').trigger('change')
                            }else{
                                $("#idgedung").append('<option value="' + value.id + '">' + value.uraiangedung + '</option>');
                            }

                        });
                    }

                });
            });

        });

    </script>
@endsection
