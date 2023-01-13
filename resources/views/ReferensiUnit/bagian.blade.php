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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahbagian"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelbagian" class="table table-bordered table-striped tabelbagian">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Deputi</th>
                                <th>Biro</th>
                                <th>Uraian Bagian</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Deputi</th>
                                <th>Biro</th>
                                <th>Uraian Bagian</th>
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
                                        <form id="formbagian" name="formbagian" class="form-horizontal">
                                            <input type="hidden" name="idbagian" id="idbagian">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <div class="form-group">
                                                <label for="deputi" class="col-sm-6 control-label">Deputi</label>
                                                <select class="form-control iddeputi" name="iddeputi" id="iddeputi" style="width: 100%;">
                                                    <option value="">Pilih Deputi</option>
                                                    @foreach($datadeputi as $data)
                                                        <option value="{{ $data->id }}">{{ $data->uraiandeputi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Biro" class="col-sm-6 control-label">Biro</label>
                                                <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian bagian" class="col-sm-6 control-label">Uraian bagian</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianbagian" name="uraianbagian" placeholder="Masukan Uraian Bagian" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Status bagian" class="col-sm-6 control-label">Status</label>
                                                <input type="checkbox" name="status" id="status" data-bootstrap-switch data-on="on" data-off="off" data-off-color="danger" data-on-color="success">
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
            $('.iddeputi').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.idbiro').select2({
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
            $('#tabelbagian tfoot th').each( function (i) {
                var title = $('#tabelbagian thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelbagian').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('bagian.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'iddeputi', name: 'iddeputi'},
                    {data: 'idbiro', name: 'idbiro'},
                    {data: 'uraianbagian', name: 'uraianbagian'},
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
            $('#tambahbagian').click(function () {
                $('#saveBtn').val("tambah");
                $('#idbagian').val('');
                $('#formbagian').trigger("reset");
                $('#modelHeading').html("Tambah bagian");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editbagian', function () {
                var idbagian = $(this).data('id');
                $.get("{{ route('bagian.index') }}" +'/' + idbagian +'/edit', function (data) {
                    $('#modelHeading').html("Edit Bagian");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idbagian').val(data.id);
                    $('#iddeputi').val(data.iddeputi).trigger('change');
                    $('#idbiroawal').val(data.idbiro);
                    $('#uraianbagian').val(data.uraianbagian);
                    if (data.status == "on"){
                        $('#status').prop('checked',true).change();
                    }else{
                        $('#status').prop('checked',false).change();
                    }

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
                let form = document.getElementById('formbagian');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('idbagian').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({

                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('bagian.store')}}":"{{route('bagian.update','')}}"+'/'+id,
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
                        $('#iddeputi').val('').trigger('change');
                        $('#idbiroawal').val('');
                        $('#formbagian').trigger("reset");
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
            $('body').on('click', '.deletebagian', function () {

                var idbagian = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('bagian.destroy','') }}"+'/'+idbagian,
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
                };
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

    </script>
@endsection
