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
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtransaksi"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembali"> Kembali</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelkasbon" class="table table-bordered table-striped tabelkasbon">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rencana Kegiatan</th>
                                <th>Pengenal</th>
                                <th>Rupiah</th>
                                <th>Pagu</th>
                                <th>Total Rencana</th>
                                <th>Realisasi</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Rencana Kegiatan</th>
                                <th>Pengenal</th>
                                <th>Rupiah</th>
                                <th>Pagu</th>
                                <th>Total Rencana</th>
                                <th>Realisasi</th>
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
                                            <input type="hidden" name="idrencanakegiatan" id="idrencanakegiatan" value="{{$idrencanakegiatan}}">
                                            <input type="hidden" name="pengenalawal" id="pengenalawal">
                                            <div class="form-group">
                                                <label for="Area" class="col-sm-6 control-label">Pengenal</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control pengenal" name="pengenal" id="pengenal" style="width: 100%;">
                                                        <option>Pilih Pengenal</option>
                                                        @foreach($datapengenal as $data)
                                                            <option value="{{ $data->pengenal }}">{{ $data->pengenal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pagu" class="col-sm-6 control-label">Pagu</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control pagupengenal" id="pagupengenal" name="pagupengenal" placeholder="Pagu" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sisapagu" class="col-sm-6 control-label">Realisasi Saat Ini</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control realisasipengenal" id="realisasipengenal" name="realisasipengenal" placeholder="Realisasi Saat Ini" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilaipengajuan" class="col-sm-6 control-label">Total Direncanakan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control totalrencanapengenal" id="totalrencanapengenal" name="totalrencanapengenal" placeholder="Total Direncanakan" value="" maxlength="21" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilaipengajuan" class="col-sm-6 control-label">Kebutuhan Anggaran</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control rupiah" id="rupiah" name="rupiah" placeholder="Rupiah" value="" maxlength="21">
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
            let idrencanakegiatan = document.getElementById('idrencanakegiatan').value;
            var table = $('.tabelkasbon').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getrencanakegiatanbagiandetil','')}}"+"/"+idrencanakegiatan,
                columns: [
                    {data:'id',name:'id'},
                    {data:'rencanakegiatan',name:'rencanakegiatan.uraiankegiatan'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'rupiah', name: 'rupiah'},
                    {data: 'pagupengenal', name: 'pagupengenal'},
                    {data: 'totalrencanapengenal', name: 'totalrencanapengenal'},
                    {data: 'realisasipengenal', name: 'realisasipengenal'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    }
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 5,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
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
                $('#pengenalawal').val('');
                $('#modelHeading').html("Tambah Pengenal");
                $('#ajaxModel').modal('show');
            });

            $('#kembali').click(function () {
                window.location="{{URL::to('rencanakegiatanbagian')}}"
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('editrencanakegiatanbagiandetil','') }}" +'/' + id, function (data) {
                    $('#modelHeading').html("Edit Transaksi");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#idrencanakegiatan').val(data.idrencanakegiatan);
                    $('#pengenalawal').val(data.pengenal);
                    $('#pengenal').val(data.pengenal).trigger('change');
                    $('#rupiah').val(data.rupiah);
                    $('#pagupengenal').val(data.pagupengenal);
                    $('#totalrencanapengenal').val(data.totalrencanapengenal);
                    $('#realisasipengenal').val(data.realisasipengenal);
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
                var idrencanakegiatan = document.getElementById('idrencanakegiatan').value;
                fd.append('saveBtn',saveBtn)
                fd.append('idrencanakegiatan',idrencanakegiatan)
                if(saveBtn == "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('rencanakegiatanbagiandetil.store')}}":"{{route('rencanakegiatanbagiandetil.update','')}}"+'/'+id,
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
                        url: "{{ route('rencanakegiatanbagiandetil.destroy','') }}"+'/'+id,
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

            $('#pengenal').on('change', function () {
                var pengenal = this.value;
                $.ajax({
                    url: "{{url('ambildatapengenal')}}",
                    type: "POST",
                    data: {
                        pengenal: pengenal,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        let pagu = result['pagu'][0].paguanggaran;
                        let realisasisaatini = result['pagu'][0].rsd12;
                        let totalrencana = result['pagu'][0].totalrencana;
                        let sisapagu = pagu-realisasisaatini;
                        $('#pagupengenal').val(pagu);
                        new AutoNumeric('.pagupengenal', {currencySymbol :'Rp'});
                        $('#realisasipengenal').val(realisasisaatini);
                        new AutoNumeric('.realisasipengenal', {currencySymbol :'Rp'});
                        $('#totalrencanapengenal').val(totalrencana);
                        new AutoNumeric('.totalrencanapengenal', {currencySymbol :'Rp'});
                        //$('#sisapagu').val(sisapagu);
                        new AutoNumeric('.rupiah', {currencySymbol :'Rp',unformatOnSubmit : true});
                    }
                });
            });
        });

    </script>
@endsection
