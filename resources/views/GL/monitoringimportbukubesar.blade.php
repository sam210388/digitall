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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahrefgl">Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelmonitoring" class="table table-bordered table-striped tabelmonitoring">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tahun</th>
                                <th>Periode</th>
                                <th>Kode Satker</th>
                                <th>Status</th>
                                <th>Tgl Update</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tahun</th>
                                <th>Periode</th>
                                <th>Kode Satker</th>
                                <th>Status</th>
                                <th>Tgl Update</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal fade" id="ajaxModel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modelHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formrefgl" name="formrefgl" class="form-horizontal">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="Periode" class="col-sm-6 control-label">Periode</label>
                                            <div class="col-sm-12">
                                                <select class="form-control periode" name="periode" id="periode" style="width: 100%;">
                                                    <option value="">Periode</option>
                                                    <option value="01">Januari</option>
                                                    <option value="02">Februari</option>
                                                    <option value="03">Maret</option>
                                                    <option value="04">April</option>
                                                    <option value="05">Mei</option>
                                                    <option value="06">Juni</option>
                                                    <option value="07">Juli</option>
                                                    <option value="08">Agustus</option>
                                                    <option value="09">September</option>
                                                    <option value="10">Oktober</option>
                                                    <option value="11">November</option>
                                                    <option value="12">Desember</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="KodeSatker" class="col-sm-6 control-label">Kode Satker</label>
                                            <div class="col-sm-12">
                                                <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;">
                                                    <option value="">Pilih Satker</option>
                                                    <option value="001012">001012</option>
                                                    <option value="001030">001030</option>
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
    <!-- /.content -->
    <script type="text/javascript">
        $(function () {
            $('.periode').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.kdsatker').select2({
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
            $('#tabelmonitoring tfoot th').each( function (i) {
                var title = $('#tabelmonitoring thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelmonitoring').DataTable({
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('monitoringimportbukubesar.index')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'periode', name: 'periode'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'statusimport', name: 'statusimport'},
                    {data: 'tanggalterakhir', name: 'tanggalterakhir'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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

            $('#tambahrefgl').click(function () {
                $('#saveBtn').val("tambah");
                $('#id').val('');
                $('#formrefgl').trigger("reset");
                $('#modelHeading').html("Tambah Ref GL");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formrefgl');
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
                    url: saveBtn === "tambah" ? "{{route('monitoringimportbukubesar.store')}}":"{{route('monitoringimportbukubesar.update','')}}"+'/'+id,
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
                        $('#formrefgl').trigger("reset");
                        $('#kdsatker').val('').trigger('change');
                        $('#periode').val('').trigger('change');
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

            $('body').on('click', '.deleterefgl', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('monitoringimportbukubesar.destroy','') }}"+'/'+id,
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

            $('body').on('click', '.editrefgl', function () {
                var id = $(this).data('id');
                $.get("{{ route('monitoringimportbukubesar.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#periode').val(data.periode).trigger('change');
                })
            });

            $('body').on('click', '.importgl', function (e) {
                var id = $(this).data('id');
                if(confirm("Apakah Anda Yakin Mau Import GL untuk Data "+id+" ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importgl')}}"+"/"+id;
                }
            });
        });
    </script>

@endsection
