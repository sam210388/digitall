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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahkewenanganmenu"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelkewenanganmenu" class="table table-bordered table-striped tabelkewenanganmenu">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kewenangan</th>
                                <th>Menu</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Kewenangan</th>
                                <th>Menu</th>
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
                                        <form id="formkewenanganmenu" name="formkewenanganmenu" class="form-horizontal">
                                            <input type="hidden" name="id" id="id">
                                            <div class="form-group">
                                                <label for="Menu" class="col-sm-6 control-label">Menu</label>
                                                <select class="form-control idmenu" name="idmenu" id="idmenu" style="width: 100%;">
                                                    <option value="">Pilih Menu</option>
                                                    @foreach($datamenu as $data)
                                                        <option value="{{ $data->id }}">{{ $data->uraianmenu }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Menu" class="col-sm-6 control-label">Kewenangan</label>
                                                <select class="form-control idkewenangan" name="idkewenangan" id="idkewenangan" style="width: 100%;">
                                                    <option value="">Pilih Kewenangan</option>
                                                    @foreach($datakewenangan as $data)
                                                        <option value="{{ $data->id }}">{{ $data->kewenangan }}</option>
                                                    @endforeach
                                                </select>
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
            $('.idmenu').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })
            $('.idkewenangan').select2({
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
            $('#tabelkewenanganmenu tfoot th').each( function (i) {
                var title = $('#tabelkewenanganmenu thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelkewenanganmenu').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('kewenanganmenu.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'idkewenangan', name: 'idkewenangan'},
                    {data: 'idmenu', name: 'idmenu'},
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
            $('#tambahkewenanganmenu').click(function () {
                $('#saveBtn').val("tambah");
                $('#id').val('');
                $('#formkewenanganmenu').trigger("reset");
                $('#modelHeading').html("Tambah Kewenangan Menu");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editkewenanganmenu', function () {
                var id = $(this).data('id');
                $.get("{{ route('kewenanganmenu.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Kewenangan Menu");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#idmenu').val(data.idmenu).trigger('change');
                    $('#idkewenangan').val(data.idkewenangan).trigger('change');
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
                let form = document.getElementById('formkewenanganmenu');
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
                    url: saveBtn === "tambah" ? "{{route('kewenanganmenu.store')}}":"{{route('kewenanganmenu.update','')}}"+'/'+id,
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
                        $('#formkewenanganmenu').trigger("reset");
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
            $('body').on('click', '.deletekewenanganmenu', function () {

                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('kewenanganmenu.destroy','') }}"+'/'+id,
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

    </script>
@endsection
