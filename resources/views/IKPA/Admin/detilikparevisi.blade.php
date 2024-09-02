@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{session('status')}}
                            </div>
                        @endif
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
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdata">Tambah Data</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetildata" class="table table-bordered table-striped tabeldetildata">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>No Surat</th>
                                <th>Tanggal Surat</th>
                                <th>Perihal</th>
                                <th>No Revisi</th>
                                <th>Tanggal Pengesahan</th>
                                <th>Periode Pengesahan</th>
                                <th>Kewenangan Revisi</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!-- <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('importdetilrevisi')}}" method="POST" id="formuploaddetilpenyelesaiantagihan" name="formuploaddetilpenyelesaiantagihan" class="form-horizontal" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Upload File Detail</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".xls,.xlsx" class="custom-file-input" id="filedetail" name="filedetail">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
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
                        -->
                        <div class="modal fade" id="ajaxModelDetilRevisi" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdetilrevisi" name="formdetilrevisi" class="form-horizontal">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Satker</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;">
                                                        <option value="">Pilih Satker</option>
                                                        <option value="001012">Setjen</option>
                                                        <option value="001030">Dewan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bulan" class="col-sm-6 control-label">Biro</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;">
                                                        <option value="">Pilih Biro</option>
                                                        @foreach($databiro as $data)
                                                            <option value="{{ $data->id }}">{{ $data->uraianbiro }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Bagian" class="col-sm-6 control-label">Bagian</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbagian" name="idbagian" id="idbagian" style="width: 100%;" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian ruangan" class="col-sm-6 control-label">No Surat</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control nosurat" id="nosurat" name="nosurat" placeholder="Masukan No Surat" value="" maxlength="200" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label>Tanggal Surat</label>
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control tanggalsurat" name="tanggalsurat" id="tanggalsurat" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="perihal" class="col-sm-6 control-label">Perihal</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control perihal" id="perihal" name="perihal" placeholder="Perihal" value=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian ruangan" class="col-sm-6 control-label">No Revisi</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control norevisi" id="norevisi" name="norevisi" placeholder="Masukan No Revisi" value="" maxlength="200" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label>Tanggal Pengesahan</label>
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control tanggalpengesahan" name="tanggalpengesahan" id="tanggalpengesahan" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bulan" class="col-sm-6 control-label">Kewenangan Revisi</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kewenanganrevisi" name="kewenanganrevisi" id="kewenanganrevisi" style="width: 100%;">
                                                        <option value="">Pilih Kewenangan</option>
                                                        <option value="Revisi Kemenkeu">Revisi Kemenkeu</option>
                                                        <option value="Revisi POK">Revisi POK</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bulan" class="col-sm-6 control-label">Status Revisi</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control status" name="status" id="status" style="width: 100%;">
                                                        <option value="">Pilih Status</option>
                                                        <option value="Pengajuan Dari Unit">Pengajuan Dari Unit</option>
                                                        <option value="Persetujuan KPA">Persetujuan KPA</option>
                                                        <option value="Penelaahan Perencanaan">Penelaahan Perencanaan</option>
                                                        <option value="Penelaahan Kemenkeu">Penelaahan Kemenkeu</option>
                                                        <option value="Final">Final</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary saveBtn" id="saveBtn" value="create">Simpan</button>
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
            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idbiro').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.kewenanganrevisi').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.status').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.idbagian').select2({
                width: '100%',
                theme: 'bootstrap4',
            })
            $( "#tanggalsurat" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $( "#tanggalpengesahan" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            // Setup - add a text input to each header cell
            $('#tabeldetildata thead th').each( function (i) {
                var title = $('#tabeldetildata thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });
            var table = $('.tabeldetildata').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdetilrevisi')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'nosurat', name: 'nosurat'},
                    {data: 'tanggalsurat', name: 'tanggalsurat'},
                    {data: 'perihal', name: 'perihal'},
                    {data: 'norevisi', name: 'norevisi'},
                    {data: 'tanggalpengesahan', name: 'tanggalpengesahan'},
                    {data: 'bulanpengesahan', name: 'bulanpengesahan'},
                    {data: 'kewenanganrevisi', name: 'kewenanganrevisi'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
            });
            table.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

            // Filter event handler
            $( table.table().container() ).on( 'keyup', 'thead input', function () {
                table
                    .column( $(this).data('index') )
                    .search( this.value )
                    .draw();
            });

            $('#tambahdata').click(function () {
                $('#saveBtn').val("tambah");
                $('#idbagianawal').val('');
                $('#idbiroawal').val('');
                $('#idbagian').val('');
                $('#idbiro').val('');
                $('#kdsatker').val('');
                $('#nosurat').val('');
                $('#perihal').val('');
                $('#norevisi').val('');
                $('#kewenanganrevisi').val('');
                $('#status').val('');
                $('#id').val('');
                $('#formdetilrevisi').trigger("reset");
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModelDetilRevisi').modal('show');
            });

            $('body').on('click', '.editdata', function () {
                var id = $(this).data('id');
                $.get("{{ route('detilikparevisi.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtn').val("edit");
                    $('#ajaxModelDetilRevisi').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kodesatker).trigger('change');
                    $('#idbagian').val(data.idbagian).trigger('change');
                    $('#idbagianawal').val(data.idbagian);
                    $('#idbiro').val(data.idbiro).trigger('change');
                    $('#idbiroawal').val(data.idbiro);
                    $('#nosurat').val(data.nosurat);
                    $('#tanggalsurat').val(data.tanggalsurat);
                    $('#perihal').val(data.perihal);
                    $('#norevisi').val(data.norevisi);
                    $('#tanggalpengesahan').val(data.tanggalpengesahan);
                    $('#kewenanganrevisi').val(data.kewenanganrevisi).trigger('change');
                    $('#status').val(data.status).trigger('change');
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formdetilrevisi');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('id').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('detilikparevisi.store')}}":"{{route('detilikparevisi.update','')}}"+'/'+id,
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
                        $('#formdetilrevisi').trigger("reset");
                        $('#ajaxModelDetilRevisi').modal('hide');
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

            $('body').on('click', '.deletedata', function () {

                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('detilikparevisi.destroy','') }}"+'/'+id,
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

            $('#idbiro').on('change', function () {
                var idbiro = this.value;
                $.ajax({
                    url: "{{url('ambildatabagian')}}",
                    type: "POST",
                    data: {
                        idbiro: idbiro,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var idbagian = document.getElementById('idbagianawal').value;
                        $('#idbagian').html('<option value="">Pilih Bagian</option>');
                        $.each(result.bagian, function (key, value) {
                            if (idbagian == value.id) {
                                $('select[name="idbagian"]').append('<option value="'+value.id+'" selected>'+value.uraianbagian+'</option>').trigger('change')
                            }else{
                                $("#idbagian").append('<option value="' + value.id + '">' + value.uraianbagian + '</option>');
                            }

                        });
                    }

                });
            });

        });

    </script>
@endsection
