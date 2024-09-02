@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="col-sm-6">
                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{session('status')}}
                                </div>
                            @endif
                        </div>
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
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahuser"> Tambah</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeluser" class="table table-bordered table-striped tabeluser">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Deputi</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Gambar</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Deputi</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Gambar</th>
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
                                        <form id="formuser" name="formuser" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="gambarlama" id="gambarlama">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="pnsppnpn" id="pns" value="pns">
                                                        <label class="form-check-label" for="inlineRadio1">PNS</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="pnsppnpn" id="ppnpn" value="ppnpn">
                                                        <label class="form-check-label" for="inlineRadio2">PPNPN/Lainnya</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="col-sm-6 control-label">Username</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control username" name="username" id="username" style="width: 100%;">
                                                        <option value="">Pilih Pegawai</option>
                                                        @foreach($datapegawai as $data)
                                                            <option value="{{ $data->email }}">{{ $data->nama." ".$data->nama_satker }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Name" class="col-sm-6 control-label">Nama</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="email" class="col-sm-6 control-label">Email</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-envelope"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="email" class="col-sm-6 control-label">No Telepon</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="phone" id="phone" name="phone" class="form-control" placeholder="Nomor WA Aktif Tanpa 0 (81312345678)">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-phone"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="password" class="col-sm-6 control-label">Password</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" placeholder="Password">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-lock"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="password-confirm" class="col-sm-6 control-label">Konfirmasi Password</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Retype password" autocomplete="new-password">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-lock"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="gambaruser" class="col-sm-6 control-label">Gambar User</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="gambaruser" name="gambaruser">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="user-panel">
                                                            <div class="image">
                                                                <img class="img-circle elevation-2" alt="User Image" id="gambarusernow">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data</button>
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
            $('.username').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            bsCustomFileInput.init();
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabeluser tfoot th').each( function (i) {
                var title = $('#tabeluser thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeluser').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('kelolauser.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'deputi', name: 'deputi'},
                    {data: 'biro', name: 'biro'},
                    {data: 'bagian', name: 'bagian'},
                    {data: 'gambaruser', name: 'gambaruser'},
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
            $('#tambahuser').click(function () {
                $('#saveBtn').val("tambah");
                document.getElementById('gambarusernow').src ="{{env('APP_URL')."/".asset('storage/gambaruser/default.png')}}";
                $('#id').val('');
                $('#formuser').trigger("reset");
                $('#pns').prop('disabled', false);
                $('#ppnpn').prop('disabled', false);
                $('#username').prop('disabled', false);
                $('#modelHeading').html("Tambah User");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edituser', function () {
                var id = $(this).data('id');
                $.get("{{ route('kelolauser.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit User");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#username').val(data.username).trigger('change');
                    $('#gambarlama').val(data.gambaruser);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    if (data.pnsppnpn == "pns"){
                        $('#pns').prop('checked',true).change();
                        $('#username').prop('readonly', 'readonly');
                        $('#ppnpn').prop('readonly', 'readonly');
                        $('#name').prop('readonly', 'readonly');
                        $('#email').prop('readonly', 'readonly');
                    }else{
                        $('#ppnpn').prop('checked',true).change();
                        $('#pns').prop('readonly', 'readonly');
                        $('#username').prop('readonly', 'readonly');
                    }
                    document.getElementById('gambarusernow').src ="{{env('APP_URL')."/".asset('storage')}}"+"/"+data.gambaruser;
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
                let form = document.getElementById('formuser');
                let fd = new FormData(form);
                let gambaruser = $('#gambaruser')[0].files;
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('id').value;
                fd.append('gambaruser',gambaruser[0])
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('kelolauser.store')}}":"{{route('kelolauser.update','')}}"+'/'+id,
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
                        $('#formuser').trigger("reset");
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
            $('body').on('click', '.deleteuser', function () {
                var iduser = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('kelolauser.destroy','') }}"+"/"+iduser,
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

            $('#username').on('change', function () {
                var email = this.value;

                $.ajax({
                    url: "{{url('ambildatapegawai')}}",
                    type: "POST",
                    data: {
                        email: email,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        document.getElementById('name').value = result[0].nama;
                        document.getElementById('email').value = result[0].email+"@dpr.go.id";

                    }

                });
            });

            $('input[type=radio][name=pnsppnpn]').change(function() {
                if (this.value === 'pns') {
                    $('#username').prop('disabled', false);
                }
                else if (this.value === 'ppnpn') {
                    $('#username').prop('disabled', 'disabled');
                }
            });
        });


    </script>
@endsection
