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
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <label for="bulan" class="col-sm-6 control-label">Bulan</label>
                            <div class="col-sm-12">
                                <select class="form-control idbulan" name="idbulan" id="idbulan" style="width: 100%;">
                                    <option value="">Pilih Bulan</option>
                                    @foreach($databulan as $data)
                                        <option value="{{ $data->id }}">{{ $data->bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelrealisasi" class="table table-bordered table-striped tabelrealisasi">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>RO</th>
                                <th>Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>RO</th>
                                <th>Indikator RO</th>
                                <th>Target</th>
                                <th>Realisasi Bulan Ini</th>
                                <th>Realisasi sd Bulan Ini</th>
                                <th>Prosentase Bulan Ini</th>
                                <th>Prosentase sd Bulan Ini</th>
                                <th>Status Pelaksanaan</th>
                                <th>Permasalahan</th>
                                <th>Uraian Output</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="formrealisasi" name="formrealisasi" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="idindikatorro" id="idindikatorro">
                        <input type="hidden" name="idro" id="idro">
                        <input type="hidden" name="idkro" id="idkro">
                        <input type="hidden" name="nilaibulan" id="nilaibulan">
                        <input type="hidden" name="jumlahsdbulanlalu" id="jumlahsdbulanlalu">
                        <input type="hidden" name="prosentasesdbulanlalu" id="prosentasesdbulanlalu">
                        <input type="hidden" name="linkbuktiawal" id="linkbuktiawal">
                        <div class="form-group">
                            <label for="target" class="col-sm-6 control-label">Target</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="target" name="target" placeholder="Target" value="" maxlength="100" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="target" class="col-sm-6 control-label">Target Bulan</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control targetbulan" id="targetbulan" name="targetbulan" placeholder="Target Bulan Berjalan" value="" maxlength="100" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jumlah" class="col-sm-6 control-label">Jumlah Output</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="number" step="1" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" value="" maxlength="100" required="" onfocusout="realisasisdperiodeini()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jumlahsdperiodeini" class="col-sm-6 control-label">Jumlah Output Sd Periode Ini</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="number" step="1" class="form-control" id="jumlahsdperiodeini" name="jumlahsdperiodeini" placeholder="Jumlah sd Periode Ini" value="" maxlength="100" required="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prosentase" class="col-sm-6 control-label">Prosentase Periode Ini</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step="any" id="prosentase" name="prosentase" placeholder="Prosentase Periode Ini" value="" maxlength="100" required="" onfocusout="nilaiprosentasesdperiodeini()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prosentasesdperiodeini" class="col-sm-6 control-label">Prosentase sd Periode Ini</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" step="any" id="prosentasesdperiodeini" name="prosentasesdperiodeini" placeholder="Prosentase Sd Periode Ini" value="" maxlength="100" required="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="statuspelaksanaan" class="col-sm-6 control-label">Status Pelaksanaan</label>
                            <div class="col-sm-12">
                                <select class="form-control statuspelaksanaan" name="statuspelaksanaan" id="statuspelaksanaan" style="width: 100%;">
                                    <option value="">Pilih Status Pelaksanaan</option>
                                    @foreach($datastatuspelaksanaan as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraianstatus }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kategoripermasalahan" class="col-sm-6 control-label">Kategori Permasalahan</label>
                            <div class="col-sm-12">
                                <select class="form-control kategoripermasalahan" name="kategoripermasalahan" id="kategoripermasalahan" style="width: 100%;">
                                    <option value="">Pilih Permasalahan</option>
                                    @foreach($datakategoripermasalahan as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraiankategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uraianoutputdihasilkan" class="col-sm-6 control-label">Output Dihasilkan</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="uraianoutputdihasilkan" name="uraianoutputdihasilkan" placeholder="Output Dihasilkan" value="" maxlength="100" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-6 control-label">Keterangan</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" value="" maxlength="100" required="">
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="file" class="col-sm-6 control-label">File</label>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file" name="file">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
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
                            <button type="submit" class="btn btn-primary" id="saveBtn" name="saveBtn" value="create">Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        function dapatkanidbulan(){
            var idbulan = document.getElementById('idbulan').value;
            if(idbulan == ""){
                date = new Date();
                nilaibulan = date.getMonth();
                nilaibulan = nilaibulan+1;
                return parseInt(nilaibulan);
            }else{
                nilaibulan = idbulan;
                return parseInt(nilaibulan);
            }
        }

        $(function () {
            bsCustomFileInput.init();
            $('.idbulan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            $('.statuspelaksanaan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            $('.kategoripermasalahan').select2({
                width: '100%',
                theme: 'bootstrap4',

            })

            $( "#tanggallapor" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            // Setup - add a text input to each header cell
            $('#tabelrealisasi thead th').each( function (i) {
                var title = $('#tabelrealisasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });
            idbulan = dapatkanidbulan();
            var table = $('.tabelrealisasi').DataTable({
                destroy: true,
                fixedColumns: {
                    leftColumns: 3 // Freeze 3 kolom pertama
                },
                fixedHeader: true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('getdatarealisasiindikatorro','')}}"+"/"+idbulan,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'ro', name: 'ro'},
                    {data: 'indikatorro', name: 'indikatorro'},
                    {data: 'target', name: 'target'},
                    {data: 'jumlah', name: 'jumlah'},
                    {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                    {data: 'prosentase', name: 'prosentase'},
                    {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                    {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                    {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                    {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                    {data: 'keterangan', name: 'keterangan'},
                    {data: 'statusrealisasi', name: 'statusrealisasi'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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


            $('#idbulan').on('change',function (){
                let idbulan = dapatkanidbulan();
                var table = $('.tabelrealisasi').DataTable({
                    destroy: true,
                    fixedColumns: {
                        leftColumns: 3 // Freeze 3 kolom pertama
                    },
                    fixedHeader: true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarealisasiindikatorro','')}}"+"/"+idbulan,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'ro', name: 'ro'},
                        {data: 'indikatorro', name: 'indikatorro'},
                        {data: 'target', name: 'target'},
                        {data: 'jumlah', name: 'jumlah'},
                        {data: 'jumlahsdperiodeini', name: 'jumlahsdperiodeini'},
                        {data: 'prosentase', name: 'prosentase'},
                        {data: 'prosentasesdperiodeini', name: 'prosentasesdperiodeini'},
                        {data: 'statuspelaksanaan', name: 'statuspelaksanaan'},
                        {data: 'kategoripermasalahan', name: 'kategoripermasalahan'},
                        {data: 'uraianoutputdihasilkan', name: 'uraianoutputdihasilkan'},
                        {data: 'keterangan', name: 'keterangan'},
                        {data: 'statusrealisasi', name: 'statusrealisasi'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
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
            })

            $('body').on('click', '.laporkinerja', function () {
                //cek dan pastikan bulan sebelumnya terisi dan waktu pengisian sudah dibuka
                var idindikatorro = $(this).data('id');
                let nilaibulan = dapatkanidbulan();
                let uraiantargetbulan = 'target'+nilaibulan;
                $.ajax({
                    type: "GET",
                    url: "{{ route('cekjadwallaporindikatorro',['',''])}}"+"/"+idindikatorro+"/"+nilaibulan,
                    success: function (data) {
                        if (data.status == "Buka"){
                            $.ajax({
                                url: "{{url('getdataindikatorro')}}",
                                type: "POST",
                                data: {
                                    idindikatorro: idindikatorro,
                                    nilaibulan: nilaibulan,
                                    _token: '{{csrf_token()}}'
                                },
                                dataType: 'json',
                                success: function (data) {
                                    $('#modelHeading').html("Lapor Kinerja");
                                    $('#saveBtn').val("tambah");
                                    $('#ajaxModel').modal('show');
                                    $('#idindikatorro').val(data[0]['id']);
                                    $('#idro').val(data[0]['idro']);
                                    $('#idkro').val(data[0]['idkro']);
                                    $('#target').val(data[0]['target']);
                                    $('#targetbulan').val(data[0][uraiantargetbulan]);
                                    $('#jumlahsdbulanlalu').val(data['jumlahsdperiodelalu']);
                                    $('#prosentasesdbulanlalu').val(data['prosentasesdperiodelalu']);
                                    $('#nilaibulan').val(data['nilaibulan']);
                                }
                            });
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: "Status: "+data.status+" Karena: "+data.kondisi,
                                icon: 'error'
                            })
                            $('#tabelrealisasi').DataTable().ajax.reload();
                        }
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
            });

            $('body').on('click', '.editrealisasi', function () {
                let id = $(this).data('id');
                let dataid = id.split("/");
                let idrealisasi = dataid[0];
                let idindikatorro = dataid[1];
                let nilaibulan = parseInt(dapatkanidbulan());
                $.ajax({
                    type: "GET",
                    url: "{{ route('cekjadwallapor',['',''])}}"+"/"+idindikatorro+"/"+nilaibulan,
                    success: function (data) {
                        if (data.status == "Buka") {
                            $.ajax({
                                url: "{{url('editrealisasiindikatorro')}}",
                                type: "POST",
                                data: {
                                    idrealisasi: idrealisasi,
                                    nilaibulan: nilaibulan,
                                    idindikatorro: idindikatorro,
                                    _token: '{{csrf_token()}}'
                                },
                                dataType: 'json',
                                success: function (data) {
                                    $('#modelHeading').html("Lapor Kinerja");
                                    $('#saveBtn').val("edit");
                                    $('#ajaxModel').modal('show');
                                    $('#id').val(data[0]['idrealisasi']);
                                    $('#idindikatorro').val(data[0]['indikatorro']);
                                    $('#idro').val(data[0]['idro']);
                                    $('#idkro').val(data[0]['idkro']);
                                    $('#nilaibulan').val(data[0]['periode']);
                                    $('#target').val(data[0]['target']);
                                    $('#targetbulan').val(data[0]['targetbulan']);
                                    $('#tanggallapor').val(data[0]['tanggallapor']);
                                    $('#jumlah').val(data[0]['jumlah']);
                                    $('#jumlahsdperiodeini').val(data[0]['jumlahsdperiodeini']);
                                    $('#prosentase').val(data[0]['prosentase']);
                                    $('#prosentasesdperiodeini').val(data[0]['prosentasesdperiodeini']);
                                    $('#statuspelaksanaan').val(data[0]['statuspelaksanaan']).trigger('change');
                                    $('#kategoripermasalahan').val(data[0]['kategoripermasalahan']).trigger('change');
                                    $('#uraianoutputdihasilkan').val(data[0]['uraianoutputdihasilkan']);
                                    $('#keterangan').val(data[0]['keterangan']);
                                    $('#jumlahsdbulanlalu').val(data['jumlahsdperiodelalu']);
                                    $('#prosentasesdbulanlalu').val(data['prosentasesdperiodelalu']);
                                    document.getElementById('aktuallinkbukti').href = "{{env('APP_URL')."/".asset('storage')}}" + "/" + data[0]['file']
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: "Status: "+data.status+" Karena: "+data.kondisi,
                                icon: 'error'
                            })
                            $('#tabelrealisasi').DataTable().ajax.reload();
                        }
                    },

                });
            });



            $('#saveBtn').click(function (e) {
                e.preventDefault();
                let status = bandingkanrealisasi();
                if(status){
                    $(this).html('Sending..');
                    let form = document.getElementById('formrealisasi');
                    let fd = new FormData(form);
                    let file = $('#file')[0].files;
                    let saveBtn = document.getElementById('saveBtn').value;
                    let idrealisasi = document.getElementById('id').value;

                    fd.append('file',file[0])
                    fd.append('saveBtn',saveBtn)

                    for (var pair of fd.entries()) {
                        console.log(pair[0]+ ', ' + pair[1]);
                    }
                    $.ajax({
                        data: fd,
                        url: saveBtn === "tambah" ? "{{route('simpanrealisasiindikatorro')}}":"{{route('updaterealisasiindikatorro','')}}"+'/'+idrealisasi,
                        type: "POST",
                        dataType: 'json',
                        enctype: 'multipart/form-data',
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
                            $('#formrealisasi').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            $('#saveBtn').html('Simpan Data');
                            $('#tabelrealisasi').DataTable().ajax.reload();

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

            $('body').on('click', '.deleterealisasi', function () {
                var idrealisasi = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('deleterealisasiindikatorro','') }}"+"/"+idrealisasi,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Dihapus ',
                                    icon: 'success'
                                })
                            }else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Hapus Data Gagal',
                                    icon: 'error'
                                })
                            }
                            $('#tabelrealisasi').DataTable().ajax.reload();
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
        });

        function realisasisdperiodeini() {
            let jumlahsdbulanlalu = parseInt($('#jumlahsdbulanlalu').val()) || 0;
            let jumlah = parseInt($('#jumlah').val()) || 0;
            let target = parseInt($('#target').val()) || 0;
            let targetbulan = parseInt($('#targetbulan').val()) || 0;
            let jumlahsdperiodeini = jumlahsdbulanlalu + jumlah;
            let tombolSimpan = document.getElementById('saveBtn');

            if (jumlahsdperiodeini > target) {
                alert("Jumlah Realisasi Melebihi Target");
                tombolSimpan.disabled = true;
            }else if (jumlah >= targetbulan){
                tombolSimpan.disabled = false;
            }else if (jumlah < targetbulan ){
                alert("Jumlah Realisasi Kurang dari Target Bulanan");
                tombolSimpan.disabled = true;
            }
            $('#jumlahsdperiodeini').val(jumlahsdperiodeini);
        }

        function nilaiprosentasesdperiodeini() {
            let prosentasesdbulanlalu = parseFloat($('#prosentasesdbulanlalu').val()) || 0;
            let prosentase = parseFloat($('#prosentase').val()) || 0;

            let prosentasesdperiodeini = (prosentasesdbulanlalu + prosentase).toFixed(2);
            $('#prosentasesdperiodeini').val(prosentasesdperiodeini);
        }

        function bandingkanrealisasi() {
            let target = parseInt($('#target').val()) || 0;
            let jumlahsdperiodeini = parseInt($('#jumlahsdperiodeini').val()) || 0;
            let prosentasesdperiodeini = parseFloat($('#prosentasesdperiodeini').val()) || 0;

            if (jumlahsdperiodeini > target || prosentasesdperiodeini > 100.00) {
                alert("Total Realisasi Tidak Boleh Melebihi Target dan Prosentase Tidak Boleh Melebihi 100%");
                return false;
            } else {
                return true;
            }
        }
    </script>

@endsection
