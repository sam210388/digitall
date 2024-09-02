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
                        <!--<a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtransaksi"> Tambah Data</a> -->
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
                                                    <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;" readonly>
                                                        <option value="">Pilih Satker</option>
                                                        <option value="001012">Setjen</option>
                                                        <option value="001030">Dewan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Pengenal</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control pengenal" name="pengenal" id="pengenal" style="width: 100%;" readonly></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Peruntukan</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control peruntukan" id="peruntukan" name="peruntukan" placeholder="Peruntukan Kasbon"  value="" maxlength="500" readonly>
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
                                                <label for="nilaipengajuan" class="col-sm-6 control-label">Nilai Pengajuan</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control nilaipengajuan" id="nilaipengajuan" name="nilaipengajuan" placeholder="Nilai Pengajuan" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sisapagu" class="col-sm-6 control-label">Sisa Pagu</label>
                                                <div class="col-sm-12">
                                                    <input type="number" class="form-control sisapagu" id="sisapagu" name="sisapagu" placeholder="Sisa Pagu" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="setujutolak" id="setuju" value="setuju" required>
                                                        <label class="form-check-label" for="setuju">Setuju</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="setujutolak" id="tolak" value="tolak">
                                                        <label class="form-check-label" for="setuju">Tolak</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="keteranganbendahara" class="col-sm-6 control-label">Keterangan Bendahara</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control keteranganbendahara" id="keteranganbendahara" name="keteranganbendahara" placeholder="Keterangan Bendahara" value="" maxlength="500" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data</button>
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
            $('input[type=radio][name=setujutolak]').change(function() {
                if (this.value === 'setuju') {
                    //document.getElementById("keteranganppk").innerText = "Pengajuan OK";
                    $('#keteranganppk').val("Pengajuan OK");
                }
                else if (this.value === 'tolak') {
                    $('#keteranganppk').val("");;

                }
            });



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
                ajax:"{{route('getdatakasbonbendahara')}}",
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

            $('body').on('click', '.prosestransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('editkasbonbendahara','') }}" +'/' + id , function (data) {
                    $('#modelHeading').html("Proses Transaksi");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#pengenalawal').val(data.pengenal);
                    $('#pengenal').val(data.pengenal).trigger('change');
                    $('#peruntukan').val(data.peruntukan);
                    $('#nilaipengajuan').val(data.nilaipengajuan);
                    $('#pagu').val(data.pagu);
                    $('#realisasisaatini').val(data.realisasisaatpengajuan);
                    $('#sisapagu').val(data.sisapagu);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formkasbon');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('id').value;
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: "{{route('prosespengajuankasbonbendahara')}}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status == "berhasil"){
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Proses Data Kasbon Berhasil',
                                icon: 'success'
                            })
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: 'Proses Data Kasbon Gagal',
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
        });

    </script>
@endsection
