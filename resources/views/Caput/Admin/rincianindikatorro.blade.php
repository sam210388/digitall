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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahrincianindikatorro"> Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrincianindikatorro" class="table table-bordered table-striped tabelrincianindikatorro">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>Indikator RO</th>
                                <th>Indeks</th>
                                <th>Uraian Rincian</th>
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
                                <th>Indikator RO</th>
                                <th>Indeks</th>
                                <th>Uraian Rincian</th>
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
                                        <form id="formrincianindikatorro" name="formrincianindikatorro" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="kegiatanawal" id="kegiatanawal">
                                            <input type="hidden" name="outputawal" id="outputawal">
                                            <input type="hidden" name="suboutputawal" id="suboutputawal">
                                            <input type="hidden" name="komponenawal" id="komponenawal">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
                                            <input type="hidden" name="subkomponenawal" id="subkomponenawal">
                                            <input type="hidden" name="statusawal" id="statusawal">
                                            <input type="hidden" name="idindikatorroawal" id="idkroawal">
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
                                                <label for="indikatorro" class="col-sm-6 control-label">Indikator RO</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control indikatorro" name="indikatorro" id="indikatorro" style="width: 100%;">
                                                        <option value="">Pilih Indikator RO</option>
                                                        @foreach($dataindikatorro as $data)
                                                            <option value="{{ $data->id }}">{{ $data->indeks." | ".$data->uraianindikatorro }}</option>
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
                                                <label for="komponen" class="col-sm-6 control-label">Komponen</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control komponen" name="komponen" id="komponen" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="subkomponen" class="col-sm-6 control-label">Sub Komponen</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control subkomponen" name="subkomponen" id="subkomponen" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraianrincianindikatorro" class="col-sm-6 control-label">Uraian Rincian</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianrincianindikatorro" name="uraianrincianindikatorro" placeholder="Uraian Indikator RO" value="">
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
                                            <div class="form-group">
                                                <label for="deputi" class="col-sm-6 control-label">Deputi</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control iddeputi" name="iddeputi" id="iddeputi" style="width: 100%;">
                                                        <option value="">Pilih Deputi</option>
                                                        @foreach($datadeputi as $data)
                                                            <option value="{{ $data->id }}">{{ $data->uraiandeputi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Biro" class="col-sm-6 control-label">Biro</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Bagian" class="col-sm-6 control-label">Bagian</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbagian" name="idbagian" id="idbagian" style="width: 100%;">
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

            $('.komponen').select2({
                theme: 'bootstrap4',
            })

            $('.subkomponen').select2({
                theme: 'bootstrap4',

            })

            $('.jenisindikator').select2({
                theme: 'bootstrap4',
            })

            $('.indikatorro').select2({
                theme: 'bootstrap4',
            })
            $('.iddeputi').select2({
                theme: 'bootstrap4',
            })
            $('.idbiro').select2({
                theme: 'bootstrap4',
            })
            $('.idbagian').select2({
                theme: 'bootstrap4',
            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelrincianindikatorro tfoot th').each( function (i) {
                var title = $('#tabelrincianindikatorro thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabelrincianindikatorro').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('rincianindikatorro.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'idindikatorro', name: 'idindikatorro'},
                    {data: 'indeks', name: 'indeks'},
                    {data: 'uraianrincianindikatorro', name: 'uraianrincianindikatorro'},
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
            $('#tambahrincianindikatorro').click(function () {
                $('#saveBtn').val("tambah");
                $('#formrincianindikatorro').trigger("reset");
                $('#modelHeading').html("Tambah Rincian Indikator RO");
                $('#ajaxModel').modal('show');
            });


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editrincianindikatorro', function () {
                var idrincianindikatorro = $(this).data('id');
                $.get("{{ route('rincianindikatorro.index') }}" +'/' + idrincianindikatorro +'/edit', function (data) {
                    $('#modelHeading').html("Edit RO");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran).trigger('change');
                    $('#kodesatker').val(data.kodesatker).trigger('change');
                    $('#indikatorro').val(data.idindikatorro).trigger('change');
                    $('#kegiatan').val(data.kodekegiatan).trigger('change');
                    $('#kegiatanawal').val(data.kodekegiatan);
                    $('#iddeputi').val(data.iddeputi).trigger('change');
                    $('#idbiroawal').val(data.idbiro);
                    $('#idbagianawal').val(data.idbagian);
                    $('#outputawal').val(data.kodeoutput);
                    $('#suboutputawal').val(data.kodesuboutput);
                    $('#komponenawal').val(data.kodekomponen);
                    $('#subkomponenawal').val(data.kodesubkomponen);
                    $('#statusawal').val(data.status);
                    $('#target').val(data.target);
                    $('#satuan').val(data.satuan);
                    $('#uraianrincianindikatorro').val(data.uraianrincianindikatorro);
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
                let form = document.getElementById('formrincianindikatorro');
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
                    url: saveBtn === "tambah" ? "{{route('rincianindikatorro.store')}}":"{{route('rincianindikatorro.update','')}}"+'/'+id,
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
                        $('#komponenawal').val('');
                        $('#statusawal').val('');
                        $('#formro').trigger("reset");
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
            $('body').on('click', '.deleterincianindikatorro', function () {

                var idrincianindikatorro = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('rincianindikatorro.destroy','') }}"+'/'+idrincianindikatorro,
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
                            $("#suboutput").append('<option value="' + value.kodesuboutput + '">' +value.kodesuboutput+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#suboutput').on('change', function () {
            var  kodesuboutput = this.value;
            var  kodekegiatan = document.getElementById('kegiatan').value;
            var  kodeoutput = document.getElementById('output').value;
            $.ajax({
                url: "{{url('ambildatakomponen')}}",
                type: "POST",
                data: {
                    kodekegiatan: kodekegiatan,
                    kodeoutput: kodeoutput,
                    kodesuboutput: kodesuboutput,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var komponen = document.getElementById('komponenawal').value;
                    $('#komponen').html('<option value="">Pilih Komponen</option>');
                    $.each(result.komponen, function (key, value) {
                        if (komponen === value.kodekomponen) {
                            $('select[name="komponen"]').append('<option value="'+value.kodekomponen+'" selected>'+value.kodekomponen+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#komponen").append('<option value="' + value.kodekomponen + '">' +value.kodekomponen+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#komponen').on('change', function () {
            var  kodekomponen = this.value;
            var  kodekegiatan = document.getElementById('kegiatan').value;
            var  kodeoutput = document.getElementById('output').value;
            var  kodesuboutput = document.getElementById('suboutput').value;
            $.ajax({
                url: "{{url('ambildatasubkomponen')}}",
                type: "POST",
                data: {
                    kodekegiatan: kodekegiatan,
                    kodeoutput: kodeoutput,
                    kodesuboutput: kodesuboutput,
                    kodekomponen: kodekomponen,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var subkomponen = document.getElementById('subkomponenawal').value;
                    $('#subkomponen').html('<option value="">Pilih SubKomponen</option>');
                    $.each(result.subkomponen, function (key, value) {
                        if (subkomponen === value.kodesubkomponen) {
                            $('select[name="subkomponen"]').append('<option value="'+value.kodesubkomponen+'" selected>'+value.kodesubkomponen+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#subkomponen").append('<option value="' + value.kodesubkomponen + '">' +value.kodesubkomponen+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#iddeputi').on('change', function () {
            var iddeputi = this.value;

            $.ajax({
                url: "{{url('ambildatabiro')}}",
                type: "POST",
                data: {
                    iddeputi: iddeputi,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var idbiro = document.getElementById('idbiroawal').value;
                    $('#idbiro').html('<option value="">Pilih Biro</option>');
                    $.each(result.biro, function (key, value) {
                        if (idbiro == value.id) {
                            $('select[name="idbiro"]').append('<option value="'+value.id+'" selected>'+value.uraianbiro+'</option>').trigger('change')
                        }else{
                            $("#idbiro").append('<option value="' + value.id + '">' + value.uraianbiro + '</option>');
                        }

                    });
                }

            });
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
    </script>
@endsection
