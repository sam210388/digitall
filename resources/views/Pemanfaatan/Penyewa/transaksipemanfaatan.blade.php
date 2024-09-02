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
                        <table id="tabeltransaksipemanfaatan" class="table table-bordered table-striped tabeltransaksipemanfaatan">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Penyewa</th>
                                <th>Penanggungjawab</th>
                                <th>Objek</th>
                                <th>Peruntukan Sewa</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Periodisitas</th>
                                <th>Status Pemanfaatan</th>
                                <th>Nilai</th>
                                <th>Nomor SK</th>
                                <th>Tanggal SK</th>
                                <th>File SK</th>
                                <th>Terakhir Edit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Penyewa</th>
                                <th>Penanggungjawab</th>
                                <th>Objek</th>
                                <th>Peruntukan Sewa</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Periodisitas</th>
                                <th>Status Pemanfaatan</th>
                                <th>Nilai</th>
                                <th>Nomor SK</th>
                                <th>Tanggal SK</th>
                                <th>File SK</th>
                                <th>Terakhir Edit</th>
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
                                        <form id="formtransaksipemanfaatan" name="formtransaksipemanfaatan" class="form-horizontal">
                                            <input type="hidden" name="fileskawal" id="fileskawal">
                                            <input type="hidden" name="idpenyewaawal" id="idpenyewaawal">
                                            <input type="hidden" name="idobjekawal" id="idobjekawal">
                                            <input type="hidden" name="idpenanggungjawabawal" id="idpenanggungjawabawal">
                                            <input type="hidden" name="peruntukanawal" id="peruntukanawal">
                                            <input type="hidden" name="periodisitasawal" id="periodisitasawal">
                                            <input type="hidden" name="startdate" id="startdate">
                                            <input type="hidden" name="enddate" id="enddate">
                                            <input type="hidden" name="id" id="id">
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Penyewa</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idpenyewa" name="idpenyewa" id="idpenyewa" style="width: 100%;">
                                                        <option value="">Pilih Penyewa</option>
                                                        @foreach($penyewa as $data)
                                                            <option value="{{ $data->id }}">{{ $data->namapenyewa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Penanggungjawab</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idpenanggungjawab" name="idpenanggungjawab" id="idpenanggungjawab" style="width: 100%;">
                                                        <option value="">Pilih Penanggungjawab</option>
                                                        @foreach($penyewa as $data)
                                                            <option value="{{ $data->id }}">{{ $data->namapenyewa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Objek Sewa</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idobjek" name="idobjek" id="idobjek" style="width: 100%;">
                                                        <option value="">Pilih Objek</option>
                                                        @foreach($penyewa as $data)
                                                            <option value="{{ $data->id }}">{{ $data->namapenyewa }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Peruntukan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control peruntukan" name="peruntukan" id="peruntukan" style="width: 100%;">
                                                        <option value="">Pilih Peruntukan</option>
                                                        <option value="Bisnis">Bisnis</option>
                                                        <option value="Sosial">Sosial</option>
                                                        <option value="Non Bisnis">Non Bisnis</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                <label>Periode Sewa</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="periodesewa" id="periodesewa">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="penyewa" class="col-sm-6 control-label">Periodisitas</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control periodisitas" name="periodisitas" id="periodisitas" style="width: 100%;">
                                                        <option value="">Pilih Periodisitas</option>
                                                        <option value="Bulanan">Bulanan</option>
                                                        <option value="Triwulanan">Triwulanan</option>
                                                        <option value="Semesteran">Semesteran</option>
                                                        <option value="Tahunan">Tahunan</option>
                                                        <option value="Tiga Tahunan">Tiga Tahunan</option>
                                                        <option value="Lima Tahunan">Lima Tahunan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="noktp" class="col-sm-6 control-label">No SK</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="nosk" name="nosk" placeholder="No SK" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkfilesk" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallihatsk">Lihat SK</a>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                <label>Tanggal SK</label>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="tanggalsk" id="tanggalsk" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask readonly>
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
            $('.idpenyewa').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idpenanggungjawab').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.idobjek').select2({
                width: '100%',
                theme: 'bootstrap4',

            })
            $('.peruntukan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            $( "#periodesewa" ).daterangepicker({
                opens:'left'
                },function(start, end){
                document.getElementById('startdate').value = start.format('YYYY-MM-DD');
                document.getElementById('enddate').value = end.format('YYYY-MM-DD');
                }

            );



            $( "#tanggalsk" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });


            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabeltransaksipemanfaatan tfoot th').each( function (i) {
                var title = $('#tabeltransaksipemanfaatan thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeltransaksipemanfaatan').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatatransaksipemanfaatan')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'idpenyewa', name: 'idpenyewa'},
                    {data: 'idpenanggungjawab', name: 'idpenanggungjawab'},
                    {data: 'idobjeksewa', name: 'idobjeksewa'},
                    {data: 'peruntukansewa', name: 'peruntukansewa'},
                    {data: 'tanggalawalsewa', name: 'tanggalawalsewa'},
                    {data: 'tanggalakhirsewa', name: 'tanggalakhirsewa'},
                    {data: 'periodisitassewa', name: 'periodisitassewa'},
                    {data: 'statustransaksi', name: 'statustransaksi'},
                    {data: 'nilaitransaksi', name: 'nilaitransaksi'},
                    {data: 'nomorsk', name: 'nomorsk'},
                    {data: 'tanggalsk', name: 'tanggalsk'},
                    {data: 'filesk', name: 'filesk'},
                    {data: 'dieditpada', name: 'dieditpada'},
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
            $('#tambahtransaksi').click(function () {
                $('#saveBtn').val("tambah");
                $('#idpenyewaawal').val('');
                $('#idpenanggungjawabawal').val('');
                $('#idobjekawal').val('');
                $('#peruntukanawal').val('');
                $('#periodesitasawal').val('');
                $('#id').val('');
                $('#modelHeading').html("Tambah Transaksi");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('transaksipemanfaatan.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Penanggungjawab");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idpenanggungjawab').val(data.id);
                    $('#filektpawal').val(data.filektp);
                    $('#fileskawal').val(data.filesk);
                    $('#namapenanggungjawab').val(data.namapenanggungjawab);
                    $('#nomorktp').val(data.nomorktp);
                    $('#jabatan').val(data.jabatan);
                    $('#dasarjabatan').val(data.dasarjabatan);
                    $('#tanggaldasar').val(data.tanggaldasar);
                    $('#lokasi').val(data.lokasi);
                    $('#status').val(data.status).trigger('change');
                    $('#userpenyewa').val(data.userpenyewa).trigger('change');
                    document.getElementById('aktuallihatktp').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/ktp')}}"+"/"+data.filektp
                    document.getElementById('aktuallihatsk').href = "{{env('APP_URL')."/".asset('storage/dokpemanfaatan/skpenanggungjawab')}}"+"/"+data.filesk
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
                let form = document.getElementById('formtransaksipemanfaatan');
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
                    url: saveBtn === "tambah" ? "{{route('transaksipemanfaatan.store')}}":"{{route('transaksipemanfaatan.update','')}}"+'/'+id,
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
                        $('#formtransaksipemanfaatan').trigger("reset");
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
                        url: "{{ route('transaksipemanfaatan.destroy','') }}"+'/'+id,
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
        });

    </script>
@endsection
