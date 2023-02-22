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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahkro"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="importkro"> Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelkro" class="table table-bordered table-striped tabelkro">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>Kegiatan</th>
                                <th>Output</th>
                                <th>Uraian Kro</th>
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
                                <th>Kegiatan</th>
                                <th>Output</th>
                                <th>Uraian Kro</th>
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
                                        <form id="formkro" name="formkro" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="kegiatanawal" id="kegiatanawal">
                                            <input type="hidden" name="outputawal" id="outputawal">
                                            <input type="hidden" name="statusawal" id="statusawal">
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
                                                <label for="uraiankro" class="col-sm-6 control-label">Uraian KRO</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraiankro" name="uraiankro" placeholder="uraiankro" value="">
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
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')
            })

            $('.kodesatker').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')
            })

            $('.kegiatan').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')
            })

            $('.output').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')
            })

            $('.jenisindikator').select2({
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
            $('#tabelkro tfoot th').each( function (i) {
                var title = $('#tabelkro thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabelkro').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('kro.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'kodekegiatan', name: 'kodekegiatan'},
                    {data: 'kodeoutput', name: 'kodeoutput'},
                    {data: 'uraiankro', name: 'uraiankro'},
                    {data: 'target', name: 'target'},
                    {data: 'satuan', name: 'satuan'},
                    {data: 'jenisindikator', name: 'jenis'},
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
            $('#tambahkro').click(function () {
                $('#saveBtn').val("tambah");
                $('#formkro').trigger("reset");
                $('#modelHeading').html("Tambah kro");
                $('#ajaxModel').modal('show');
            });

            $('#importkro').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import KRO dari Data Anggaran Terbaru ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importkro')}}";
                }
            });


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editkro', function () {
                var idkro = $(this).data('id');
                $.get("{{ route('kro.index') }}" +'/' + idkro +'/edit', function (data) {
                    $('#modelHeading').html("Edit kro");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran).trigger('change');
                    $('#kodesatker').val(data.kodesatker).trigger('change');
                    $('#kegiatan').val(data.kodekegiatan).trigger('change');
                    $('#kegiatanawal').val(data.kodekegiatan);
                    $('#outputawal').val(data.kodeoutput);
                    $('#statusawal').val(data.status);
                    $('#target').val(data.target);
                    $('#satuan').val(data.satuan);
                    $('#uraiankro').val(data.uraiankro);
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
                let form = document.getElementById('formkro');
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
                    url: saveBtn === "tambah" ? "{{route('kro.store')}}":"{{route('kro.update','')}}"+'/'+id,
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
            $('body').on('click', '.deletekro', function () {

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
    </script>
@endsection
