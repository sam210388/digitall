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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahruangan"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelruangan" class="table table-bordered table-striped tabelruangan">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Area</th>
                                <th>Sub Area</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Kode ruangan</th>
                                <th>Uraian ruangan</th>
                                <th>Status DBR</th>
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
                                <th>Lantai</th>
                                <th>Kode ruangan</th>
                                <th>Uraian ruangan</th>
                                <th>Status DBR</th>
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
                                        <form id="formruangan" name="formruangan" class="form-horizontal">
                                            <input type="hidden" name="idsubareaawal" id="idsubareaawal">
                                            <input type="hidden" name="idgedungawal" id="idgedungawal">
                                            <input type="hidden" name="idlantaiawal" id="idlantaiawal">
                                            <input type="hidden" name="idruangan" id="idruangan">
                                            <input type="hidden" name="iddeputiawal" id="iddeputiawal">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
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
                                                <label for="Lantai" class="col-sm-6 control-label">Lantai</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idlantai" name="idlantai" id="idlantai" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Kode ruangan" class="col-sm-6 control-label">Kode ruangan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="koderuangan" name="koderuangan" placeholder="Masukan Kode ruangan" value="" maxlength="4" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian ruangan" class="col-sm-6 control-label">Uraian ruangan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianruangan" name="uraianruangan" placeholder="Masukan Uraian Sub Area" value="" maxlength="200" required="">
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
            $('.idlantai').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.iddeputi').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idbiro').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idbagian').select2({
                width: '100%',
                theme: 'bootstrap4',

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
            $('#tabelruangan tfoot th').each( function (i) {
                var title = $('#tabelruangan thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabelruangan').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:{
                    type: 'GET',
                    url:'{{route('getdataruangan')}}',
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'area', name: 'arearelation.uraianarea'},
                    {data: 'subarea', name: 'subarearelation.uraiansubarea'},
                    {data: 'gedung', name: 'gedungrelation.uraiangedung'},
                    {data: 'lantai', name: 'lantairelation.uraianlantai'},
                    {data: 'koderuangan', name: 'koderuangan'},
                    {data: 'uraianruangan', name: 'uraianruangan'},
                    {data: 'dibuatdbr', name: 'statusruanganrelation.uraianstatus'},
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


            $('#tambahruangan').click(function () {
                $('#saveBtn').val("tambah");
                $('#idarea').val('');
                $('#idsubarea').val('');
                $('#idgedung').val('');
                $('#idlantai').val('');
                $('#formruangan').trigger("reset");
                $('#modelHeading').html("Tambah Ruangan");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editruangan', function () {
                var idruangan = $(this).data('id');
                $.get("{{ route('ruangan.index') }}" +'/' + idruangan +'/edit', function (data) {
                    $('#modelHeading').html("Edit ruangan");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idruangan').val(data.id);
                    $('#idsubareaawal').val(data.idsubarea);
                    $('#idgedungawal').val(data.idgedung);
                    $('#idlantaiawal').val(data.idlantai);
                    $('#idarea').val(data.idarea).trigger('change');
                    $('#idsubarea').val(data.idsubarea).trigger('change');
                    $('#idgedung').val(data.idgedung).trigger('change');
                    $('#idlantai').val(data.idlantai).trigger('change');
                    $('#koderuangan').val(data.koderuangan);
                    $('#uraianruangan').val(data.uraianruangan);
                    $('#iddeputi').val(data.iddeputi).trigger('change');
                    $('#iddeputiawal').val(data.iddeputi);
                    $('#idbiro').val(data.idbiro).trigger('change');
                    $('#idbiroawal').val(data.idbiro);
                    $('#idbagian').val(data.idbagian).trigger('change');
                    $('#idbagianawal').val(data.idbagian);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formruangan');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                let statusdbr = document.getElementById('statusdbr').value;
                var id = document.getElementById('idruangan').value;
                fd.append('saveBtn',saveBtn)
                fd.append('statusdbr',statusdbr)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('ruangan.store')}}":"{{route('ruangan.update','')}}"+'/'+id,
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
                        $('#formruangan').trigger("reset");
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

            $('body').on('click', '.deleteruangan', function () {
                var idruangan = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('buatdbr','') }}"+'/'+idruangan,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'DBR atas Ruangan dengan ID '+idruangan+' Berhasil Dibuat',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Buat DBR Gagal',
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
                        },
                    });
                }
            });

            $('body').on('click', '.lihatdbr', function () {
                var iddbr = $(this).data("id");
                window.location="{{URL::to('lihatdbr')}}"+"/"+iddbr;

            });

            $('body').on('click', '.buatdbr', function () {
                var idruangan = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Membuat DBR untuk Ruangan Ini?")){
                    window.location="{{URL::to('buatdbr')}}"+"/"+idruangan;
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
            $('#idgedung').on('change', function () {
                var idgedung = this.value;
                $.ajax({
                    url: "{{url('ambildatalantai')}}",
                    type: "POST",
                    data: {
                        idgedung: idgedung,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var idlantai = document.getElementById('idlantaiawal').value;
                        $('#idlantai').html('<option value="">Pilih Lantai</option>');
                        $.each(result.lantai, function (key, value) {
                            if (idlantai == value.id) {
                                $('select[name="idlantai"]').append('<option value="'+value.id+'" selected>'+value.uraianlantai+'</option>').trigger('change')
                            }else{
                                $("#idlantai").append('<option value="' + value.id + '">' + value.uraianlantai + '</option>');
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
        });

    </script>
@endsection
