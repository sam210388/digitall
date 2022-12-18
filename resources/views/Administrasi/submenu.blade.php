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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahsubmenu"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelsubmenu" class="table table-bordered table-striped tabelsubmenu">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Uraian SubMenu</th>
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
                                <th>Menu</th>
                                <th>Uraian Menu</th>
                                <th>Url Menu</th>
                                <th>Icon Menu</th>
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
                                        <form id="formsubmenu" name="formsubmenu" class="form-horizontal">
                                            <input type="hidden" name="idsubmenu" id="idsubmenu">
                                            <div class="form-group">
                                                <label for="Menu" class="col-sm-6 control-label">Menu</label>
                                                <select class="form-control idmenu" name="idmenu" id="idmenu" style="width: 100%;">
                                                    <option>Pilih Menu</option>
                                                    @foreach($datamenu as $data)
                                                        <option value="{{ $data->id }}">{{ $data->uraianmenu }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Uraian SubMenu" class="col-sm-6 control-label">Uraian SubMenu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="uraiansubmenu" name="uraiansubmenu" placeholder="Masukan Uraian Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Url Sub Menu" class="col-sm-6 control-label">Url Sub Menu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="url_submenu" name="url_submenu" placeholder="Masukan Url Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Icon Sub Menu" class="col-sm-6 control-label">Icon Sub Menu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="icon_submenu" name="icon_submenu" placeholder="Icon Menu" value="" maxlength="100" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Status SubMenu" class="col-sm-6 control-label">Status</label>
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
            $('.idmenu').select2({
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
            $('#tabelsubmenu tfoot th').each( function (i) {
                var title = $('#tabelsubmenu thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelsubmenu').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('submenu.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'idmenu', name: 'idmenu'},
                    {data: 'uraiansubmenu', name: 'uraiansubmenu'},
                    {data: 'url_submenu', name: 'url_submenu'},
                    {data: 'icon_submenu', name: 'icon_submenu'},
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
            $('#tambahsubmenu').click(function () {
                $('#saveBtn').val("tambahsubmenu");
                $('#idsubmenu').val('');
                $('#formsubmenu').trigger("reset");
                $('#modelHeading').html("Tambah Sub Menu");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editsubmenu', function () {
                var idsubmenu = $(this).data('id');
                $.get("{{ route('submenu.index') }}" +'/' + idsubmenu +'/edit', function (data) {
                    $('#modelHeading').html("Edit Sub Menu");
                    $('#saveBtn').val("editsubmenu");
                    $('#ajaxModel').modal('show');
                    $('#idsubmenu').val(data.id);
                    $('#idmenu').val(data.idmenu).trigger('change');
                    $('#uraiansubmenu').val(data.uraiansubmenu);
                    $('#url_submenu').val(data.url_submenu);
                    $('#icon_submenu').val(data.icon_submenu);
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

                $.ajax({
                    data: $('#formsubmenu').serialize(),
                    url: "{{ route('submenu.store') }}",
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
                        $('#formsubmenu').trigger("reset");
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
            $('body').on('click', '.deletesubmenu', function () {

                var idsubmenu = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('submenu.store') }}"+'/'+idsubmenu,
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
