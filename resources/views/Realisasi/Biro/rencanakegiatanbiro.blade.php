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
                        <!-- <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahtransaksi"> Tambah Data</a> -->
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-header">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <select class="form-control idbagian" name="idbagian" id="idbagian" style="width: 100%;">
                                    <option value="">Pilih Bagian</option>
                                    @foreach($databagian as $data)
                                        <option value="{{ $data->id }}">{{ $data->uraianbagian }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabelkasbon" class="table table-bordered table-striped tabelkasbon">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Uraian Kegiatan POK</th>
                                <th>Uraian Kegiatan Bagian</th>
                                <th>Pagu Anggaran</th>
                                <th>Total Rencana</th>
                                <th>Status Rencana</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tahun Anggaran</th>
                                <th>Satker</th>
                                <th>Bagian</th>
                                <th>Pengenal</th>
                                <th>Uraian Kegiatan POK</th>
                                <th>Uraian Kegiatan Bagian</th>
                                <th>Pagu Anggaran</th>
                                <th>Total Rencana</th>
                                <th>Status Rencana</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
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
                                        <form id="formrencanakegiatanbagian" name="formkasbon" class="form-horizontal">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" name="idbagian" id="idbagian">
                                            <input type="hidden" name="pengenalawal" id="pengenalawal">
                                            <div class="form-group">
                                                <label for="" class="col-sm-6 control-label">Satker</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control kdsatker" name="kdsatker" id="kdsatker" style="width: 100%;" disabled>
                                                        <option value="">Pilih Satker</option>
                                                        <option value="001012">Setjen</option>
                                                        <option value="001030">Dewan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Area" class="col-sm-6 control-label">Pengenal</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control pengenal" name="pengenal" id="pengenal" style="width: 100%;" disabled>
                                                        <option>Pilih Pengenal</option>
                                                        @foreach($datapengenal as $data)
                                                            <option value="{{ $data->pengenal }}">{{ $data->pengenal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraiankegiatan" class="col-sm-6 control-label">Uraian Kegiatan POK</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control uraiankegiatanpok" id="uraiankegiatanpok" name="uraiankegiatanpok" placeholder="Uraian Kegiatan" value="" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraiankegiatan" class="col-sm-6 control-label">Uraian Kegiatan Rinci</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="uraiankegiatanrinci" name="uraiankegiatanrinci" placeholder="Uraian Kegiatan Rinci" value="" required=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Pagu Anggaran</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="paguanggaran" name="paguanggaran" placeholder="Pagu Anggaran" value="" maxlength="500" readonly>
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
                                                <label for="peruntukan" class="col-sm-6 control-label">Januari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="januari" name="januari" placeholder="Total Rencana januari" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Februari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="februari" name="februari" placeholder="Total Rencana februari" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Maret</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="maret" name="maret" placeholder="Total Rencana maret" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">April</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="april" name="april" placeholder="Total Rencana April" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Mei</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="mei" name="mei" placeholder="Total Rencana mei" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Juni</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="juni" name="juni" placeholder="Total Rencana Juni" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Juli</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="juli" name="juli" placeholder="Total Rencana Juli" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Agustus</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="agustus" name="agustus" placeholder="Total Rencana Agustus" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">September</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="september" name="september" placeholder="Total Rencana September" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Oktober</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="oktober" name="oktober" placeholder="Total Rencana Oktober" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">November</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="november" name="november" placeholder="Total Rencana November" value="" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Desember</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="desember" name="desember" placeholder="Total Rencana Desember" value="" maxlength="500">
                                                </div>
                                            </div>

                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary saveBtn" id="saveBtn" value="create">Simpan</button>
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
    <script>
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

        // Fungsi untuk melakukan pengecekan total rencana
        function cektotalrencana() {
            let paguanggaran = parseInt(document.getElementById('paguanggaran').value.replace(/\D/g, '')); // Parse value ke integer dan hapus non-digit
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
            if (totalrencana > paguanggaran) {
                // Jika melebihi, kurangi nilai input pada bulan Desember terlebih dahulu
                let sisa = totalrencana - paguanggaran;
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
                let harusdialokasikan = paguanggaran - totalrencana;
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
            let selisih = paguanggaran - totalrencana;

            // Menampilkan nilai selisih di input field selisih
            document.getElementById('totalselisih').value = formatNumber(selisih.toString());

            // Menonaktifkan tombol simpan jika total rencana tidak sama dengan pagu anggaran
            let tombolSimpan = document.getElementById('saveBtn');
            if (totalrencana !== paguanggaran) {
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

    </script>

    <script type="text/javascript">
        $(function () {
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

            $('.kdsatker').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('.idbagian').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

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
            var table = $('.tabelkasbon').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatarencanakegiatanbiro')}}",
                columns: [
                    {data:'id',name:'id'},
                    {data: 'tahunanggaran',name:'tahunanggaran'},
                    {data: 'kdsatker', name: 'kdsatker'},
                    {data: 'bagian', name:'bagianpengajuanrelation.uraianbagian'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'uraiankegiatanpok', name: 'uraiankegiatanpok'},
                    {data: 'uraiankegiatanbagian', name: 'uraiankegiatanbagian'},
                    {data: 'paguanggaran', name: 'paguanggaran'},
                    {data: 'totalrencana', name: 'totalrencana'},
                    {data: 'statusrencana', name: 'statusrencana'},
                    {data: 'pok1', name: 'pok1'},
                    {data: 'pok2', name: 'pok2'},
                    {data: 'pok3', name: 'pok3'},
                    {data: 'pok4', name: 'pok4'},
                    {data: 'pok5', name: 'pok5'},
                    {data: 'pok6', name: 'pok6'},
                    {data: 'pok7', name: 'pok7'},
                    {data: 'pok8', name: 'pok8'},
                    {data: 'pok9', name: 'pok9'},
                    {data: 'pok10', name: 'pok10'},
                    {data: 'pok11', name: 'pok11'},
                    {data: 'pok12', name: 'pok12'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                columnDefs: [
                    {
                        targets: 7,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 8,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 10,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 11,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 12,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 13,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 14,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 15,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 16,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 17,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 18,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 19,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 20,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                    {
                        targets: 21,
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
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('rencanakegiatanbiro.index') }}" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Rencana");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#pengenal').val(data.pengenal).trigger('change');
                    $('#idbagianawal').val(data.idbagian);
                    $('#pengenalawal').val(data.pengenal);
                    $('#uraiankegiatanpok').val(data.uraiankegiatanpok);
                    $('#uraiankegiatanrinci').val(data.uraiankegiatanbagian);
                    $('#paguanggaran').val(formatNumber(data.paguanggaran.toString()));
                    $('#totalrencana').val(formatNumber(data.totalrencana.toString()));
                    $('#januari').val(formatNumber(data.pok1.toString()));
                    $('#februari').val(formatNumber(data.pok2.toString()));
                    $('#maret').val(formatNumber(data.pok3.toString()));
                    $('#april').val(formatNumber(data.pok4.toString()));
                    $('#mei').val(formatNumber(data.pok5.toString()));
                    $('#juni').val(formatNumber(data.pok6.toString()));
                    $('#juli').val(formatNumber(data.pok7.toString()));
                    $('#agustus').val(formatNumber(data.pok8.toString()));
                    $('#september').val(formatNumber(data.pok9.toString()));
                    $('#oktober').val(formatNumber(data.pok10.toString()));
                    $('#november').val(formatNumber(data.pok11.toString()));
                    $('#desember').val(formatNumber(data.pok12.toString()));
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formrencanakegiatanbagian');
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
                    url: saveBtn === "tambah" ? "{{route('rencanakegiatanbiro.store')}}":"{{route('rencanakegiatanbiro.update','')}}"+'/'+id,
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
                        $('#formrencanakegiatanbagian').trigger("reset");
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

            $('#idbagian').on('change',function (){
                let idbagian = document.getElementById('idbagian').value;
                var table = $('.tabelkasbon').DataTable();
                table.ajax.url("{{route('getdatarencanakegiatanbiro','')}}"+"/"+idbagian).load();
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
                        url: "{{ route('rencanakegiatanbiro.destroy','') }}"+'/'+id,
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
                        url: "{{ route('ajukanrencanakeppk','') }}"+'/'+id,
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
        });

    </script>
@endsection
