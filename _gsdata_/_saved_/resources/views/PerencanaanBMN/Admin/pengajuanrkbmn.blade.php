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
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="dokumenpendukungawal" id="dokumenpendukungawal">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Bagian Pelaksana</label>
                                                    <select class="form-control idbagianpelaksana" name="idbagianpelaksana" id="idbagianpelaksana" style="width: 100%;" disabled>
                                                        <option value="">Pilih Bagian Pelaksana</option>
                                                        @foreach($databagianrk as $data)
                                                            <option value="{{ $data->idbagian }}">{{ $data->uraianbagian }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="deputi" class="col-sm-6 control-label">Usulan Tahun Pengadaan</label>
                                                    <select class="form-control tahunpengadaan" name="tahunpengadaan" id="tahunpengadaan" style="width: 100%;" disabled>
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
                                                    <select class="form-control kodebarang" name="kodebarang" id="kodebarang" style="width: 100%;" disabled>
                                                        <option value="">Pilih Kode Barang</option>
                                                        @foreach($databmnrk as $data)
                                                            <option value="{{ $data->kdbrg }}">{{ $data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="keterangan" class="col-sm-6 control-label">Barang Tersedia Dalam DBR</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="barangtersediadbr" name="barangtersediadbr" placeholder="Barang Tersedia Dalam DBR" value="" maxlength="100" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="keterangan" class="col-sm-6 control-label">Uraian/Spesifikasi Barang</label>
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="uraianbarang" name="uraianbarang" placeholder="Uraian/Spesifikasi" value="" required="" readonly></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Tujuan Penggunaan</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="tujuanpenggunaan" name="tujuanpenggunaan" placeholder="Tujuan Penggunaan" value="" maxlength="100" required="" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Kuantitas</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" class="form-control" id="quantitas" name="quantitas" placeholder="Kuantitas Pengajuan" value="" maxlength="100" required="" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Harga Barang</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="hargabarang" name="hargabarang" placeholder="Harga Barang" value="" maxlength="100" required="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="nilaibukti" class="col-sm-6 control-label">Total Anggaran</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="totalanggaran" name="totalanggaran" placeholder="Total Harga Barang" value="" maxlength="100" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="file" class="col-sm-6 control-label">Dokumen Pendukung</label>
                                                    <div class="form-group" id="linkbukti" aria-hidden="true">
                                                        <div class="col-sm-12">
                                                            <a href="#" id="aktuallinkbukti">Lihat Bukti</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="setujutolak" id="setuju" value="setuju">
                                                        <label class="form-check-label" for="inlineRadio1">Setuju</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="setujutolak" id="tolak" value="tolak">
                                                        <label class="form-check-label" for="inlineRadio2">Tolak</label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Alasan Penolakan" value="" maxlength="100" required="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-group" role="group">
                                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
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
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            bsCustomFileInput.init();
            $('input[type=radio][name=setujutolak]').change(function() {
                if (this.value === 'setuju') {
                    $('#keterangan').prop('disabled', true);
                }
                else if (this.value === 'tolak') {
                    $('#keterangan').prop('disabled', false);
                }
            });

            $('.idbagianpelaksana').select2({
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

            $('.tahunpelaksanaan').select2({
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
                ajax:"{{route('persetujuanusulanrkbmn')}}",
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


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editpengajuan', function () {
                var id = $(this).data('id');
                $.get("{{ route('pengajuanrkbmnbagian.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Proses Pengajuan Usulan Kebutuhan");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#idbagianpelaksana').val(data.idbagianpelaksana).trigger('change');
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
                    url: "{{route('prosespersetujuanbmn','')}}"+'/'+id,
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
