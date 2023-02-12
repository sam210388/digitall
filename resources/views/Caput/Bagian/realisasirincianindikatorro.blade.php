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
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbulan" name="idbulan" id="idbulan" style="width: 100%;">
                                    <option value="">Pilih Bulan</option>
                                    @foreach($databulan as $data)
                                        <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasi" class="table table-bordered table-striped tabelrealisasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Rincian Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Dokumen</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Rincian Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Dokumen</th>
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
                                        <form id="formrealisasi" name="formrealisasi" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="idindikatoroutput" id="idindikatoroutput">
                                            <input type="hidden" name="idrincianindikatorro" id="idrincianindikatorro">
                                            <input type="hidden" name="linkbuktiawal" id="linkbuktiawal">
                                            <div class="form-group">
                                                <label for="tanggallapor" class="col-sm-6 control-label">Tanggal</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control tanggallapor" id="tanggallapor" name="tanggallapor">
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
                                                <label for="jumlah" class="col-sm-6 control-label">Jumlah Output</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jumlahsdperiodeini" class="col-sm-6 control-label">Jumlah Output Sd Periode Ini</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="jumlahsdperiodeini" name="jumlahsdperiodeini" placeholder="Jumlah sd Periode Ini" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="prosentase" class="col-sm-6 control-label">Prosentase Periode Ini</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="prosentase" name="prosentase" placeholder="Prosentase Periode Ini" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="prosentasesdperiodeini" class="col-sm-6 control-label">Prosentase sd Periode Ini</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="prosentasesdperiodeini" name="prosentasesdperiodeini" placeholder="Prosentase Sd Periode Ini" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="statuspelaksanaan" class="col-sm-6 control-label">Status Pelaksanaan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control statuspelaksanaan" name="statuspelaksanaan" id="statuspelaksanaan" style="width: 100%;">
                                                        <option value="">Pilih Status Pelaksanaan</option>
                                                        @foreach($datastatuspelaksanaan as $data)
                                                            <option value="{{ $data->id }}">{{ $data->uraianstatus }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kategoripermasalahan" class="col-sm-6 control-label">Kategori Permasalahan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kategoripermasalahan" name="kategoripermasalahan" id="kategoripermasalahan" style="width: 100%;">
                                                        <option value="">Pilih Permasalahan</option>
                                                        @foreach($datakategoripermasalahan as $data)
                                                            <option value="{{ $data->id }}">{{ $data->uraiankategori }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraianoutputdihasilkan" class="col-sm-6 control-label">Output Dihasilkan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="uraianoutputdihasilkan" name="uraianoutputdihasilkan" placeholder="Output Dihasilkan" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan" class="col-sm-6 control-label">Keterangan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="" maxlength="100" required="">
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
                                                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="DetailRincianModal" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdetail" name="formdetail" class="form-horizontal" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="targetpengisian" class="col-sm-6 control-label">Target Pengisian</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="targetpengisian" name="targetpengisian" placeholder="Kondisi" style="width: 100%;"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="volperbulan" class="col-sm-6 control-label">Vol Per Bulan</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="volperbulan" name="volperbulan" placeholder="Vol Per Bulan"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="infoproses" class="col-sm-6 control-label">Info Proses</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="infoproses" name="infoproses" placeholder="Info Proses"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan" class="col-sm-6 control-label">Info Proses</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"></textarea>
                                                </div>
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
            $('#tabelrealisasi tfoot th').each( function (i) {
                var title = $('#tabelrealisasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            let idbulan = document.getElementById('idbulan').value;
            var table = $('.tabeltindaklanjut').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                "ajax": {
                    "url": "{{route('realisasirincianindikatorro.index')}}",
                    "type": "POST",
                    "data": function (d){
                        d._token = "{{ csrf_token() }}";
                        d.idbulan = idbulan;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'idindikatorro', name: 'idindikatorro'},
                    {data: 'target', name: 'target'},
                    {data: 'jumlah', name: 'jumlah'},
                    {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                    {data: 'prosentase', name: 'prosentase'},
                    {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                    {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                    {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                    {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'file', name: 'file'},
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
            });
        });

        /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
        $('body').on('click', '.editdata', function () {
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('kelolatindaklanjut.index') }}" +'/' + id +'/edit',
                success: function (data) {
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#idrekomendasi').val(data.idrekomendasi);
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
            let form = document.getElementById('formtindaklanjut');
            let fd = new FormData(form);
            let file = $('#file')[0].files;
            let saveBtn = document.getElementById('saveBtn').value;
            var id = document.getElementById('id').value;
            fd.append('file',file[0])
            fd.append('saveBtn',saveBtn)
            if(saveBtn == "edit"){
                fd.append('_method','PUT')
            }
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }
            $.ajax({
                data: fd,
                url: saveBtn === "tambah" ? "{{route('kelolatindaklanjut.store')}}":"{{route('kelolatindaklanjut.update','')}}"+'/'+id,
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

        /*------------------------------------------
        --------------------------------------------
        Delete Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deletedata', function () {
            var idtemuan = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('kelolatindaklanjut.destroy','') }}"+"/"+idtemuan,
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

                        $('#saveBtn').html('Simpan Data');
                    },
                });
            }
        });

        $('body').on('click', '.ajukankeirtama', function () {
            var idtindaklanjut = $(this).data("id");
            if(confirm("Apakah Anda Yakin AKan Kirim Data Ini Ke Irtama?")){
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajukankeirtama') }}"+"/"+idtindaklanjut,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Data Berhasil Dikirim Ke Irtama',
                                icon: 'success'
                            })
                        }else if(data.status == "belumditanggapi"){
                            document.getElementById('idtindaklanjut').value = idtindaklanjut
                            $('#saveBtnPenjelasan').val("tambah");
                            $('#formpenjelasan').trigger("reset");
                            $('#modelHeadingPenjelasan').html("Penjelasan Penolakan");
                            $('#ajaxModelPenjelasan').modal('show');

                        } else{
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




        $( "#tanggaldokumen" ).datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        $('#saveBtnPenjelasan').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            let form = document.getElementById('formpenjelasan');
            let fd = new FormData(form);
            let saveBtn = document.getElementById('saveBtnPenjelasan').value;
            fd.append('saveBtnPenjelasan',saveBtn)
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]);
            }
            $.ajax({
                data: fd,
                url: "{{url('simpantanggapan')}}",
                type: "POST",
                dataType: 'json',
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "berhasil"){
                        Swal.fire({
                            title: 'Sukses',
                            text: 'Tanggapan Berhasil Disimpan, Data Sudah Dikirim Ke Irtama',
                            icon: 'success'
                        })
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text: 'Simpan Data Gagal',
                            icon: 'error'
                        })
                    }
                    $('#formpenjelasan').trigger("reset");
                    $('#ajaxModelPenjelasan').modal('hide');
                    $('#saveBtnPenjelasan').html('Simpan Data');
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
    </script>

@endsection
