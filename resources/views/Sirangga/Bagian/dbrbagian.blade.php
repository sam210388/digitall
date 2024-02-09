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
                </div>
                <div class="row">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
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
                    </div>
                    <div class="modal fade" id="ajaxModelTolak" aria-hidden="true" data-focus="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modelHeadingTolak"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formpenolakan" name="formpenolakan" class="form-horizontal" enctype="multipart/form-data">
                                        <input type="hidden" name="iddbr" id="iddbr">
                                        <div class="form-group">
                                            <label for="penolakan" class="col-sm-6 control-label">Alasan Penolakan</label>
                                            <div class="col-sm-12">
                                                <div class="input-group mb-3">
                                                    <textarea class="form-control" id="alasanpenolakan" name="alasanpenolakan" placeholder="Alasan Penolakan" value="" required=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary" id="saveBtnTolak" name="saveBtnTolak" value="create">Simpan Data
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="ajaxModelPerubahan" aria-hidden="true" data-focus="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modelHeadingPerubahan"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formperubahan" name="formperubahan" class="form-horizontal" enctype="multipart/form-data">
                                        <input type="hidden" name="iddbr" id="iddbr">
                                        <div class="form-group">
                                            <label for="jumlahbarangdilaporkan" class="col-sm-6 control-label">Jumlah Barang Diterima</label>
                                            <div class="col-sm-12">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="jumlahbarangdilaporkan" name="jumlah" placeholder="jumlahbarangdilaporkan" value="" maxlength="100" required="" onfocusout="realisasisdperiodeini()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deskripsi" class="col-sm-6 control-label">Barang Yang Diterima</label>
                                            <div class="col-sm-12">
                                                <div class="input-group mb-3">
                                                    <textarea class="form-control" id="deskripsibarangdilaporkan" name="deskripsibarangdilaporkan" placeholder="Barang Yang Diterima" value="" required=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary" id="saveBtnPerubahan" name="saveBtnPerubahan" value="create">Simpan Data
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
    <!-- /.content -->
    <script type="text/javascript">
        $(function () {
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
                ajax: "{{ route('getdatadbrbagian') }}",
                columns: [
                    {data: 'iddbr', name: 'a.iddbr'},
                    {data: 'idpenanggungjawab', name:'a.idpenanggungjawab'},
                    {data: 'idgedung', name: 'a.idgedung'},
                    {data: 'idruangan', name: 'a.idruangan'},
                    {data: 'uraianruangan', name: 'c.uraianruangan'},
                    {data: 'statusdbr', name: 'a.statusdbr'},
                    {data: 'useredit', name: 'a.useredit'},
                    {data: 'terakhiredit', name: 'a.terakhiredit'},
                    {data: 'versike', name: 'a.versike'},
                    {data: 'dokumendbr', name: 'a.dokumendbr'},
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

            $('body').on('click', '.laporpenambahan', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Mengajukan Perubahan Pada DBR Ini?")){
                    $.ajax({
                        url: "{{ url('/laporperubahan') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Pengajuan Perubahan Berhasil Disampaikan ke Admin BMN',
                                    icon: 'success'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Pengajuan Perubahan Gagal Disampaikan ke Admin BMN',
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

            $('body').on('click', '.lihatdbr', function () {
                var iddbr = $(this).data("id");
                window.location="{{URL::to('lihatdbrbagian')}}"+"/"+iddbr;
            });

            $('body').on('click', '.tolakdbr', function () {
                var iddbr = $(this).data('id');
                $('#modelHeadingTolak').html("Tolak DBR");
                $('#saveBtnTolak').val("tolak");
                $('#iddbr').val(iddbr);
                $('#ajaxModelTolak').modal('show');
            });

            $('#saveBtnTolak').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formpenolakan');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtnTolak').value;
                let iddbr = document.getElementById('iddbr').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: "{{route('penolakandbr','')}}"+"/"+iddbr,
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Penolakan DBR Berhasil Disampaikan',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Penolakan DBR Gagal Disampaikan',
                                icon: 'error'
                            })
                        }
                        $('#formpenolakan').trigger("reset");
                        $('#ajaxModelTolak').modal('hide');
                        $('#saveBtnTolak').html('Simpan Data');
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
                        $('#saveBtnTolak').html('Simpan Data');
                    },
                });
            });

            $('body').on('click', '.setujuidbr', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Menyetujui Data Ini? " +
                    "<br>"+
                    "Dengan Persetujuan Ini, Anda Telah Memastikan Bahwa Semua BMN" +
                    " Yang Tercantum Telah Anda Konfirmasi dan Benar Benar ada Secara Fisik, dan Siap Anda Pertanggungjawabkan")){
                    $.ajax({
                        url: "{{ url('/setujuidbr') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Persetujuan DBR Berhasil Dikirim Ke BMN',
                                    icon: 'success'
                                })
                            }else if (data.status == "konfirmbarang"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Persetujuan DBR Gagal, Ada Barang Belum Dikonfirmasi',
                                    icon: 'error'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Persetujuan DBR Gagal',
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
                        },
                    });
                }
            });


        });

    </script>
@endsection
