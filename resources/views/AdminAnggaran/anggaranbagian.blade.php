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
                        <div class="btn-group float-sm-right" role="group">
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-header">
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="seluruhanggaran"> Seluruh Anggaran</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="anggaransetjenkosong"> Anggaran Setjen Kosong : {{$anggaransetjenkosong}}</a>
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="anggarandewankosong"> Anggaran Dewan Kosong : {{$anggarandewankosong}}</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="status" class="col-sm-6 control-label">Pilih Kondisi</label>
                            <div class="col-sm-12">
                                <select class="form-control idstatus" name="idstatus" id="idstatus" style="width: 100%;">
                                    <option value="1" selected>Seluruh Anggaran</option>
                                    <option value="2">Anggaran Setjen Kosong</option>
                                    <option value="3">Anggaran Dewan Kosong</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelanggaranbagian" class="table table-bordered table-striped tabelanggaranbagian">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>Pengenal</th>
                                <th>Bagian</th>
                                <th>Biro</th>
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
                                <th>Pengenal</th>
                                <th>Bagian</th>
                                <th>Biro</th>
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
                                        <form id="formanggaranbagian" name="formanggaranbagian" class="form-horizontal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <input type="hidden" name="iddeputiawal" id="iddeputiawal">
                                            <input type="hidden" name="indeksanggaran" id="indeksanggaran">
                                            <div class="form-group">
                                                <label for="anggarandipilih" class="col-sm-6 control-label">Anggaran Dipilih</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="anggarandipilih" name="anggarandipilih" placeholder="Anggaran Dipilih" value="" required></textarea>
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
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        $('.iddeputi').select2({
            width: '100%',
            theme: 'bootstrap4',
        })

        $('.idstatus').select2({
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

        $(function () {
            $('body').on('click', '.editanggaran', function () {
                var indeksanggaran = $(this).data('id');
                //alert(indeksanggaran);
                $.get("{{ route('anggaranbagian.index') }}" +'/' + indeksanggaran +'/edit', function (data) {
                    $('#modelHeading').html("Edit Bagian");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#anggarandipilih').val(data[0]['indeks']);
                    $('#indeksanggaran').val(data[0]['indeks']);
                    $('#iddeputi').val(data[0]['iddeputi']).trigger('change');
                    $('#idbiro').val(data[0]['idbiro']).trigger('change');
                    $('#idbiroawal').val(data[0]['idbiro']);
                    $('#idbagianawal').val(data[0]['idbagian']);
                    $('#idbagian').val(data[0]['idbagian']).trigger('change');
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
                let form = document.getElementById('formanggaranbagian');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var indeksanggaran = document.getElementById('anggarandipilih').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('anggaranbagian.store')}}":"{{route('anggaranbagian.update','')}}"+'/'+indeksanggaran,
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status === "berhasil"){
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
                        $('#iddeputi').val('').trigger('change');
                        $('#idbiro').val('').trigger('change');
                        $('#idbagian').val('').trigger('change');
                        $('#idbiroawal').val('');
                        $('#idbagianawal').val('');
                        $('#indeksanggaran').val('');
                        $('#formanggaranbagian').trigger("reset");
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

        bsCustomFileInput.init();

        /*------------------------------------------
        --------------------------------------------
        Render DataTable
        --------------------------------------------
        --------------------------------------------*/
        // Setup - add a text input to each footer cell
        $('#tabelanggaranbagian tfoot th').each( function (i) {
            var title = $('#tabelanggaranbagian thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                {"width":"5%"},
            );
        });

        let status = document.getElementById('idstatus').value;
        var table = $('.tabelanggaranbagian').DataTable({
            destroy: true,
            fixedColumn:true,
            scrollX:"100%",
            autoWidth:true,
            processing: true,
            serverSide: false,
            dom: 'lf<"floatright"B>rtip',
            buttons: ['copy','excel','pdf','csv','print'],
            ajax: "{{route('getdataanggaranbagian','')}}"+'/'+status,
            columns: [
                {data: 'id', name: 'id'},
                {data: 'tahunanggaran', name: 'tahunanggaran'},
                {data: 'kdsatker', name: 'kdsatker'},
                {data: 'pengenal', name: 'pengenal'},
                {data: 'idbagian', name: 'idbagian'},
                {data: 'idbiro', name: 'idbiro'},
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

        $('#idstatus').on('change',function (){
            let status = document.getElementById('idstatus').value;
            var table = $('#tabelanggaranbagian').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: false,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax: "{{route('getdataanggaranbagian','')}}"+'/'+status,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'idbagian', name: 'idbagian'},
                    {data: 'idbiro', name: 'idbiro'},
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
        })


    </script>

@endsection
