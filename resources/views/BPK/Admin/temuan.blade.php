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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtemuan"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeltemuan" class="table table-bordered table-striped tabeltemuan">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Temuan</th>
                                <th>Kondisi</th>
                                <th>Kriteria</th>
                                <th>Sebab</th>
                                <th>Akibat</th>
                                <th>Nilai</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Penyelesaian</th>
                                <th>Didata Oleh</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Tahun</th>
                                <th>Tahun</th>
                                <th>Temuan</th>
                                <th>Kondisi</th>
                                <th>Kriteria</th>
                                <th>Sebab</th>
                                <th>Akibat</th>
                                <th>Nilai</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Penyelesaian</th>
                                <th>Didata Oleh</th>
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
                                        <form id="formtemuan" name="formtemuan" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="idtemuan" id="idtemuan">
                                            <input type="hidden" name="statusawal" id="statusawal">
                                            <input type="hidden" name="buktiawal" id="buktiawal">
                                            <input type="hidden" name="created_by_awal" id="created_by_awal">
                                            <div class="form-group">
                                                <label for="tahunanggaran" class="col-sm-6 control-label">Tahun Anggaran</label>
                                                <div class="col-sm-12">
                                                <select class="form-control tahunanggaran" name="tahunanggaran" id="tahunanggaran" style="width: 100%;">
                                                    <option value="">Tahun Anggaran</option>
                                                    @foreach($datatahunanggaran as $data)
                                                        <option value="{{ $data->kode }}">{{ $data->tahunanggaran }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="temuan" class="col-sm-6 control-label">Temuan</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="temuan" name="temuan" placeholder="Temuan" value="" required style="width: 100%;"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kondisi" class="col-sm-6 control-label">Kondisi</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="kondisi" name="kondisi" placeholder="Kondisi" value="" required style="width: 100%;"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kriteria" class="col-sm-6 control-label">Kriteria</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="kriteria" name="kriteria" placeholder="Kriteria" value="" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sebab" class="col-sm-6 control-label">Sebab</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="sebab" name="sebab" placeholder="Sebab" value="" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="akibat" class="col-sm-6 control-label">Akibat</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="akibat" name="akibat" placeholder="Akibat" value="" required></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilai" class="col-sm-6 control-label">Nilai</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="nilai" name="nilai" placeholder="Nilai" value="">
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <label for="bukti" class="col-sm-6 control-label">Upload Bukti</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="bukti" name="bukti">
                                                            <label class="custom-file-label" for="exampleInputFile">Pilih file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkbukti" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallinkbukti">Lihat Bukti</a>
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

            $('.tahunanggaran').select2({
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
            $('#tabeltemuan tfoot th').each( function (i) {
                var title = $('#tabeltemuan thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' )
            });
            var table = $('.tabeltemuan').DataTable({
                fixedColumn:true,
                scrollX:true,
                autoWidth:false,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('temuan.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'temuan', name: 'temuan'},
                    {data: 'kondisi', name: 'kondisi'},
                    {data: 'kriteria', name: 'kriteria'},
                    {data: 'sebab', name: 'sebab'},
                    {data: 'akibat', name: 'akibat'},
                    {data: 'nilai', name: 'nilai'},
                    {data: 'bukti', name: 'bukti'},
                    {data: 'status', name: 'status'},
                    {data: 'penyelesaian', name: 'penyelesaian'},
                    {data: 'created_by', name: 'created_by'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                column: [
                    { "width": "3%", "targets": [0] },
                    { "width": "7%", "targets": [1] },
                    { "width": "15%", "targets": [2] },
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
            $('#tambahtemuan').click(function () {
                $('#saveBtn').val("tambah");
                $('#idtemuan').val('');
                $('#formtemuan').trigger("reset");
                $('#modelHeading').html("Tambah Temuan");
                document.getElementById('aktuallinkbukti').href = "#"
                $('#linkbukti').hide();
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittemuan', function () {
                var idtemuan = $(this).data('id');
                $.get("{{ route('temuan.index') }}" +'/' + idtemuan +'/edit', function (data) {
                    $('#modelHeading').html("Edit Temuan");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idtemuan').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran).trigger('change');
                    $('#buktiawal').val(data.bukti);
                    $('#temuan').val(data.temuan);
                    $('#kondisi').val(data.kondisi);
                    $('#kriteria').val(data.kriteria);
                    $('#sebab').val(data.sebab);
                    $('#akibat').val(data.akibat);
                    $('#nilai').val(data.nilai);
                    $('#statusawal').val(data.status);
                    $('#created_by_awal').val(data.created_by);
                    document.getElementById('aktuallinkbukti').href = "{{env('APP_URL')."/".asset('storage')}}"+"/"+data.bukti
                    $('#linkbukti').show();




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
                let form = document.getElementById('formtemuan');
                let fd = new FormData(form);
                let bukti = $('#bukti')[0].files;
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('idtemuan').value;

                fd.append('bukti',bukti[0])
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('temuan.store')}}":"{{route('temuan.update','')}}"+'/'+id,
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

                        $('#formtemuan').trigger("reset");
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
            $('body').on('click', '.deleterekomendasi', function () {

                var idtemuan = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('temuan.destroy','') }}"+'/'+idtemuan,
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
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });

        $('body').on('click', '.lihatrekomendasi', function () {
            var idtemuan = $(this).data("id");
            window.location="{{URL::to('tampilrekomendasi')}}"+"/"+idtemuan;
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
