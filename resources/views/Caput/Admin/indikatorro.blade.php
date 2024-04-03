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
                            @if(session('importgagal'))
                                <div class="alert alert-danger">
                                    {{session('importgagal')}}
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
                        <h3 class="card-title">{{$judul}}</h3>
                        <div class="btn-group float-sm-right" role="group">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahindikatorro"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="importindikatorro"> Import</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelindikatorro" class="table table-bordered table-striped tabelindikatorro">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>KRO</th>
                                <th>RO</th>
                                <th>Indeks</th>
                                <th>Uraian Indikator RO</th>
                                <th>Target</th>
                                <th>Satuan</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Satker</th>
                                <th>KRO</th>
                                <th>RO</th>
                                <th>Pengenal</th>
                                <th>Uraian Indikator RO</th>
                                <th>Target</th>
                                <th>Satuan</th>
                                <th>Jenis</th>
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
                                        <form id="formindikatorro" name="formindikatorro" class="form-horizontal" enctype="multipart/form-data">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="kegiatanawal" id="kegiatanawal">
                                            <input type="hidden" name="outputawal" id="outputawal">
                                            <input type="hidden" name="suboutputawal" id="suboutputawal">
                                            <input type="hidden" name="idbiroawal" id="idbiroawal">
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
                                            <input type="hidden" name="komponenawal" id="komponenawal">
                                            <input type="hidden" name="statusawal" id="statusawal">
                                            <input type="hidden" name="idkroawal" id="idkroawal">
                                            <input type="hidden" name="idroawal" id="idkroawal">
                                            <div class="form-group">
                                                <label for="tahunanggaran" class="col-sm-6 control-label">Tahun Anggaran</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control tahunanggaran" name="tahunanggaran" id="tahunanggaran" style="width: 100%;">
                                                        <option value="">Pilih Tahun Anggaran</option>
                                                        @foreach($datatahunanggaran as $data)
                                                            @if($data->kode == date('Y'))
                                                                <option value="{{ $data->kode }}" selected>{{ $data->tahunanggaran }}</option>
                                                            @endif
                                                            <option value="{{ $data->kode }}">{{ $data->tahunanggaran }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kodesatker" class="col-sm-6 control-label">Kode Satker</label>
                                                <div class="col-sm-12">
                                                <select class="form-control kodesatker" name="kodesatker" id="kodesatker" style="width: 100%;">
                                                    <option value="">Pilih Satker</option>
                                                    <option value="001012">Sekretariat Jenderal</option>
                                                    <option value="001030">Dewan</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kro" class="col-sm-6 control-label">KRO</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kro" name="kro" id="kro" style="width: 100%;">
                                                        <option value="">Pilih KRO</option>
                                                        @foreach($datakro as $data)
                                                            <option value="{{ $data->id }}">{{ $data->kodekegiatan.$data->kodeoutput." | ".$data->uraiankro }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="ro" class="col-sm-6 control-label">RO</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control ro" name="ro" id="ro" style="width: 100%;">
                                                        <option value="">Pilih RO</option>
                                                        @foreach($dataro as $data)
                                                            <option value="{{ $data->id }}">{{ $data->indeks." | ".$data->uraianro }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="kegiatan" class="col-sm-6 control-label">Kegiatan</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kegiatan" name="kegiatan" id="kegiatan" style="width: 100%;">
                                                        <option value="">Pilih Kegiatan</option>
                                                        @foreach($datakegiatan as $data)
                                                            <option value="{{ $data->kode }}">{{ $data->kode." | ".$data->deskripsi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="output" class="col-sm-6 control-label">Output</label>
                                                <div class="col-sm-12">
                                                <select class="form-control output" name="output" id="output" style="width: 100%;">
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="suboutput" class="col-sm-6 control-label">SubOutput</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control suboutput" name="suboutput" id="suboutput" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="komponen" class="col-sm-6 control-label">Komponen</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control komponen" name="komponen" id="komponen" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraianindikatorro" class="col-sm-6 control-label">Uraian Indikator RO</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control uraianindikatorro" id="uraianindikatorro" name="uraianindikatorro" placeholder="Uraian Indikator RO" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="target" class="col-sm-6 control-label">Target</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control target" id="target" name="target" placeholder="Target" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="deputi" class="col-sm-6 control-label">Deputi</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control iddeputi" name="iddeputi" id="iddeputi" style="width: 100%;" required>
                                                        <option value="">Pilih Deputi</option>
                                                        @foreach($datadeputi as $data)
                                                            <option value="{{ $data->id }}">{{ $data->uraiandeputi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Biro" class="col-sm-6 control-label">Biro</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbiro" name="idbiro" id="idbiro" style="width: 100%;" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Bagian" class="col-sm-6 control-label">Bagian</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control idbagian" name="idbagian" id="idbagian" style="width: 100%;" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Rencana</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="totalrencana" name="totalrencana" placeholder="Total Rencana" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Selisih Harus Dialokasikan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="totalselisih" name="totalselisih" placeholder="Total Selisih" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Januari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="januari" name="januari" placeholder="Total Rencana januari" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Februari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="februari" name="februari" placeholder="Total Rencana februari" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Maret</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="maret" name="maret" placeholder="Total Rencana maret" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target April</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="april" name="april" placeholder="Total Rencana April" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Mei</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="mei" name="mei" placeholder="Total Rencana mei" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Juni</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="juni" name="juni" placeholder="Total Rencana Juni" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Juli</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="juli" name="juli" placeholder="Total Rencana Juli" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Agustus</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="agustus" name="agustus" placeholder="Total Rencana Agustus" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target September</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="september" name="september" placeholder="Total Rencana September" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Oktober</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="oktober" name="oktober" placeholder="Total Rencana Oktober" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target November</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="november" name="november" placeholder="Total Rencana November" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Target Desember</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="desember" name="desember" placeholder="Total Rencana Desember" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="satuan" class="col-sm-6 control-label">Satuan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenisindikator" class="col-sm-6 control-label">Jenis Indikator</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control jenisindikator" name="jenisindikator" id="jenisindikator" style="width: 100%;">
                                                        <option value="">Jenis Indikator</option>
                                                        <option value="1">Internal</option>
                                                        <option value="2">Non Internal</option>
                                                    </select>
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
    <script type="text/javascript">
        function formatNumber(inputValue) {
            // Pengecekan apakah inputValue adalah string
            if (typeof inputValue !== 'string') {
                return '';
            }
            // Menghilangkan karakter selain angka
            var numericValue = inputValue.replace(/\D/g, '');

            // Memformat angka dengan separator ribuan
            var formattedNumber = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return formattedNumber;
        }


        // Fungsi untuk memformat input dengan separator ribuan
        function formatInputWithThousandSeparator(inputElement) {
            inputElement.value = formatNumber(inputElement.value);
        }

        function cektotalrencana() {
            let target = parseInt(document.getElementById('target').value.replace(/\D/g, '')); // Parse value ke integer dan hapus non-digit
            let totalrencana = 0;
            let inputs = document.querySelectorAll('.inputFormat');

            // Objek untuk menyimpan nilai input bulan per bulan
            let bulanInputs = {
                januari: parseInt(document.getElementById('januari').value.replace(/\D/g, '')),
                februari: parseInt(document.getElementById('februari').value.replace(/\D/g, '')),
                maret: parseInt(document.getElementById('maret').value.replace(/\D/g, '')),
                april: parseInt(document.getElementById('april').value.replace(/\D/g, '')),
                mei: parseInt(document.getElementById('mei').value.replace(/\D/g, '')),
                juni: parseInt(document.getElementById('juni').value.replace(/\D/g, '')),
                juli: parseInt(document.getElementById('juli').value.replace(/\D/g, '')),
                agustus: parseInt(document.getElementById('agustus').value.replace(/\D/g, '')),
                september: parseInt(document.getElementById('september').value.replace(/\D/g, '')),
                oktober: parseInt(document.getElementById('oktober').value.replace(/\D/g, '')),
                november: parseInt(document.getElementById('november').value.replace(/\D/g, '')),
                desember: parseInt(document.getElementById('desember').value.replace(/\D/g, ''))
            };

            // Menghitung total rencana
            for (let bulan in bulanInputs) {
                totalrencana += bulanInputs[bulan];
            }

            // Memeriksa apakah total rencana melebihi pagu anggaran
            if (totalrencana > target) {
                // Jika melebihi, kurangi nilai input pada bulan Desember terlebih dahulu
                let sisa = totalrencana - target;
                bulanInputs.desember -= sisa;

                // Jika nilai input pada bulan Desember sudah mencapai 0, kurangi bulan-bulan sebelumnya
                if (bulanInputs.desember < 0) {
                    let kurangiBulan = Math.abs(bulanInputs.desember);
                    bulanInputs.desember = 0;

                    // Kurangi nilai bulan-bulan sebelumnya
                    for (let bulan of ['november', 'oktober', 'september', 'agustus', 'juli', 'juni', 'mei', 'april', 'maret', 'februari']) {
                        if (kurangiBulan <= 0) break;
                        let kurangi = Math.min(kurangiBulan, bulanInputs[bulan]);
                        bulanInputs[bulan] -= kurangi;
                        kurangiBulan -= kurangi;
                    }
                }
            }else{
                let harusdialokasikan = target - totalrencana;
                console.log(harusdialokasikan);
                //harusdialokasikan = harusdialokasikan.toString();
                //harusdialokasikan = formatInputWithThousandSeparator(harusdialokasikan);
                document.getElementById('totalselisih').value = harusdialokasikan;
            }

            // Menghitung total rencana kembali setelah koreksi
            totalrencana = 0;
            for (let bulan in bulanInputs) {
                totalrencana += bulanInputs[bulan];
            }

            // Menetapkan kembali nilai input yang sudah dimodifikasi
            for (let bulan in bulanInputs) {
                document.getElementById(bulan).value = formatNumber(bulanInputs[bulan].toString());
            }

            // Memperbarui total rencana pada elemen totalrencana
            document.getElementById('totalrencana').value = formatNumber(totalrencana.toString());

            // Menghitung selisih antara total rencana dan pagu anggaran
            let selisih = target - totalrencana;

            // Menampilkan nilai selisih di input field selisih
            document.getElementById('totalselisih').value = formatNumber(selisih.toString());

            // Menonaktifkan tombol simpan jika total rencana tidak sama dengan pagu anggaran
            let tombolSimpan = document.getElementById('saveBtn');
            if (totalrencana !== target) {
                tombolSimpan.disabled = true;
            } else {
                tombolSimpan.disabled = false;
            }
        }


        // Menambahkan event listener untuk setiap input bulan
        document.querySelectorAll('.inputFormat').forEach(input => {
            input.addEventListener('input', function() {
                formatInputWithThousandSeparator(input); // Memformat input dengan separator ribuan saat input berubah
                cektotalrencana(); // Memeriksa total rencana setiap kali input berubah
            });
        });

        $(function () {
            $('.tahunanggaran').select2({
                theme: 'bootstrap4',
            })

            $('.kodesatker').select2({
                theme: 'bootstrap4',
            })

            $('.kegiatan').select2({
                theme: 'bootstrap4',
            })

            $('.output').select2({
                theme: 'bootstrap4',
            })

            $('.suboutput').select2({
                theme: 'bootstrap4',

            })

            $('.komponen').select2({
                theme: 'bootstrap4',

            })

            $('.jenisindikator').select2({
                theme: 'bootstrap4',
            })

            $('.kro').select2({
                theme: 'bootstrap4',
            })

            $('.ro').select2({
                theme: 'bootstrap4',
            })

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelindikatorro tfoot th').each( function (i) {
                var title = $('#tabelindikatorro thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            var table = $('.tabelindikatorro').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"{{route('indikatorro.index')}}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'tahunanggaran', name: 'tahunanggaran'},
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'idkro', name: 'idkro'},
                    {data: 'idro', name: 'idkro'},
                    {data: 'indeks', name: 'indeks'},
                    {data: 'uraianindikatorro', name: 'uraianindikatorro'},
                    {data: 'target', name: 'target'},
                    {data: 'satuan', name: 'satuan'},
                    {data: 'jenisindikator', name: 'jenisindikator'},
                    {data: 'status', name: 'status'},
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
            $('#tambahindikatorro').click(function () {
                $('#saveBtn').val("tambah");
                $('#formrindikatoro').trigger("reset");
                $('#modelHeading').html("Tambah Indikator RO");
                $('#ajaxModel').modal('show');
            });

            $('#importindikatorro').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Indikator RO dari Data Anggaran Terbaru ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="{{URL::to('importindikatorro')}}";
                }
            });


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editindikatorro', function () {
                var idindikatorro = $(this).data('id');
                $.get("{{ route('indikatorro.index') }}" +'/' + idindikatorro +'/edit', function (data) {
                    $('#modelHeading').html("Edit RO");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#tahunanggaran').val(data.tahunanggaran).trigger('change');
                    $('#kodesatker').val(data.kodesatker).trigger('change');
                    $('#kro').val(data.idkro).trigger('change');
                    $('#ro').val(data.idro).trigger('change');
                    $('#iddeputi').val(data.iddeputi).trigger('change');
                    $('#idbiroawal').val(data.idbiro);
                    $('#idbiro').val(data.idbiro).trigger('change');
                    $('#idbagianawal').val(data.idbagian);
                    $('#idbagian').val(data.idbagian).trigger('change');
                    $('#kegiatan').val(data.kodekegiatan).trigger('change');
                    $('#kegiatanawal').val(data.kodekegiatan);
                    $('#outputawal').val(data.kodeoutput);
                    $('#suboutputawal').val(data.kodesuboutput);
                    $('#komponenawal').val(data.kodekomponen);
                    $('#statusawal').val(data.status);
                    $('#uraianindikatorro').val(data.uraianindikatorro);
                    $('#jenisindikator').val(data.jenisindikator).trigger('change');
                    $('#status').val(data.status);
                    $('#target').val(formatNumber(data.target.toString()));
                    $('#januari').val(formatNumber(data.target1.toString()));
                    $('#februari').val(formatNumber(data.target2.toString()));
                    $('#maret').val(formatNumber(data.target3.toString()));
                    $('#april').val(formatNumber(data.target4.toString()));
                    $('#mei').val(formatNumber(data.target5.toString()));
                    $('#juni').val(formatNumber(data.target6.toString()));
                    $('#juli').val(formatNumber(data.target7.toString()));
                    $('#agustus').val(formatNumber(data.target8.toString()));
                    $('#september').val(formatNumber(data.target9.toString()));
                    $('#oktober').val(formatNumber(data.target10.toString()));
                    $('#november').val(formatNumber(data.target11.toString()));
                    $('#desember').val(formatNumber(data.target12.toString()));
                    $('#satuan').val(data.satuan);


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
                let form = document.getElementById('formindikatorro');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                var id = document.getElementById('id').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }
                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('indikatorro.store')}}":"{{route('indikatorro.update','')}}"+'/'+id,
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
                        $('#tahunanggaran').val('').trigger('change');
                        $('#kodesatker').val('').trigger('change');
                        $('#kegiatan').val('').trigger('change');
                        $('#output').val('').trigger('change');
                        $('#jenisindikator').val('').trigger('change');
                        $('#kegiatanawal').val('');
                        $('#outputawal').val('');
                        $('#suboutputawal').val('');
                        $('#komponenawal').val('');
                        $('#statusawal').val('');
                        $('#formro').trigger("reset");
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
            $('body').on('click', '.deleteindikatorro', function () {

                var idindikatorro = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('indikatorro.destroy','') }}"+'/'+idindikatorro,
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

        $('#output').on('change', function () {
            var  kodeoutput = this.value;
            var  kodekegiatan = document.getElementById('kegiatan').value;
            $.ajax({
                url: "{{url('ambildatasuboutput')}}",
                type: "POST",
                data: {
                    kodekegiatan: kodekegiatan,
                    kodeoutput: kodeoutput,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var suboutput = document.getElementById('suboutputawal').value;
                    $('#suboutput').html('<option value="">Pilih SubOutput</option>');
                    $.each(result.suboutput, function (key, value) {
                        if (suboutput === value.kodesuboutput) {
                            $('select[name="suboutput"]').append('<option value="'+value.kodesuboutput+'" selected>'+value.kodesuboutput+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#suboutput").append('<option value="' + value.kodesuboutput + '">' +value.kodesuboutput+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#suboutput').on('change', function () {
            var  kodesuboutput = this.value;
            var  kodekegiatan = document.getElementById('kegiatan').value;
            var  kodeoutput = document.getElementById('output').value;
            $.ajax({
                url: "{{url('ambildatakomponen')}}",
                type: "POST",
                data: {
                    kodekegiatan: kodekegiatan,
                    kodeoutput: kodeoutput,
                    kodesuboutput: kodesuboutput,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var komponen = document.getElementById('komponenawal').value;
                    $('#komponen').html('<option value="">Pilih Komponen</option>');
                    $.each(result.komponen, function (key, value) {
                        if (komponen === value.kodekomponen) {
                            $('select[name="komponen"]').append('<option value="'+value.kodekomponen+'" selected>'+value.kodekomponen+" | "+value.deskripsi+'</option>').trigger('change')
                        }else{
                            $("#komponen").append('<option value="' + value.kodekomponen + '">' +value.kodekomponen+" | "+value.deskripsi+ '</option>');
                        }

                    });
                }

            });
        });

        $('#iddeputi').on('change', function () {
            var iddeputi = this.value;
            $.ajax({
                url: "{{url('ambildatabiro')}}",
                type: "POST",
                data: {
                    iddeputi: iddeputi,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var idbiro = document.getElementById('idbiroawal').value;
                    $('#idbiro').html('<option value="">Pilih Biro</option>');
                    $.each(result.biro, function (key, value) {
                        if (idbiro == value.id) {
                            $('select[name="idbiro"]').append('<option value="'+value.id+'" selected>'+value.uraianbiro+'</option>').trigger('change')
                        }else{
                            $("#idbiro").append('<option value="' + value.id + '">' + value.uraianbiro + '</option>');
                        }

                    });
                }

            });
        });

        $('#idbiro').on('change', function () {
            var idbiro = this.value;
            $.ajax({
                url: "{{url('ambildatabagian')}}",
                type: "POST",
                data: {
                    idbiro: idbiro,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    var idbagian = document.getElementById('idbagianawal').value;
                    $('#idbagian').html('<option value="">Pilih Bagian</option>');
                    $.each(result.bagian, function (key, value) {
                        if (idbagian == value.id) {
                            $('select[name="idbagian"]').append('<option value="'+value.id+'" selected>'+value.uraianbagian+'</option>').trigger('change')
                        }else{
                            $("#idbagian").append('<option value="' + value.id + '">' + value.uraianbagian + '</option>');
                        }

                    });
                }

            });
        });
    </script>
@endsection
