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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahpenyewa"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelpenyewa" class="table table-bordered table-striped tabelpenyewa">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Penyewa</th>
                                <th>Kelembagaan</th>
                                <th>Jenis Usaha</th>
                                <th>Alamat</th>
                                <th>Kedudukan</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Nomor NPWP</th>
                                <th>File NPWP</th>
                                <th>Nomor SIUP</th>
                                <th>File SIUP</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama Penyewa</th>
                                <th>Kelembagaan</th>
                                <th>Jenis Usaha</th>
                                <th>Alamat</th>
                                <th>Kedudukan</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Nomor NPWP</th>
                                <th>File NPWP</th>
                                <th>Nomor SIUP</th>
                                <th>File SIUP</th>
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
                                        <form id="formpenyewa" name="formpenyewa" class="form-horizontal">
                                            <input type="hidden" name="filenpwpawal" id="filenpwpawal">
                                            <input type="hidden" name="filesiupawal" id="filesiupawal">
                                            <input type="hidden" name="idpenyewa" id="idpenyewa">
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
                                                <label for="namapenyewa" class="col-sm-6 control-label">Nama Penyewa</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="namapenyewa" name="namapenyewa" placeholder="Masukan Nama Penyewa" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kelembagaan" class="col-sm-6 control-label">Kelembagaan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="kelembagaan" name="kelembagaan" placeholder="Masukan Badan Hukum Penyewa" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenisusaha" class="col-sm-6 control-label">Jenis Usaha</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="jenisusaha" name="jenisusaha" placeholder="Masukan Jenis Usaha" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat" class="col-sm-6 control-label">Alamat</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan Alamat" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kedudukan" class="col-sm-6 control-label">Tempat Kedudukan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="kedudukan" name="kedudukan" placeholder="Masukan Tempat Kedudukan" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="col-sm-6 control-label">Email</label>
                                                <div class="col-sm-12">
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email Valid" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="telepon" class="col-sm-6 control-label">Telepon</label>
                                                <div class="col-sm-12">
                                                    <input type="tel" class="form-control" id="telepon" name="telepon" placeholder="Masukan Telepon Valid" value="" maxlength="500" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="luas" class="col-sm-6 control-label">Nomor NPWP</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control" id="nomornpwp" name="nomornpwp" placeholder="Masukan Nomor NPWP" value="" required="" step=".01">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File NPWP</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".gif,.jpg,.jpeg,.png,.pdf" class="custom-file-input" id="filenpwp" name="filenpwp">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilenpwp" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallihatnpwp">Lihat NPWP</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="luas" class="col-sm-6 control-label">Nomor SIUP</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control" id="nomorsiup" name="nomorsiup" placeholder="Masukan Nomor SIUP" value="" required="" step=".01">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">File SIUP</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".pdf" class="custom-file-input" id="filesiup" name="filesiup">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilesiup" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallihatsiup">Lihat SIUP</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="statuspenyewa" class="col-sm-6 control-label">Status Penyewa</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control statuspenyewa" name="statuspenyewa" id="statuspenyewa" style="width: 100%;">
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
            $('.statuspenyewa').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.userpenyewa').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelpenyewa tfoot th').each( function (i) {
                var title = $('#tabelpenyewa thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelpenyewa').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatapenyewa')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'namapenyewa', name: 'namapenyewa'},
                    {data: 'kelembagaan', name: 'kelembagaan'},
                    {data: 'jenisusaha', name: 'jenisusaha'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'kedudukan', name: 'kedudukan'},
                    {data: 'email', name: 'email'},
                    {data: 'telepon', name: 'telepon'},
                    {data: 'nomornpwp', name: 'nomornpwp'},
                    {data: 'filenpwp', name: 'filenpwp'},
                    {data: 'nomorsiup', name: 'nomorsiup'},
                    {data: 'filesiup', name: 'filesiup'},
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
            $('#tambahpenyewa').click(function () {
                $('#saveBtn').val("tambah");
                $('#idpenyewa').val('');
                $('#filenpwpawal').val('');
                $('#filesiupawal').val('');
                $('#modelHeading').html("Tambah Penyewa");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editpenyewa', function () {
                var id = $(this).data('id');
                $.get("{{ route('penyewa.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Gedung");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idpenyewa').val(data.id);
                    $('#filenpwpawal').val(data.filenpwp);
                    $('#filesiupawal').val(data.filesiup);
                    $('#namapenyewa').val(data.namapenyewa);
                    $('#kelembagaan').val(data.kelembagaan);
                    $('#jenisusaha').val(data.jenisusaha);
                    $('#alamat').val(data.alamat);
                    $('#kedudukan').val(data.kedudukan);
                    $('#email').val(data.email);
                    $('#telepon').val(data.telepon);
                    $('#nomornpwp').val(data.nomornpwp);
                    $('#nomorsiup').val(data.nomorsiup);
                    $('#status').val(data.status).trigger('change');
                    $('#userpenyewa').val(data.userpenyewa).trigger('change');
                    document.getElementById('aktuallihatnpwp').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/npwp')}}"+"/"+data.filenpwp
                    document.getElementById('aktuallihatsiup').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/siup')}}"+"/"+data.filesiup
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
                let form = document.getElementById('formpenyewa');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var idpenyewa = document.getElementById('idpenyewa').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('penyewa.store')}}":"{{route('penyewa.update','')}}"+'/'+id,
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
                        $('#formpenyewa').trigger("reset");
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
            $('body').on('click', '.deletepenyewa', function () {

                var idpenanggungjawabsewa = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('penanggungjawabsewa.destroy','') }}"+'/'+idpenanggungjawabsewa,
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
