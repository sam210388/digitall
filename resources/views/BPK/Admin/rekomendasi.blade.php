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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahrekomendasi"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembali"> Kembali</a>
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Temuan: {{$temuan}}</h3>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Nilai Temuan: {{$nilai}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelrekomendasi" class="table table-bordered table-striped tabelrekomendasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Rekomendasi</th>
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
                                <th>Rekomendasi</th>
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
                                            <input type="hidden" name="idtemuan" id="idtemuan" value="{{$idtemuan ? $idtemuan:""}}">
                                            <input type="hidden" name="idrekomendasi" id="idrekomendasi">
                                            <input type="hidden" name="statusawal" id="statusawal">
                                            <input type="hidden" name="buktiawal" id="buktiawal">
                                            <input type="hidden" name="created_by_awal" id="created_by_awal">
                                            <div class="form-group">
                                                <label for="rekomendasi" class="col-sm-6 control-label">Rekomendasi</label>
                                                <div class="col-sm-12">
                                                    <textarea type="text" class="form-control" id="rekomendasi" name="rekomendasi" placeholder="Rekomendasi"></textarea>
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
            $('#tabelrekomendasi tfoot th').each( function (i) {
                var title = $('#tabelrekomendasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var idtemuan = document.getElementById('idtemuan').value;
            var table = $('.tabelrekomendasi').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                "ajax": {
                    "url": "{{route('getdatarekomendasi')}}",
                    "type": "POST",
                    "data": function (d){
                        d._token = "{{ csrf_token() }}";
                        d.idtemuan = idtemuan;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'rekomendasi', name: 'rekomendasi'},
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
            } );

            /*------------------------------------------
            --------------------------------------------
            Click to Button
            --------------------------------------------
            --------------------------------------------*/
            $('#tambahrekomendasi').click(function () {
                $('#saveBtn').val("tambah");
                $('#formrekomendasi').trigger("reset");
                $('#modelHeading').html("Tambah Rekomendasi");
                document.getElementById('aktuallinkbukti').href = "#"
                $('#linkbukti').hide();
                $('#ajaxModel').modal('show');
            });

            $('#kembali').click(function () {
                window.location="{{URL::to('temuan')}}"
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editrekomendasi', function () {
                var idrekomendasi = $(this).data('id');
                $.get("{{ route('rekomendasi.index') }}" +'/' + idrekomendasi +'/edit', function (data) {
                    $('#modelHeading').html("Edit Rekomendasi");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#idrekomendasi').val(data.id);
                    $('#idtemuan').val(data.idtemuan);
                    $('#buktiawal').val(data.bukti);
                    $('#nilai').val(data.nilai);
                    $('#rekomendasi').val(data.rekomendasi);
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
                let form = document.getElementById('formrekomendasi');
                let fd = new FormData(form);
                let bukti = $('#bukti')[0].files;
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('idrekomendasi').value;
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
                    url: saveBtn === "tambah" ? "{{route('rekomendasi.store')}}":"{{route('rekomendasi.update','')}}"+'/'+id,
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
                        $('#formrekomendasi').trigger("reset");
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

                var idrekomendasi = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('rekomendasi.destroy','') }}"+'/'+idrekomendasi,
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
                        error: function (xhr) {
                            var errorsArr = [];
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorsArr.push(value);
                            });
                            Swal.fire({
                                title: 'Error!',
                                text: errorsArr,
                                icon: 'error'
                            })
                            $('#saveBtn').html('Simpan Data');
                        },
                    });
                };
            });
        });

        $('body').on('click', '.indikatorrekomendasi', function () {
            var idrekomendasi = $(this).data("id");
            window.location="{{URL::to('tampilindikatorrekomendasi')}}"+"/"+idrekomendasi;
        });

        $('#nilai').on('input', function() {
            var nilaitemuan = $(this).val();

            // Hapus koma ribuan sebelum melakukan perhitungan
            nilaitemuan = removeThousandSeparator(nilaitemuan);


            // Tambahkan koma ribuan setelah perhitungan
            $(this).val(addThousandSeparator(nilaitemuan));

        });

        // Fungsi untuk menambahkan separator ribuan dengan koma
        function addThousandSeparator(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Fungsi untuk menghapus separator ribuan
        function removeThousandSeparator(number) {
            return number.replace(/,/g, '');
        }


    </script>
@endsection
