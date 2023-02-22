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
                            @if(session('importgagal'))
                                <div class="alert alert-danger">
                                    {{session('importgagal')}}
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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahro"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="importro"> Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelro" class="table table-bordered table-striped tabelro">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>KRO</th>
                                <th>Kegiatan</th>
                                <th>Output</th>
                                <th>SubOutput</th>
                                <th>Uraian RO</th>
                                <th>Target</th>
                                <th>Satuan</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>KRO</th>
                                <th>Kegiatan</th>
                                <th>Output</th>
                                <th>SubOutput</th>
                                <th>Uraian RO</th>
                                <th>Target</th>
                                <th>Satuan</th>
                                <th>Jenis</th>
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
                                        <form id="formro" name="formro" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="kegiatanawal" id="kegiatanawal">
                                            <input type="hidden" name="outputawal" id="outputawal">
                                            <input type="hidden" name="suboutputawal" id="suboutputawal">
                                            <input type="hidden" name="statusawal" id="statusawal">
                                            <input type="hidden" name="idkroawal" id="idkroawal">
                                            <div class="form-group">
                                                <label for="tahunanggaran" class="col-sm-6 control-label">Tahun Anggaran</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control tahunanggaran" name="tahunanggaran" id="tahunanggaran" style="width: 100%;">
                                                        <option value="">Pilih Tahun Anggaran</option>
                                                        @foreach($datatahunanggaran as $data)
                                                            @if($data->kode == date('Y'))
                                                                <option value="{{ $data->kode }}" selected>{{ $data->tahunanggaran }}</option>
                                                            @endif
                                                            <option value="{{ $data->kode }}">{{ $data->tahunanggaran }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kodesatker" class="col-sm-6 control-label">Kode Satker</label>
                                                <div class="col-sm-12">
                                                <select class="form-control kodesatker" name="kodesatker" id="kodesatker" style="width: 100%;">
                                                    <option value="">Pilih Satker</option>
                                                    <option value="001012">Sekretariat Jenderal</option>
                                                    <option value="001030">Dewan</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kro" class="col-sm-6 control-label">KRO</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kro" name="kro" id="kro" style="width: 100%;">
                                                        <option value="">Pilih KRO</option>
                                                        @foreach($datakro as $data)
                                                            <option value="{{ $data->id }}">{{ $data->kodekegiatan.$data->kodeoutput." | ".$data->uraiankro }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kegiatan" class="col-sm-6 control-label">Kegiatan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kegiatan" name="kegiatan" id="kegiatan" style="width: 100%;">
                                                        <option value="">Pilih Kegiatan</option>
                                                        @foreach($datakegiatan as $data)
                                                            <option value="{{ $data->kode }}">{{ $data->kode." | ".$data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="output" class="col-sm-6 control-label">Output</label>
                                                <div class="col-sm-12">
                                                <select class="form-control output" name="output" id="output" style="width: 100%;">
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="suboutput" class="col-sm-6 control-label">SubOutput</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control suboutput" name="suboutput" id="suboutput" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraianro" class="col-sm-6 control-label">Uraian RO</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianro" name="uraianro" placeholder="Uraian RO" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="target" class="col-sm-6 control-label">Target</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="target" name="target" placeholder="Target" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="satuan" class="col-sm-6 control-label">Satuan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenisindikator" class="col-sm-6 control-label">Jenis Indikator</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control jenisindikator" name="jenisindikator" id="jenisindikator" style="width: 100%;">
                                                        <option value="">Jenis Indikator</option>
                                                        <option value="1">Internal</option>
                                                        <option value="2">Non Internal</option>
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
    <script type="text/javascript">
        $(function () {
            $('.tahunanggaran').select2({
                theme: 'bootstrap4',
            })

            $('.kodesatker').select2({
                theme: 'bootstrap4',
            })

            $('.kegiatan').select2({
                theme: 'bootstrap4',
            })

            $('.output').select2({
                theme: 'bootstrap4',
            })

            $('.suboutput').select2({
                theme: 'bootstrap4',

            })

            $('.jenisindikator').select2({
                theme: 'bootstrap4',
            })

            $('.kro').select2({
                theme: 'bootstrap4',
            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelro tfoot th').each( function (i) {
                var title = $('#tabelro thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabelro').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('ro.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'idkro', name: 'idkro'},
                    {data: 'kodekegiatan', name: 'kodekegiatan'},
                    {data: 'kodeoutput', name: 'kodeoutput'},
                    {data: 'kodesuboutput', name: 'kodesuboutput'},
                    {data: 'uraianro', name: 'uraianro'},
                    {data: 'target', name: 'target'},
                    {data: 'satuan', name: 'satuan'},
                    {data: 'jenisindikator', name: 'jenisindikator'},
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
            $('#tambahro').click(function () {
                $('#saveBtn').val("tambah");
                $('#formro').trigger("reset");
                $('#modelHeading').html("Tambah kro");
                $('#ajaxModel').modal('show');
            });

            $('#importro').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import RO dari Data Anggaran Terbaru ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importro')}}";
                }
            });


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editro', function () {
                var idro = $(this).data('id');
                $.get("{{ route('ro.index') }}" +'/' + idro +'/edit', function (data) {
                    $('#modelHeading').html("Edit RO");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran).trigger('change');
                    $('#kodesatker').val(data.kodesatker).trigger('change');
                    $('#kro').val(data.idkro).trigger('change');
                    $('#kegiatan').val(data.kodekegiatan).trigger('change');
                    $('#kegiatanawal').val(data.kodekegiatan);
                    $('#outputawal').val(data.kodeoutput);
                    $('#suboutputawal').val(data.kodesuboutput);
                    $('#statusawal').val(data.status);
                    $('#target').val(data.target);
                    $('#satuan').val(data.satuan);
                    $('#uraianro').val(data.uraianro);
                    $('#jenisindikator').val(data.jenisindikator).trigger('change');
                    $('#status').val(data.status);

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
                let form = document.getElementById('formro');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('id').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('ro.store')}}":"{{route('ro.update','')}}"+'/'+id,
                    type: "POST",
                    enctype: 'multipart/form-data',
                    contentType: false,
                    processData: false,
                    dataType: 'json',
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
                        $('#tahunanggaran').val('').trigger('change');
                        $('#kodesatker').val('').trigger('change');
                        $('#kegiatan').val('').trigger('change');
                        $('#output').val('').trigger('change');
                        $('#jenisindikator').val('').trigger('change');
                        $('#kegiatanawal').val('');
                        $('#outputawal').val('');
                        $('#suboutputawal').val('');
                        $('#statusawal').val('');
                        $('#formkro').trigger("reset");
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
            $('body').on('click', '.deletero', function () {

                var idkro = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('kro.destroy','') }}"+'/'+idkro,
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
                        error: function (xhr) {
                            var errorsArr = [];
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorsArr.push(value);
                            });
                            Swal.fire({
                                title: 'Error!',
                                text: errorsArr,
                                icon: 'error'
                            })
                            $('#saveBtn').html('Simpan Data');
                        },
                    });
                };
            });
        });


        $('#kegiatan').on('change', function () {
            var  kegiatan = this.value;
            $.ajax({
                url: "{{url('ambildataoutput')}}",
                type: "POST",
                data: {
                    kodekegiatan: kegiatan,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var output = document.getElementById('outputawal').value;
                    $('#output').html('<option value="">Pilih Output</option>');
                    $.each(result.output, function (key, value) {
                            if (output === value.kodeoutput) {
                            $('select[name="output"]').append('<option value="'+value.kodeoutput+'" selected>'+value.kodeoutput+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#output").append('<option value="' + value.kodeoutput + '">' +value.kodeoutput+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#output').on('change', function () {
            var  kodeoutput = this.value;
            var  kodekegiatan = document.getElementById('kegiatan').value;
            $.ajax({
                url: "{{url('ambildatasuboutput')}}",
                type: "POST",
                data: {
                    kodekegiatan: kodekegiatan,
                    kodeoutput: kodeoutput,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var suboutput = document.getElementById('suboutputawal').value;
                    $('#suboutput').html('<option value="">Pilih SubOutput</option>');
                    $.each(result.suboutput, function (key, value) {
                        if (suboutput === value.kodesuboutput) {
                            $('select[name="suboutput"]').append('<option value="'+value.kodesuboutput+'" selected>'+value.kodesuboutput+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#output").append('<option value="' + value.kodesuboutput + '">' +value.kodesuboutput+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });
    </script>
@endsection
