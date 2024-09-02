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
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdata">Tambah Data</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetildata" class="table table-bordered table-striped tabeldetildata">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>No SP2D</th>
                                <th>Tgl SP2D</th>
                                <th>No SPM</th>
                                <th>Tgl SPM</th>
                                <th>Uraian</th>
                                <th>Nilai SP2D</th>
                                <th>Tanggal BAST</th>
                                <th>Tanggal BAP</th>
                                <th>Tanggal Mulai Perhitungan</th>
                                <th>Tanggal Konversi ADK</th>
                                <th>Selisih Hari</th>
                                <th>Jumlah Hari Libur</th>
                                <th>Jumlah Hari Final</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>No SP2D</th>
                                <th>Tgl SP2D</th>
                                <th>No SPM</th>
                                <th>Tgl SPM</th>
                                <th>Uraian</th>
                                <th>Nilai SP2D</th>
                                <th>Tanggal BAST</th>
                                <th>Tanggal BAP</th>
                                <th>Tanggal Mulai Perhitungan</th>
                                <th>Tanggal Konversi ADK</th>
                                <th>Selisih Hari</th>
                                <th>Jumlah Hari Libur</th>
                                <th>Jumlah Hari Final</th>
                                <th>Status</th>
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
                                        <form action="{{route('importdetilpenyelesaian')}}" method="POST" id="formuploaddetilpenyelesaiantagihan" name="formuploaddetilpenyelesaiantagihan" class="form-horizontal" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Upload File Detail</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".xls,.xlsx" class="custom-file-input" id="filedetail" name="filedetail">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
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

            // Setup - add a text input to each header cell
            $('#tabeldetildata thead th').each( function (i) {
                var title = $('#tabeldetildata thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });

            var table = $('.tabeldetildata').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdetilpenyelesaian')}}",
                columns: [
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'periode', name: 'periode'},
                    {data: 'no_sp2d', name: 'no_sp2d'},
                    {data: 'tgl_sp2d', name: 'tgl_sp2d'},
                    {data: 'no_spm', name: 'no_spm'},
                    {data: 'tgl_spm', name: 'tgl_spm'},
                    {data: 'uraian', name: 'uraian'},
                    {data: 'nilai_sp2d', name: 'nilai_sp2d'},
                    {data: 'tgl_bast', name: 'tgl_bast'},
                    {data: 'tgl_bap', name: 'tgl_bap'},
                    {data: 'tgl_perhitungan', name: 'tgl_perhitungan'},
                    {data: 'tgl_konversi_adk', name: 'tgl_konversi_adk'},
                    {data: 'selisih_hari', name: 'selisih_hari'},
                    {data: 'jumlah_hari_libur', name: 'jumlah_hari_libur'},
                    {data: 'jumlah_hari_final', name: 'jumlah_hari_final'},
                    {data: 'status', name: 'status'},
                ],
                columnDefs: [
                    {
                        targets: 9,
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

            $('#tambahdata').click(function () {
                $('#saveBtn').val("tambah");
                $('#formuploaddetilpenyelesaiantagihan').trigger("reset");
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
            });

            /*
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formuploaddetilpenyelesaiantagihan');
                let fd = new FormData(form);
                $.ajax({
                    data: fd,
                    url: "{{route('importdetilpenyelesaian')}}",
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
                        $('#formgedung').trigger("reset");
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
            */
        });

    </script>
@endsection
