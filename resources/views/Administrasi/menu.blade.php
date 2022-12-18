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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahmenu"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelmenu" class="table table-bordered table-striped tabelmenu">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Uraian Menu</th>
                                <th>Url Menu</th>
                                <th>Icon Menu</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Uraian Menu</th>
                                <th>Url Menu</th>
                                <th>Icon Menu</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="modal fade" id="ajaxModel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formmenu" name="formmenu" class="form-horizontal">
                                            <input type="hidden" name="idmenu" id="idmenu">
                                            <div class="form-group">
                                                <label for="Uraian Menu" class="col-sm-6 control-label">Uraian Menu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraianmenu" name="uraianmenu" placeholder="Masukan Uraian Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Url Menu" class="col-sm-6 control-label">Url Menu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="url_menu" name="url_menu" placeholder="Masukan Url Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Icon Menu" class="col-sm-6 control-label">Icon Menu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="icon_menu" name="icon_menu" placeholder="Icon Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Status Menu" class="col-sm-6 control-label">Status Menu</label>
                                                <input type="checkbox" name="active" id="active" checked data-bootstrap-switch data-on="on" data-off="off" data-off-color="danger" data-on-color="success">
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
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelmenu tfoot th').each( function (i) {
                var title = $('#tabelmenu thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelmenu').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('menu.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'uraianmenu', name: 'uraianmenu'},
                    {data: 'url_menu', name: 'url_menu'},
                    {data: 'icon_menu', name: 'icon_menu'},
                    {data: 'active', name: 'active'},

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
            $('#tambahmenu').click(function () {
                $('#saveBtn').val("tambahmenu");
                $('#idmenu').val('');
                $('#formmenu').trigger("reset");
                $('#modelHeading').html("Tambah Menu");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editmenu', function () {
                var idmenu = $(this).data('id');
                $.get("{{ route('menu.index') }}" +'/' + idmenu +'/edit', function (data) {
                    $('#modelHeading').html("Edit Menu");
                    $('#saveBtn').val("editmenu");
                    $('#ajaxModel').modal('show');
                    $('#idmenu').val(data.id);
                    $('#uraianmenu').val(data.uraianmenu);
                    $('#url_menu').val(data.url_menu);
                    $('#icon_menu').val(data.icon_menu);
                    if (data.active == "on"){
                        $('#active').prop('checked',true).change();
                    }else{
                        $('#active').prop('checked',false).change();
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

                $.ajax({
                    data: $('#formmenu').serialize(),
                    url: "{{ route('menu.store') }}",
                    type: "POST",
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
                        $('#formmenu').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#saveBtn').html('Simpan Data');
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Simpan Data');
                    }
                });
            });

            /*------------------------------------------
            --------------------------------------------
            Delete Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.deletemenu', function () {

                var idmenu = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('menu.store') }}"+'/'+idmenu,
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
                };
            });

        });

    </script>
@endsection
