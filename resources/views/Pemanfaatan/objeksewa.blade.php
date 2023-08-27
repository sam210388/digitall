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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahobjeksewa"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelobjeksewa" class="table table-bordered table-striped tabelobjeksewa">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th>Gedung</th>
                                <th>Kode Barang</th>
                                <th>No Aset</th>
                                <th>Deskripsi</th>
                                <th>Luas</th>
                                <th>Luas Terbilang</th>
                                <th>Foto 1</th>
                                <th>Foto 2</th>
                                <th>Foto 3</th>
                                <th>File PSP</th>
                                <th>Dok Kepemilikan</th>
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
                                <th>Kode Barang</th>
                                <th>No Aset</th>
                                <th>Deskripsi</th>
                                <th>Luas</th>
                                <th>Luas Terbilang</th>
                                <th>Foto 1</th>
                                <th>Foto 2</th>
                                <th>Foto 3</th>
                                <th>File PSP</th>
                                <th>Dok Kepemilikan</th>
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
                                        <form id="formobjeksewa" name="formobjeksewa" class="form-horizontal">
                                            <input type="hidden" name="idobjeksewa" id="idobjeksewa">
                                            <input type="hidden" name="idsubareaawal" id="idsubareaawal">
                                            <input type="hidden" name="idgedungawal" id="idgedungawal">
                                            <input type="hidden" name="kodebarangawal" id="kodebarangawal">
                                            <input type="hidden" name="noasetawal" id="noasetawal">
                                            <input type="hidden" name="foto1awal" id="foto1awal">
                                            <input type="hidden" name="foto2awal" id="foto2awal">
                                            <input type="hidden" name="foto3awal" id="foto3awal">
                                            <input type="hidden" name="filepenetapanstatusawal" id="filepenetapanstatusawal">
                                            <input type="hidden" name="dokkepemilikanawal" id="dokkepemilikanawal">
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
                                                <label for="kodebarang" class="col-sm-6 control-label">Kode Barang</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kodebarang" name="kodebarang" id="kodebarang" style="width: 100%;">
                                                        <option>Kode Barang</option>
                                                        @foreach($datakodebarang as $data)
                                                            <option value="{{ $data->kdbrg }}">{{ $data->kdbrg." | ".$data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="noaset" class="col-sm-6 control-label">No Aset</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control noaset" name="noaset" id="noaset" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian" class="col-sm-6 control-label">Uraian Objek</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraian" name="uraian" placeholder="Masukan Uraian Objek Sewa" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="luas" class="col-sm-6 control-label">Luas</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control" id="luas" name="luas" placeholder="Masukan Luasan Objek Sewa" value="" required="" step=".01">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="luasterbilang" class="col-sm-6 control-label">Luas Terbilang</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="luasterbilang" name="luasterbilang" placeholder="Masukan Luasan Terbilang" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Foto 1</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png" class="custom-file-input" id="foto1" name="foto1">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="lihatfoto1" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <img src="#" id="aktuallihatfoto1" width="50" height="50">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Foto 2</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png" class="custom-file-input" id="foto2" name="foto2">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="lihatfoto2" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <img src="#" id="aktuallihatfoto2" width="50" height="50">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Foto 3</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png" class="custom-file-input" id="foto3" name="foto3">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="lihatfoto3" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <img src="#" id="aktuallihatfoto3" width="50" height="50">
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File Penetapan Status</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".pdf" class="custom-file-input" id="filepenetapanstatus" name="filepenetapanstatus">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilepsp" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallinkfilepsp">Lihat Bukti</a>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Dokumen Pemilikan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".pdf" class="custom-file-input" id="dokkepemilikan" name="dokkepemilikan">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkdokkepemilikan" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallinkdokkepemilikan">Lihat Bukti</a>
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
            $('.kodebarang').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.noaset').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelobjeksewa tfoot th').each( function (i) {
                var title = $('#tabelobjeksewa thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelobjeksewa').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdataobjeksewa')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'idarea', name: 'sewaarearelation.uraianarea'},
                    {data: 'idsubarea', name: 'idsubarea'},
                    {data: 'idgedung', name: 'idgedung'},
                    {data: 'kodebarang', name: 'kodebarang'},
                    {data: 'noaset', name: 'noaset'},
                    {data: 'uraian', name: 'uraian'},
                    {data: 'luas', name: 'luas'},
                    {data: 'luasterbilang', name: 'luasterbilang'},
                    {data: 'foto', name: 'foto'},
                    {data: 'foto2', name: 'foto2'},
                    {data: 'foto3', name: 'foto3'},
                    {data: 'filepenetapanstatus', name: 'filepenetapanstatus'},
                    {data: 'dokkepemilikan', name: 'dokkepemilikan'},
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
            $('#tambahobjeksewa').click(function () {
                $('#saveBtn').val("tambah");
                $('#idobjeksewa').val('');
                $('#idsubareaawal').val('');
                $('#idgedungawal').val('');
                $('#kodebarangawal').val('');
                $('#noasetawal').val('');
                $('#formgedung').trigger("reset");
                $('#modelHeading').html("Tambah Objek Sewa");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editobjeksewa', function () {
                var id = $(this).data('id');
                $.get("{{ route('objeksewa.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Objek Sewa");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idobjeksewa').val(data.id);
                    $('#idarea').val(data.idarea).trigger('change');
                    $('#idsubarea').val(data.idsubarea).trigger('change');
                    $('#idgedung').val(data.idgedung).trigger('change');
                    $('#kodebarang').val(data.kodebarang).trigger('change');
                    $('#noaset').val(data.noaset).trigger('change');
                    $('#uraian').val(data.uraian);
                    $('#luas').val(data.luas);
                    $('#luasterbilang').val(data.luasterbilang);
                    $('#idareaawal').val(data.idarea);
                    $('#idsubareaawal').val(data.idsubarea);
                    $('#idgedungawal').val(data.idgedung);
                    $('#kodebarangawal').val(data.kodebarang);
                    $('#noasetawal').val(data.noaset);
                    document.getElementById('aktuallihatfoto1').src = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')}}"+"/"+data.foto
                    document.getElementById('aktuallihatfoto2').src = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')}}"+"/"+data.foto2
                    document.getElementById('aktuallihatfoto3').src = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/fotobmn')}}"+"/"+data.foto3
                    document.getElementById('aktuallinkfilepsp').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/filepsp')}}"+"/"+data.filepenetapanstatus
                    document.getElementById('aktuallinkdokkepemilikan').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/kepemilikan')}}"+"/"+data.dokkepemilikan
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
                let form = document.getElementById('formobjeksewa');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('idobjeksewa').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('objeksewa.store')}}":"{{route('objeksewa.update','')}}"+'/'+id,
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
                        $('#formgedung').trigger("reset");
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
            $('body').on('click', '.deletegedung', function () {

                var idgedung = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('objeksewa.destroy','') }}"+'/'+idgedung,
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

            $('#kodebarang').on('change', function () {
                var kodebarang = this.value;
                $.ajax({
                    url: "{{url('dapatkandataaset')}}",
                    type: "POST",
                    data: {
                        kodebarang: kodebarang,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var noaset = document.getElementById('noasetawal').value;
                        $('#noaset').html('<option value="">Pilih NUP</option>');
                        $.each(result.barang, function (key, value) {
                            if (noaset == value.no_aset) {
                                $('select[name="noaset"]').append('<option value="'+value.no_aset+'" selected>'+value.no_aset+'</option>').trigger('change')
                            }else{
                                $("#noaset").append('<option value="' + value.no_aset + '">' + value.no_aset + '</option>');
                            }

                        });
                    }

                });
            });

        });

    </script>
@endsection
