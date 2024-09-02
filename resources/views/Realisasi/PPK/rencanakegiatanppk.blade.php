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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtransaksi"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelkasbon" class="table table-bordered table-striped tabelkasbon">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Uraian Kegiatan</th>
                                <th>Bulan Kegiatan</th>
                                <th>Bulan Pencairan</th>
                                <th>Total Kebutuhan</th>
                                <th>Status Kegiatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Uraian Kegiatan</th>
                                <th>Bulan Kegiatan</th>
                                <th>Bulan Pencairan</th>
                                <th>Total Kebutuhan</th>
                                <th>Status Kegiatan</th>
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
                                        <form id="formrencanakegiatanbagian" name="formkasbon" class="form-horizontal">
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
                                                        <textarea class="form-control" id="uraiankegiatan" name="uraiankegiatan" placeholder="Uraian Kegiatan" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bulan" class="col-sm-6 control-label">Bulan Kegiatan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control bulankegiatan" name="bulankegiatan" id="bulankegiatan" style="width: 100%;">
                                                        <option value="">Pilih Bulan</option>
                                                        @foreach($databulan as $data)
                                                            <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bulan" class="col-sm-6 control-label">Bulan Pencairan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control bulanpencairan" name="bulanpencairan" id="bulanpencairan" style="width: 100%;">
                                                        <option value="">Pilih Bulan</option>
                                                        @foreach($databulan as $data)
                                                            <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Kebutuhan</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control" id="totalkebutuhan" name="totalkebutuhan" placeholder="Total Kebutuhan" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Status Kegiatan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control statuskegiatan" name="statuskegiatan" id="statuskegiatan" style="width: 100%;">
                                                        <option value="Terjadwal">Terjadwal</option>
                                                        <option value="Terlaksana">Terlaksana</option>
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
            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',
            })
            $('.bulankegiatan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })
            $('.bulanpencairan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })
            $('.statuskegiatan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })



            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelkasbon tfoot th').each( function (i) {
                var title = $('#tabelkasbon thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelkasbon').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatarencanakegiatanbagian')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'tahunanggaran',name:'tahunanggaran'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name:'bagianpengajuanrelation.uraianbagian'},
                    {data: 'uraiankegiatan', name: 'uraiankegiatan'},
                    {data: 'bulankegiatan', name: 'bulankegiatan'},
                    {data: 'bulanpencairan', name: 'bulanpencairan'},
                    {data: 'totalkebutuhan', name: 'totalkebutuhan'},
                    {data: 'statuskegiatan', name: 'statuskegiatan'},
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
                        targets: 7,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
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
            $('#tambahtransaksi').click(function () {
                $('#saveBtn').val("tambah");
                $('#id').val('');
                $('#idbagian').val('');
                $('#kdsatker').val('');
                $('#uraiankegiatan').val('');
                $('#bulankegiatan').val('').trigger('change');
                $('#bulanpencairan').val('').trigger('change');
                $('#totalkebutuhan').val('');
                $('#statuskegiatan').val('').trigger('change');
                $('#modelHeading').html("Tambah Rencana");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('rencanakegiatanbagian.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Rencana");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#idbagian').val(data.idbagian);
                    $('#uraiankegiatan').val(data.uraiankegiatan);
                    $('#bulankegiatan').val(data.bulankegiatan).trigger('change');
                    $('#bulanpencairan').val(data.bulanpencairan).trigger('change');
                    $('#totalkebutuhan').val(data.totalkebutuhan);
                    $('#statuskegiatan').val(data.statuskegiatan).trigger('change');
                })
            });

            $('body').on('click', '.tambahpengenal', function () {
                var idrencanakegiatan = $(this).data('id');
                window.location="{{URL::to('rencanakegiatanbagiandetil')}}"+"/"+idrencanakegiatan;

            });

            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formrencanakegiatanbagian');
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
            $('body').on('click', '.deletetransaksi', function () {
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

                            $('#saveBtn').html('Simpan Data');
                        },
                    });
                }
            });

            $('body').on('click', '.ajukankeppk', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Mengajukan Kasbon ini Ke PPK?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('ajukanrencanakeppk','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Diajukan ke PPK',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Data Kasbon Gagal Diajukan ke PPK',
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
        });

    </script>
@endsection
