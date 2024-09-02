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
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
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
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrekomendasi" class="table table-bordered table-striped tabelrekomendasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Temuan</th>
                                <th>Rekomendasi</th>
                                <th>Indikator Rekomendasi</th>
                                <th>Nilai</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Didata Oleh</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Temuan</th>
                                <th>Rekomendasi</th>
                                <th>Indikator Rekomendasi</th>
                                <th>Nilai</th>
                                <th>Bukti</th>
                                <th>Status</th>
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
                                        <form id="formrekomendasi" name="formrekomendasi" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="idrekomendasi" id="idrekomendasi">
                                            <input type="hidden" name="idindikatorrekomendasi" id="idindikatorrekomendasi">
                                            <div class="form-group">
                                                <label for="tahunanggaran" class="col-sm-6 control-label">Tahun Anggaran</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="tahunanggaran" name="tahunanggaran" placeholder="Tahun Anggaran" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="temuan" class="col-sm-6 control-label">Temuan</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="temuan" name="temuan" placeholder="Temuan" value="" required style="width: 100%;" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kondisi" class="col-sm-6 control-label">Kondisi</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="kondisi" name="kondisi" placeholder="Kondisi" value="" required style="width: 100%;" readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kriteria" class="col-sm-6 control-label">Kriteria</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="kriteria" name="kriteria" placeholder="Kriteria" value="" required readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sebab" class="col-sm-6 control-label">Sebab</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="sebab" name="sebab" placeholder="Sebab" value="" required readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="akibat" class="col-sm-6 control-label">Akibat</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="akibat" name="akibat" placeholder="Akibat" value="" required readonly></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilai" class="col-sm-6 control-label">Nilai</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="nilai" name="nilai" placeholder="Nilai" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group" id="linkbukti" aria-hidden="true">
                                                <div class="col-sm-12">
                                                    <a href="#" id="aktuallinkbukti">Lihat Bukti</a>
                                                </div>
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
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelrekomendasi tfoot th').each( function (i) {
                var title = $('#tabelrekomendasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelrekomendasi').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('indikatorrekomendasibpkbagian.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'temuan', name: 'temuan'},
                    {data: 'rekomendasi', name: 'rekomendasi'},
                    {data: 'indikatorrekomendasi', name: 'indikatorrekomendasi'},
                    {data: 'nilai', name: 'nilai'},
                    {data: 'bukti', name: 'bukti'},
                    {data: 'status', name: 'status'},
                    {data: 'created_by', name: 'created_by'},

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
            });

            $('body').on('click', '.tindaklanjut', function () {
                var idrekomendasi = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Menambahkan Data Tindaklanjut Pada Temuan Ini?")){
                    window.location="{{URL::to('tindaklanjutbagian')}}"+"/"+idrekomendasi;
                }
            });

            $('body').on('click', '.detiltemuan', function () {
                var idindikatorrekomendasi = $(this).data('id');
                $.get("{{ route('getdetiltemuan','') }}" +'/' + idindikatorrekomendasi, function (data) {
                    $('#modelHeading').html("Detil Temuan");
                    $('#ajaxModel').modal('show');
                    $('#idtemuan').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran);
                    $('#temuan').val(data.temuan);
                    $('#kondisi').val(data.kondisi);
                    $('#kriteria').val(data.kriteria);
                    $('#sebab').val(data.sebab);
                    $('#akibat').val(data.akibat);
                    $('#nilai').val(data.nilai);
                    document.getElementById('aktuallinkbukti').href = "{{env('APP_URL')."/".asset('storage')}}"+"/"+data.bukti
                    $('#linkbukti').show();
                })
            });
        });

    </script>
@endsection
