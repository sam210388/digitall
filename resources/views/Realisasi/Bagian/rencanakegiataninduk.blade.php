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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahrencana"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrencanainduk" class="table table-bordered table-striped tabelrencanainduk">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Uraian Kegiatan</th>
                                <th>Bulan Pelaksanaan</th>
                                <th>Bulan Pencairan</th>
                                <th>Total Rencana</th>
                                <th>Status Rencana</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Uraian Kegiatan</th>
                                <th>Bulan Pelaksanaan</th>
                                <th>Bulan Pencairan</th>
                                <th>Total Rencana</th>
                                <th>Status Rencana</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="modal fade" id="ajaxModelRencanaKegiatan" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formrencanainduk" name="formrencanainduk" class="form-horizontal">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="idbagian" id="idbagian">
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
                                                <label for="uraiankegiatan" class="col-sm-6 control-label">Uraian Kegiatan</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control uraiankegiatan" id="uraiankegiatan" name="uraiankegiatan" placeholder="Uraian Kegiatan" value=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Bulan Pelaksanaan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control bulanpelaksanaan" name="bulanpelaksanaan" id="bulanpelaksanaan" style="width: 100%;">
                                                        <option value="">Pilih Bulan</option>
                                                        <option value="1">Januari</option>
                                                        <option value="2">Februari</option>
                                                        <option value="3">Maret</option>
                                                        <option value="4">April</option>
                                                        <option value="5">Mei</option>
                                                        <option value="6">Juni</option>
                                                        <option value="7">Juli</option>
                                                        <option value="8">Agustus</option>
                                                        <option value="9">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Bulan Pencairan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control bulanpencairan" name="bulanpencairan" id="bulanpencairan" style="width: 100%;">
                                                        <option value="">Pilih Bulan</option>
                                                        <option value="1">Januari</option>
                                                        <option value="2">Februari</option>
                                                        <option value="3">Maret</option>
                                                        <option value="4">April</option>
                                                        <option value="5">Mei</option>
                                                        <option value="6">Juni</option>
                                                        <option value="7">Juli</option>
                                                        <option value="8">Agustus</option>
                                                        <option value="9">September</option>
                                                        <option value="10">Oktober</option>
                                                        <option value="11">November</option>
                                                        <option value="12">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Rencana</label>
                                                <div class="col-sm-12">
                                                    <input type="number" step="1" class="form-control inputFormat" id="totalrencana" name="totalrencana" placeholder="Total Rencana" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Status Rencana</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control statusrencana" name="statusrencana" id="statusrencana" style="width: 100%;">
                                                        <option value="">Pilih Status</option>
                                                        <option value="Terlaksana">Terlaksana</option>
                                                        <option value="Terjadwal">Terjadwal</option>
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

    <script type="text/javascript">

        // Ketika dokumen telah siap
        // Fungsi untuk menambahkan separator ribuan

        $(function () {
            var table;
            var table2;

            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.kdsatkerdetil').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.pengenal').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.bulanpencairan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.bulanpelaksanaan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.statusrencana').select2({
                width: '100%',
                theme: 'bootstrap4',
            })


            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            // Setup - add a text input to each header cell
            $('#tabelrencanainduk thead th').each( function (i) {
                var title = $('#tabelrencanainduk thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });
            table = $('.tabelrencanainduk').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatarencanakegiatanindukbagian')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name:'bagianpengajuanrelation.uraianbagian'},
                    {data: 'uraiankegiatan', name: 'uraiankegiatan'},
                    {data: 'bulanpelaksanaan', name: 'bulanpelaksanaan'},
                    {data: 'bulanpencairan', name: 'bulanpencairan'},
                    {data: 'totalrencana', name: 'totalrencana'},
                    {data: 'statusrencana', name: 'statusrencana'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                columnDefs: [
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
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


            $('#tambahrencana').click(function () {
                $('#saveBtn').val("tambah");
                $('#formrencanainduk')[0].reset();
                $('#id').val('');
                $('#idbagian').val('');
                $('#kdsatker').val('').trigger('change');
                $('#uraiankegiatan').val('');
                $('#bulanpelaksanaan').val('').trigger('change');
                $('#bulanpencairan').val('').trigger('change');
                $('#totalrencana').val('');
                $('#statusrencana').val('').trigger('change');
                $('#nilairealisasi').val('');
                $('#modelHeading').html("Tambah Rencana Kegiatan");
                $('#ajaxModelRencanaKegiatan').modal('show');

            });


            $('body').on('click', '.detilrencana', function () {
                var idrencanakegiatan = $(this).data("id");
                window.location="{{URL::to('detilrencanakegiatanbagian','')}}"+"/"+idrencanakegiatan;
            });



            $('body').on('click', '.editrencana', function () {
                var id = $(this).data('id');
                $.get("{{ route('rencanakegiatanbagian.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Rencana");
                    $('#saveBtn').val("edit");
                    $('#ajaxModelRencanaKegiatan').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#bulanpelaksanaan').val(data.bulanpelaksanaan).trigger('change');
                    $('#bulanpencairan').val(data.bulanpencairan).trigger('change');
                    $('#uraiankegiatan').val(data.uraiankegiatan);
                    $('#totalrencana').val(data.totalrencana.toString());
                    $('#statusrencana').val(data.statusrencana).trigger('change');
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formrencanainduk');
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
                    url: saveBtn === "tambah" ? "{{route('rencanakegiatanbagian.store')}}":"{{route('rencanakegiatanbagian.update','')}}"+'/'+id,
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
                        $('#formrencanakegiatanbagian').trigger("reset");
                        $('#ajaxModelRencanaKegiatan').modal('hide');
                        $('#saveBtn').html('Simpan Data');
                        table.ajax.reload(null, false);
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

            $('body').on('click', '.deleterencana', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('rencanakegiatanbagian.destroy','') }}"+'/'+id,
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

                            $('#saveBtnDetil').html('Simpan Data');
                        },
                    });
                }
            });

            $('body').on('click', '.setterlaksana', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Merubah Status Rencana Menjadi Terlaksana?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('setrencanaterlaksana','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Status Rencana Berhasil Diubah',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Perubahan Status Rencana Gagal',
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

            $('body').on('click', '.setterjadwal', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Merubah Status Rencana Menjadi Terjadwal?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('setrencanaterjadwal','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Status Rencana Berhasil Diubah',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Perubahan Status Rencana Gagal',
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


        });

    </script>
@endsection
