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
                        <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtransaksi"> Tambah Data</a>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelkasbon" class="table table-bordered table-striped tabelkasbon">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Peruntukan</th>
                                <th>Nilai Pengajuan</th>
                                <th>Setuju/Tolak PPK</th>
                                <th>Keterangan PPK</th>
                                <th>Setuju/Tolak PPSPM</th>
                                <th>Keterangan PPSPM</th>
                                <th>Setuju/Tolak Bendahara</th>
                                <th>Keterangan Bendahara</th>
                                <th>Dicairkan Pada</th>
                                <th>Nilai Pencairan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Peruntukan</th>
                                <th>Nilai Pengajuan</th>
                                <th>Setuju/Tolak PPK</th>
                                <th>Keterangan PPK</th>
                                <th>Setuju/Tolak PPSPM</th>
                                <th>Keterangan PPSPM</th>
                                <th>Setuju/Tolak Bendahara</th>
                                <th>Keterangan Bendahara</th>
                                <th>Dicairkan Pada</th>
                                <th>Nilai Pencairan</th>
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
                                        <form id="formkasbon" name="formkasbon" class="form-horizontal">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="pengenalawal" id="pengenalawal">
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Satker</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;">
                                                        <option value="">Pilih Satker</option>
                                                        <option value="001012">Setjen</option>
                                                        <option value="001030">Dewan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Pengenal</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control pengenal" name="pengenal" id="pengenal" style="width: 100%;"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Peruntukan</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="peruntukan" name="peruntukan" placeholder="Peruntukan Kasbon" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pagu" class="col-sm-6 control-label">Pagu</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control pagu" id="pagu" name="pagu" placeholder="Pagu" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sisapagu" class="col-sm-6 control-label">Realisasi Saat Ini</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control realisasisaatini" id="realisasisaatini" name="realisasisaatini" placeholder="Realisasi Saat Ini" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilaipengajuan" class="col-sm-6 control-label">Nilai</label>
                                                <div class="col-sm-12">
                                                <input type="number" class="form-control nilaipengajuan" id="nilaipengajuan" name="nilaipengajuan" placeholder="Nilai Kasbon" value="" maxlength="21">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sisapagu" class="col-sm-6 control-label">Sisa Pagu</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control sisapagu" id="sisapagu" name="sisapagu" placeholder="Sisa Pagu" value="" maxlength="21" readonly>
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
            $('.pengenal').select2({
                width: '100%',
                theme: 'bootstrap4',
            })
            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',
            })



            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelkasbon tfoot th').each( function (i) {
                var title = $('#tabelkasbon thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelkasbon').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatakasbonbagian')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data:'bagian',name:'bagianpengajuanrelation.uraianbagian'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'tanggalpengajuan', name: 'tanggalpengajuan'},
                    {data: 'peruntukan', name: 'peruntukan'},
                    {data: 'nilaipengajuan', name: 'nilaipengajuan'},
                    {data: 'tanggalppksetuju', name: 'tanggalppksetuju'},
                    {data: 'keteranganppk', name: 'keteranganppk'},
                    {data: 'tanggalppspmsetuju', name: 'tanggalppspmsetuju'},
                    {data: 'keteranganppspm', name: 'keteranganppspm'},
                    {data: 'tanggalbendaharasetuju', name: 'tanggalbendaharasetuju'},
                    {data: 'keteranganbendahara', name: 'keteranganbendahara'},
                    {data: 'tanggalpencairankasir', name: 'tanggalpencairankasir'},
                    {data: 'nilaipencairankasir', name: 'nilaipencairankasir'},
                    {data: 'statuskasbon', name: 'statuskasbon'},
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
            $('#tambahtransaksi').click(function () {
                $('#saveBtn').val("tambah");
                $('#id').val('');
                $('#kdsatker').val('');
                $('#pengenalawal').val('');
                $('#pengenal').val('').trigger('change');
                $('#peruntukan').val('');
                $('#nilaipengajuan').val('');
                $('#modelHeading').html("Tambah Transaksi");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('kasbonbagian.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Penanggungjawab");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#pengenalawal').val(data.pengenal);
                    $('#pengenal').val(data.pengenal).trigger('change');
                    $('#peruntukan').val(data.peruntukan);
                    $('#nilaipengajuan').val(data.nilaipengajuan);
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
                let form = document.getElementById('formkasbon');
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
                    url: saveBtn === "tambah" ? "{{route('kasbonbagian.store')}}":"{{route('kasbonbagian.update','')}}"+'/'+id,
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
                        $('#formkasbon').trigger("reset");
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
            $('body').on('click', '.deletetransaksi', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('kasbonbagian.destroy','') }}"+'/'+id,
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

            $('body').on('click', '.ajukankeppk', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Mengajukan Kasbon ini Ke PPK?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('ajukankasbonkeppk','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Diajukan ke PPK',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Data Kasbon Gagal Diajukan ke PPK',
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

            $('body').on('click', '.prosespertanggungjawaban', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Memproses Pertanggungjawaban Kasbon Ini?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ route('prosespertanggungjawaban','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Diajukan ke Kasir, Segera Lakukan Pengiriman Dokumen Riil',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Data Kasbon Gagal Diajukan Pertanggungjawabannya ke Kasir',
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

            $('#kdsatker').on('change', function () {
                var kdsatker = this.value;
                $.ajax({
                    url: "{{url('ambildatapengenalbagian')}}",
                    type: "POST",
                    data: {
                        kdsatker: kdsatker,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var pengenalawal = document.getElementById('pengenalawal').value;
                        $('#pengenal').html('<option value="">Pilih Pengenal</option>');
                        $.each(result.pengenal, function (key, value) {
                            if (pengenalawal == value.pengenal) {
                                $('select[name="pengenal"]').append('<option value="'+value.pengenal+'" selected>'+value.pengenal+'</option>').trigger('change')
                            }else{
                                $("#pengenal").append('<option value="' + value.pengenal + '">' + value.pengenal + '</option>');
                            }

                        });
                    }

                });
            });

            $('#pengenal').on('change', function () {
                var pengenal = this.value;
                $.ajax({
                    url: "{{url('ambilrealisasipengenal')}}",
                    type: "POST",
                    data: {
                        pengenal: pengenal,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        let pagu = result['pagu'][0].paguanggaran;
                        let realisasisaatini = result['pagu'][0].rsd12;
                        let sisapagu = pagu-realisasisaatini;
                        $('#pagu').val(pagu);
                        $('#realisasisaatini').val(realisasisaatini);
                        $('#sisapagu').val(sisapagu);
                    }
                });
                rubahformat()
            });

            function rubahformat(){
                $(".pagu").numeric({ decimal : ".",  negative : false, scale: 3 });
                $(".sisapagu").numeric({ decimal : ".",  negative : false, scale: 3 });
                $(".realisasisaatini").numeric({ decimal : ".",  negative : false, scale: 3 });
            }
        });

    </script>
@endsection
