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
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">DBR Total</span>
                                <span class="info-box-number">
                                 {{$dbrtotal}}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">DBR Final</span>
                                <span class="info-box-number">
                                 {{$dbrfinal}}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">DBR Unit</span>
                                <span class="info-box-number">
                                 {{$dbrunit}}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">DBR Draft</span>
                                <span class="info-box-number">
                                 {{$dbrdraft}}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembalikeruangan"> Kembali ke Ruangan</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabeldbr" class="table table-bordered table-striped tabeldbr">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Penanggungjawab</th>
                                <th>Gedung</th>
                                <th>ID Ruangan</th>
                                <th>Ruangan</th>
                                <th>Status DBR</th>
                                <th>Editor</th>
                                <th>Last Edit</th>
                                <th>Versi</th>
                                <th>Dokumen DBR</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Penanggungjawab</th>
                                <th>Gedung</th>
                                <th>ID Ruangan</th>
                                <th>Ruangan</th>
                                <th>Status DBR</th>
                                <th>Editor</th>
                                <th>Last Edit</th>
                                <th>Versi</th>
                                <th>Dokumen DBR</th>
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
                                        <form id="formdbr" name="formdbr" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="iddbr" id="iddbr">
                                            <div class="form-group">
                                                <label for="idpenanggungjawab" class="col-sm-6 control-label">Penanggungjawab</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idpenanggungjawab" name="idpenanggungjawab" id="idpenanggungjawab" style="width: 100%;">
                                                        <option value="">Pilih Penanggungjawab</option>
                                                        @foreach($datapegawai as $data)
                                                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                                        @endforeach
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
            $('.idpenanggungjawab').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

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
            $('#tabeldbr tfoot th').each( function (i) {
                var title = $('#tabeldbr thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeldbr').DataTable({
                scrollY: true,
                scrollX:true,
                autoWidth:true,
                paging:true,
                deferRender: true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','csv','print'],
                ajax: "{{ route('getdatadbr') }}",
                columns: [
                    {data: 'iddbr', name: 'iddbr'},
                    {data: 'idpenanggungjawab', name:'penanggungjawabrelation.nama'},
                    {data: 'idgedung', name: 'gedungrelation.uraiangedung'},
                    {data: 'idruangan', name: 'idruangan'},
                    {data: 'uraianruangan', name: 'ruanganrelation.uraianruangan'},
                    {data: 'statusdbr', name: 'statusdbrrelation.uraianstatus'},
                    {data: 'useredit', name: 'userrelation.name'},
                    {data: 'terakhiredit', name: 'terakhiredit'},
                    {data: 'versike', name: 'versike'},
                    {data: 'dokumendbr', name: 'dokumendbr'},
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
            $( table.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key == "Enter"){
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );

            $('#kembalikeruangan').click(function () {
                window.location="{{URL::to('ruangan')}}"
            });

            $('body').on('click', '.editdbr', function () {
                var iddbr = $(this).data('id');
                $.get("{{ route('editdbr','') }}" +'/' + iddbr, function (data) {
                    $('#modelHeading').html("Edit DBR");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#iddbr').val(data[0]['iddbr']);
                    $('#idpenanggungjawab').val(data[0]['idpenanggungjawab']).trigger('change');
                })
            });

            $('body').on('click', '.updatepenanggungjawab', function () {
                var iddbr = $(this).data('id');
                $.get("{{ route('updatepenanggungjawab','') }}" +'/' + iddbr, function (data) {
                    $('#modelHeading').html("Update Penanggungjawab");
                    $('#saveBtn').val("updatepenanggungjawab");
                    $('#ajaxModel').modal('show');
                    $('#iddbr').val(data[0]['iddbr']);
                    $('#idpenanggungjawab').val(data[0]['idpenanggungjawab']).trigger('change');
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formdbr');
                let fd = new FormData(form);
                let id = document.getElementById('iddbr').value;
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "edit" ? "{{ route('updatepenanggungjawabdbr','') }}"+'/'+id:"{{ route('aksiupdatepenanggungjawab','') }}"+'/'+id,
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
                        $('#idpenanggungjawab').val('').trigger('change');
                        $('#formdbr').trigger("reset");
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

            $('body').on('click', '.deletedbr', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data DBR Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('deletedbr','') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Dihapus',
                                    icon: 'success'
                                })
                            }else if (data.status == "adabarang"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'DBR Gagal Dihapus, Masih ada Barang',
                                    icon: 'error'
                                })
                            }
                            else{
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

            $('body').on('click', '.kirimkeunit', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Mengirim Data Ini Ke Unit Kerja?")){
                    $.ajax({
                        url: "{{ url('/kirimdbrkeunit') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Dikirim Ke Unit Kerja',
                                    icon: 'success'
                                })
                            }else if (data.status == "adabarang"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Data Gagal Dikirim Ke Unit Kerja, Belum Ada Barang Dalam Ruangan',
                                    icon: 'error'
                                })
                            } else if (data.status == "belumpenanggungjawab"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Data Gagal Dikirim Ke Unit Kerja, Belum Ada Penanggungjawab',
                                    icon: 'error'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Pengiriman Data Gagal',
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
                }
            });

            $('body').on('click', '.perubahanfinal', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Merubah DBR Berstatus Final?")){
                    $.ajax({
                        url: "{{ url('/perubahanfinal') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil") {
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'DBR Final Berhasil Dirubah Status',
                                    icon: 'success'
                                })
                            } else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Perubahan Status DBR Final Gagal, DBR Tidak Ditemukan',
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
                }
            });

            $('body').on('click', '.tambahbarang', function () {
                var iddbr = $(this).data("id");
                window.location="{{URL::to('lihatdbr')}}"+"/"+iddbr;
            });

            $('body').on('click', '.cetakdbr', function () {
                var iddbr = $(this).data("id");
                window.location="{{URL::to('cetakdbr')}}"+"/"+iddbr;
            });

            $('body').on('click', '.cekfisik', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Melakukan Opname Fisik pada DBR Ini?!")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('cekfisik','') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'DBR Siap Untuk Dilakukan Opname Fisik',
                                    icon: 'success'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'DBR Belum Dapat Dilakukan Opname Fisik',
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

            $('body').on('click', '.ingatkanunit', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Mengingatkan Unit Kerja Terkait DBR Ini?")){
                    $.ajax({
                        url: "{{ url('/ingatkanunit') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Unit Kerja Berhasil Diingatkan',
                                    icon: 'success'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Pengiriman Data Gagal',
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
                }
            });


        });

    </script>
@endsection
