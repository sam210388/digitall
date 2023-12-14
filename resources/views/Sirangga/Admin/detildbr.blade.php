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
                <div class="row">
                </div>
                <div class="row">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="exportdetildbr">Export Data</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Barang</span>
                                    <span class="info-box-number">
                                 {{$barangterdata}}
                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Barang Hilang</span>
                                    <span class="info-box-number">
                                 {{$baranghilang}}
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Barang Pengembalian</span>
                                    <span class="info-box-number">
                                 {{$barangpengembalian}}
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetildbr" class="table table-bordered table-striped tabeldetildbr">
                            <thead>
                            <tr>
                                <th>IDDetil</th>
                                <th>IDDBR</th>
                                <th>ID BRG</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Tahun Perolehan</th>
                                <th>Merek</th>
                                <th>Status Barang</th>
                                <th>Terakhir Periksa</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>IDDetil</th>
                                <th>IDDBR</th>
                                <th>ID BRG</th>
                                <th>KD BRG</th>
                                <th>Uraian BRG</th>
                                <th>NUP</th>
                                <th>Tahun Perolehan</th>
                                <th>Keterangan Barang</th>
                                <th>Status Barang</th>
                                <th>Terakhir Periksa</th>
                                <th>Aksi</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modelHeading"></h4>
                                </div>
                                <div class="modal-body">
                                    <form id="formexport" name="formexport" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="Area" class="col-sm-6 control-label">Status Barang</label>
                                            <div class="col-sm-12">
                                                <select class="form-control statusbarang" name="statusbarang" id="statusbarang" style="width: 100%;">
                                                    <option value="semua">Pilih Semua</option>
                                                    <option value="hilang">Barang Hilang</option>
                                                    <option value="pengembalian">Pengembalian</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Export
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
            $('#exportdetildbr').click(function () {
                $('#saveBtn').val("export");
                $('#modelHeading').html("Export Detil DBR");
                $('#ajaxModel').modal('show');
            });
            $('#tabeldetildbr tfoot th').each( function (i) {
                var title = $('#tabeldetildbr thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabeldetildbr').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatadetildbradmin','')}}",
                columns: [
                    {data: 'iddetil', name: 'iddetil'},
                    {data: 'iddbr', name: 'iddbr'},
                    {data: 'idbarang', name: 'idbarang'},
                    {data: 'kd_brg', name: 'kd_brg'},
                    {data: 'uraianbarang', name: 'uraianbarang'},
                    {data: 'no_aset', name: 'no_aset'},
                    {data: 'tahunperolehan', name: 'tahunperolehan'},
                    {data: 'merek', name: 'merek'},
                    {data: 'statusbarang', name: 'statusbarang'},
                    {data: 'terakhirperiksa', name: 'terakhirperiksa'},
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
            $( table.table().container() ).on( 'keypress', 'tfoot input', function (e) {
                if (e.key == "Enter"){
                    table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                }
            } );

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Exporting..');
                let statusbarang = document.getElementById('statusbarang').value;
                window.location="{{URL::to('exportdetildbr')}}"+"/"+statusbarang;

                $('#formexport').trigger("reset");
                $('#ajaxModel').modal('hide');
                $('#saveBtn').html('Export Data');

            });

            $('body').on('click', '.konfirmhilangkembali', function () {
                var iddetil = $(this).data('id');
                if(confirm("Apakah Anda Yakin Bahwa Barang ini Benar Benar Tidak Ada Atau Sudah Anda Ambil Dari Unit Kerja?")){
                    $.ajax({
                        url: "{{url('konfirmhilangkembali')}}",
                        type: "POST",
                        data: {
                            iddetil: iddetil,
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Konfirmasi Barang Berhasil',
                                    icon: 'success'
                                })
                                table.draw();
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Konfirmasi Barang Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#ajaxModel').modal('hide');
                            table.draw(null, false);
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
