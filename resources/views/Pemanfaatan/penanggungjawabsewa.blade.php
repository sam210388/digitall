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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahpenanggungjawab"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelpenanggungjawab" class="table table-bordered table-striped tabelpenanggungjawab">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Penanggungjawab</th>
                                <th>Nomor KTP</th>
                                <th>File KTP</th>
                                <th>Jabatan</th>
                                <th>Dasar Jabatan</th>
                                <th>Tanggal Dasar</th>
                                <th>File SK</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama Penanggungjawab</th>
                                <th>Nomor KTP</th>
                                <th>File KTP</th>
                                <th>Jabatan</th>
                                <th>Dasar Jabatan</th>
                                <th>Tanggal Dasar</th>
                                <th>File SK</th>
                                <th>Lokasi</th>
                                <th>Status</th>
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
                                        <form id="formpenanggungjawab" name="formpenanggungjawab" class="form-horizontal">
                                            <input type="hidden" name="filektpawal" id="filektpawal">
                                            <input type="hidden" name="fileskawal" id="fileskawal">
                                            <input type="hidden" name="idpenanggungjawab" id="idpenanggungjawab">
                                            <div class="form-group">
                                                <label for="statuspenyewa" class="col-sm-6 control-label">User Penyewa</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control userpenyewa" name="userpenyewa" id="userpenyewa" style="width: 100%;">
                                                        <option value="">Pilih User Penyewa</option>
                                                        @foreach($user as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Penyewa</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idpenyewa" name="idpenyewa" id="idpenyewa" style="width: 100%;">
                                                        <option value="">Pilih Penyewa</option>
                                                        @foreach($penyewa as $data)
                                                            <option value="{{ $data->id }}">{{ $data->namapenyewa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="namapenanggungjawab" class="col-sm-6 control-label">Nama Penanggungjawab</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="namapenanggungjawab" name="namapenanggungjawab" placeholder="Masukan Nama Penanggungjawab" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="noktp" class="col-sm-6 control-label">No KTP</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="nomorktp" name="nomorktp" placeholder="Masukan No KTP" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File KTP</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf" class="custom-file-input" id="filektp" name="filektp">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilektp" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallihatktp">Lihat KTP</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jabatan" class="col-sm-6 control-label">Jabatan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukan Jabatan" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="dasarjabatan" class="col-sm-6 control-label">Dasar Jabatan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="dasarjabatan" name="dasarjabatan" placeholder="Masukan Dasar Jabatan" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanggal Dasar</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="tanggaldasar" id="tanggaldasar" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File SK</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf" class="custom-file-input" id="filesk" name="filektp">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilesk" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallihatsk">Lihat SK</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kedudukan" class="col-sm-6 control-label">Lokasi</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Masukan Lokasi Penetapan" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="statuspenanggungjawab" class="col-sm-6 control-label">Status Penanggungjawab</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control statuspenanggungjawab" name="statuspenanggungjawab" id="statuspenanggungjawab" style="width: 100%;">
                                                        <option value="Inaktif">Inaktif</option>
                                                        <option value="Aktif">Aktif</option>
                                                    </select>
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
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            bsCustomFileInput.init();
            $('.statuspenanggungjawab').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.userpenyewa').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            $('.idpenyewa').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelpenanggungjawab tfoot th').each( function (i) {
                var title = $('#tabelpenanggungjawab thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelpenanggungjawab').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatapenanggungjawabsewa')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'namapenanggungjawab', name: 'namapenanggungjawab'},
                    {data: 'nomorktp', name: 'nomorktp'},
                    {data: 'filektp', name: 'filektp'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'dasarjabatan', name: 'dasarjabatan'},
                    {data: 'tanggaldasar', name: 'tanggaldasar'},
                    {data: 'filesk', name: 'filesk'},
                    {data: 'lokasi', name: 'lokasi'},
                    {data: 'status', name: 'status'},
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
            $('#tambahpenanggungjawab').click(function () {
                $('#saveBtn').val("tambah");
                $('#idpenyewa').val('');
                $('#filektpawal').val('');
                $('#fileskawal').val('');
                $('#modelHeading').html("Tambah Penanggungjawab");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editpenanggungjawab', function () {
                var id = $(this).data('id');
                $.get("{{ route('penanggungjawabsewa.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Penanggungjawab");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idpenanggungjawab').val(data.id);
                    $('#filektpawal').val(data.filektp);
                    $('#fileskawal').val(data.filesk);
                    $('#namapenanggungjawab').val(data.namapenanggungjawab);
                    $('#nomorktp').val(data.nomorktp);
                    $('#jabatan').val(data.jabatan);
                    $('#dasarjabatan').val(data.dasarjabatan);
                    $('#tanggaldasar').val(data.tanggaldasar);
                    $('#lokasi').val(data.lokasi);
                    $('#status').val(data.status).trigger('change');
                    $('#userpenyewa').val(data.userpenyewa).trigger('change');
                    document.getElementById('aktuallihatktp').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/ktp')}}"+"/"+data.filektp
                    document.getElementById('aktuallihatsk').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/skpenanggungjawab')}}"+"/"+data.filesk
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
                let form = document.getElementById('formpenanggungjawab');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var idpenanggungjawab = document.getElementById('idpenanggungjawab').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('penanggungjawabsewa.store')}}":"{{route('penanggungjawabsewa.update','')}}"+'/'+idpenanggungjawab,
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
                        $('#formpenanggungjawab').trigger("reset");
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
            $('body').on('click', '.deletepenanggungjawab', function () {

                var idpenanggungjawab = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('penanggungjawabsewa.destroy','') }}"+'/'+idpenanggungjawab,
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
        });

    </script>
@endsection
