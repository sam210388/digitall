@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <div class="col-sm-6">
                            @if(session('status'))
                                <div class="alert alert-success">
                                    {{session('status')}}
                                </div>
                            @endif
                        </div>
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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdata"> Tambah Data</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabelbagian" class="table table-bordered table-striped tabelbagian">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bagian Pelaksana</th>
                                <th>Biro Pelaksana</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Ke BMN</th>
                                <th>Tanggal Ke Perencanaan</th>
                                <th>Tanggal Final</th>
                                <th>Kode Barang</th>
                                <th>Tersedia DBR</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total Anggaran</th>
                                <th>Uraian Barang</th>
                                <th>Tujuan Penggunaan</th>
                                <th>Status Usulan</th>
                                <th>Dokumen Pendukung</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Bagian Pelaksana</th>
                                <th>Biro Pelaksana</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Ke BMN</th>
                                <th>Tanggal Ke Perencanaan</th>
                                <th>Tanggal Final</th>
                                <th>Kode Barang</th>
                                <th>Tersedia DBR</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total Anggaran</th>
                                <th>Uraian Barang</th>
                                <th>Tujuan Penggunaan</th>
                                <th>Status Usulan</th>
                                <th>Dokumen Pendukung</th>
                                <th>Aksi</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="modal fade" id="ajaxModel" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formbagian" name="formbagian" class="form-horizontal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
                                            <input type="hidden" name="outputawal" id="outputawal">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="dokumenpendukungawal" id="dokumenpendukungawal">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Tujuan</label>
                                                    <select class="form-control tujuanrencana" name="tujuanrencana" id="tujuanrencana" style="width: 100%;">
                                                        <option value="Perluasan">Perluasan</option>
                                                        <option value="Tambah Unit">Tambah Unit</option>
                                                        <option value="Khusus Lainnya">Khusus Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6" id="atrNonAtrDiv">
                                                    <label for="deputi" class="col-sm-6 control-label">ATR/Non ATR</label>
                                                    <select class="form-control atrnonatr" name="atrnonatr" id="atrnonatr" style="width: 100%;">
                                                        <option value="ATR">ATR</option>
                                                        <option value="Non ATR">Non ATR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Skema</label>
                                                    <select class="form-control skema" name="skema" id="skema" style="width: 100%;">
                                                        <option value="Pembelian">Pembelian</option>
                                                        <option value="Sewa">Sewa</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="Program" class="col-sm-6 control-label">Program</label>
                                                    <select class="form-control program" name="program" id="program" style="width: 100%;">
                                                        <option value="">Program</option>
                                                        @foreach($dataprogram as $data)
                                                            <option value="{{ $data->kode }}">{{ $data->kode." | ".$data->uraianprogram }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Kegiatan</label>
                                                    <select class="form-control kegiatan" name="kegiatan" id="kegiatan" style="width: 100%;">
                                                        <option value="">Pilih Kode Kegiatan</option>
                                                        @foreach($datakegiatan as $data)
                                                            <option value="{{ $data->kode }}">{{ $data->kode." | ".$data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="output" class="col-sm-6 control-label">Output</label>
                                                    <select class="form-control output" name="output" id="output" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Bagian Pelaksana</label>
                                                    <select class="form-control idbagianpelaksana" name="idbagianpelaksana" id="idbagianpelaksana" style="width: 100%;">
                                                        <option value="">Pilih Bagian Pelaksana</option>
                                                        @foreach($databagianrk as $data)
                                                            <option value="{{ $data->idbagian }}">{{ $data->uraianbagian }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Usulan Tahun Pengadaan</label>
                                                    <select class="form-control tahunpengadaan" name="tahunpengadaan" id="tahunpengadaan" style="width: 100%;">
                                                        <option value="">Pilih Tahun Pengadaan</option>
                                                        @foreach($datatahunanggaran as $data)
                                                            <option value="{{ $data }}">{{ $data }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Kode Barang</label>
                                                    <select class="form-control kodebarang" name="kodebarang" id="kodebarang" style="width: 100%;">
                                                        <option value="">Pilih Kode Barang</option>
                                                        @foreach($databmnrk as $data)
                                                            <option value="{{ $data->kdbrg }}">{{ $data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="keterangan" class="col-sm-6 control-label">Barang Tersedia Dalam DBR</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="barangtersediadbr" name="barangtersediadbr" placeholder="Barang Tersedia Dalam DBR" value=""  readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6" id="jeniskantoratr">
                                                    <label for="deputi" class="col-sm-6 control-label">Jenis Kantor</label>
                                                    <select class="form-control jeniskantor" name="jeniskantor" id="jeniskantor" style="width: 100%;">
                                                        <option value="Kementerian dan Lembaga">Kementerian dan Lembaga</option>
                                                        <option value="Kantor Eselon I">Kantor Eselon I</option>
                                                        <option value="Kantor Eselon II">Kantor Eselon II</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Lokasi</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control lokasi" id="lokasi" name="lokasi" placeholder="Lokasi" value="" required="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Luas</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control luas" id="luas" name="luas" placeholder="Luas" value="" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="keterangan" class="col-sm-6 control-label">Uraian/Spesifikasi Barang</label>
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="uraianbarang" name="uraianbarang" placeholder="Uraian/Spesifikasi" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Tujuan Penggunaan</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="tujuanpenggunaan" name="tujuanpenggunaan" placeholder="Tujuan Penggunaan" value="" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Kuantitas</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control" id="quantitas" name="quantitas" placeholder="Kuantitas Pengajuan" value="" required="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Harga Barang</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="hargabarang" name="hargabarang" placeholder="Harga Barang" value="" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Total Anggaran</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="totalanggaran" name="totalanggaran" placeholder="Total Harga Barang" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <label for="file" class="col-sm-6 control-label">Dokumen Pendukung</label>
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="file" name="file">
                                                                <label class="custom-file-label" for="exampleInputFile">Pilih File</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="linkbukti" aria-hidden="true">
                                                        <a href="#" id="aktuallinkbukti">Lihat Bukti</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan Data</button>
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
        function toggleSecondComboBox() {
            var tujuanValue = document.getElementById("tujuanrencana").value;
            var atrNonAtrDiv = document.getElementById("atrNonAtrDiv");

            if (tujuanValue === "Perluasan") {
                atrNonAtrDiv.style.display = "block";  // Tampilkan combobox kedua
            } else {
                atrNonAtrDiv.style.display = "none";  // Sembunyikan combobox kedua
            }
        }

        // Pasang event listener pada combobox pertama
        document.getElementById("tujuanrencana").addEventListener("change", toggleSecondComboBox);

        // Panggil fungsi saat halaman dimuat agar kondisi awal sesuai
        toggleSecondComboBox();

        $(function () {
            bsCustomFileInput.init();

            $('.idbagianpelaksana').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.program').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.kegiatan').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.output').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.kodebarang').select2({
                width: '100%',
                theme: 'bootstrap4',
                dropdownParent: $('#ajaxModel')

            })

            $('.tahunpengadaan').select2({
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
            $('#tabelbagian tfoot th').each( function (i) {
                var title = $('#tabelbagian thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabelbagian').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('pengajuanrkbmnbagian.index')}}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'idbagianpelaksana', name: 'idbagianpelaksana'},
                    {data: 'biropelaksana', name: 'biropelaksana'},
                    {data: 'tanggalpengajuan', name: 'tanggalpengajuan'},
                    {data: 'tanggalkebmn', name: 'tanggalkebmn'},
                    {data: 'tanggalkeperencanaan', name: 'tanggalkeperencanaan'},
                    {data: 'tanggalfinal', name: 'tanggalfinal'},
                    {data: 'kodebarang', name: 'kodebarang'},
                    {data: 'barangtersediadbr', name: 'barangtersediadbr'},
                    {data: 'quantitas', name: 'quantitas'},
                    {data: 'hargabarang', name: 'hargabarang'},
                    {data: 'totalanggaran', name: 'totalanggaran'},
                    {data: 'uraianbarang', name: 'uraianbarang'},
                    {data: 'tujuanpenggunaan', name: 'tujuanpenggunaan'},
                    {data: 'status', name: 'status'},
                    {data: 'dokumenpendukung', name: 'dokumenpendukung'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                columnDefs: [
                    {
                        targets: 10,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 11,
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


            $('#kegiatan').on('change', function () {
                var  kegiatan = this.value;
                $.ajax({
                    url: "{{url('ambildataoutput')}}",
                    type: "POST",
                    data: {
                        kodekegiatan: kegiatan,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        var output = document.getElementById('outputawal').value;
                        $('#output').html('<option value="">Pilih Output</option>');
                        $.each(result.output, function (key, value) {
                            if (output === value.kodeoutput) {
                                $('select[name="output"]').append('<option value="'+value.kodeoutput+'" selected>'+value.kodeoutput+" | "+value.deskripsi+'</option>').trigger('change')
                            }else{
                                $("#output").append('<option value="' + value.kodeoutput + '">' +value.kodeoutput+" | "+value.deskripsi+ '</option>');
                            }

                        });
                    }

                });
            });


            $('#tambahdata').click(function () {
                $('#saveBtn').val("tambah");
                $('#idbagian').val('');
                $('#id').val('');
                $('#idbagianawal').val('');
                $('#idbagianpelaksana').val('').trigger('change');
                $('#tahunpengadaan').val('').trigger('change');
                $('#kodebarang').val('').trigger('change');
                $('#formbagian').trigger("reset");
                $('#modelHeading').html("Tambah Pengajuan");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.kirimkepelaksana', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Kirim Data Ini Ke Unit Kerja Pelaksana Pengadaan?")){
                    $.ajax({
                        type: "GET",
                        url: "{{ url('kirimkepelaksanapengadaan','') }}"+"/"+id,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Data Berhasil Dikirim Ke Unit Kerja Pelaksana Pengadaan',
                                    icon: 'success'
                                })
                            } else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Kirim Data Gagal, Data Tidak Ditemukan atau Sudah Tidak Berstatus Draft',
                                    icon: 'error'
                                })
                            }
                            $('#tabelbagian').DataTable().ajax.reload();
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

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editpengajuan', function () {
                var id = $(this).data('id');
                $.get("{{ route('pengajuanrkbmnbagian.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Referensi Bagian RK");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#idbagianpelaksana').val(data.idbagianpelaksana);
                    $('#kodebarang').val(data.kodebarang).trigger('change');
                    $('#barangtersediadbr').val(data.barangtersediadbr);
                    $('#quantitas').val(data.quantitas);
                    $('#hargabarang').val(data.hargabarang);
                    $('#totalanggaran').val(data.totalanggaran);
                    $('#tahunpengadaan').val(data.tahunanggaranpengusulan).trigger('change');
                    $('#uraianbarang').val(data.uraianbarang);
                    $('#tujuanpenggunaan').val(data.tujuanpenggunaan);
                    $('#dokumenpendukungawal').val(data.dokumenpendukung);
                    document.getElementById('aktuallinkbukti').href = "{{env('APP_URL')."/".asset('storage')}}"+"/"+data.dokumenpendukung
                    hitungtotalanggaran();
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
                let form = document.getElementById('formbagian');
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
                    url: saveBtn === "tambah" ? "{{route('pengajuanrkbmnbagian.store')}}":"{{route('pengajuanrkbmnbagian.update','')}}"+'/'+id,
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
                        $('#idbagianpelaksana').val('').trigger('change');
                        $('#kodebarang').val('').trigger('change');
                        $('#dokumenpendukungawal').val('');
                        $('#id').val('');
                        $('#uraianbarang').val('');
                        $('#formbagian').trigger("reset");
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

            $('body').on('click', '.deletepengajuan', function () {

                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('pengajuanrkbmnbagian.destroy','') }}"+'/'+id,
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

            $('#kodebarang').on('change', function () {
                var kodebarang = this.value;
                $.ajax({
                    url: "{{url('ambildatabarangdalamdbr')}}",
                    type: "POST",
                    data: {
                        kodebarang: kodebarang,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        let barangtersediadbr = result['barang'][0].totalbarangdbr;
                        $('#barangtersediadbr').val(barangtersediadbr);
                    }

                });

                // Mendapatkan elemen combo box Jenis Kantor
                var jenisKantor = document.getElementById('jeniskantoratr');

                // Memeriksa apakah digit pertama adalah 2 atau 3
                if (kodebarang.charAt(0) === '2' || kodebarang.charAt(0) === '3') {
                    // Jika ya, tampilkan combo box Jenis Kantor
                    jenisKantor.style.display = 'block';
                } else {
                    // Jika tidak, sembunyikan combo box Jenis Kantor
                    jenisKantor.style.display = 'none';
                }
                // Pastikan combo box Jenis Kantor disembunyikan secara default
                document.getElementById('jeniskantoratr').style.display = 'none';
            });

            $('#hargabarang').on('input', function() {
                var hargabarang = $(this).val();

                // Hapus koma ribuan sebelum melakukan perhitungan
                hargabarang = removeThousandSeparator(hargabarang);
                hitungtotalanggaran();

                // Tambahkan koma ribuan setelah perhitungan
                $(this).val(addThousandSeparator(hargabarang));
            });

            $('#quantitas').on('input', function() {
                var quantitas = $(this).val();

                // Hapus koma ribuan sebelum melakukan perhitungan
                quantitas = removeThousandSeparator(quantitas);
                hitungtotalanggaran();

                // Tambahkan koma ribuan setelah perhitungan
                $(this).val(addThousandSeparator(quantitas));

            });

            function hitungtotalanggaran(){
                let quantitas = document.getElementById('quantitas').value;
                let hargabarang = document.getElementById('hargabarang').value;

                quantitas = removeThousandSeparator(quantitas);
                hargabarang = removeThousandSeparator(hargabarang);

                let totalanggaran = quantitas * hargabarang;
                document.getElementById('totalanggaran').value = addThousandSeparator(totalanggaran);
                document.getElementById('quantitas').value = addThousandSeparator(quantitas);
                document.getElementById('hargabarang').value = addThousandSeparator(hargabarang);
            }

            // Fungsi untuk menambahkan separator ribuan dengan koma
            function addThousandSeparator(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Fungsi untuk menghapus separator ribuan
            function removeThousandSeparator(number) {
                return number.replace(/,/g, '');
            }
        });

    </script>
@endsection
