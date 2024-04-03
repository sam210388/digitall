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
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdetil"> Tambah Data</a>
                            <a class="btn btn-info float-sm-right" href="javascript:void(0)" id="kembali"> Kembali</a>
                        </div>
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetilrencana" class="table table-bordered table-striped tabeldetilrencana">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rencana Kegiatan</th>
                                <th>Bulan Pencairan</th>
                                <th>Pengenal</th>
                                <th>Nilai Rencana</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="modal fade" id="ajaxModelDetilRencana" aria-hidden="true" data-focus="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formdetilrencana" name="formdetilrencana" class="form-horizontal">
                                            <input type="hidden" name="iddetilrencana" id="iddetilrencana">
                                            <input type="hidden" name="bulanpencairandetil" id="bulanpencairandetil" value="{{$bulanpencairan}}">
                                            <input type="hidden" name="idrencanakegiatan" id="idrencanakegiatan" value="{{$idrencanakegiatan}}">
                                            <input type="hidden" name="kdsatker" id="kdsatker" value="{{$kdsatker}}">
                                            <input type="hidden" name="pengenalawal" id="pengenalawal">


                                            <div class="form-group">
                                                <label for="kdsatkerdetil" class="col-sm-6 control-label">Deputi</label>
                                                <div class="col-sm-12">
                                                    <select class="form-control pengenal" name="pengenal" id="pengenal" style="width: 100%;" required>
                                                        <option value="">Pilih Pengenal</option>
                                                        @foreach($datapengenal as $data)
                                                            <option value="{{ $data->pengenal }}">{{ $data->pengenal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Pagu Pengenal</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="pagupengenal" name="pagupengenal" placeholder="Pagu Pengenal" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Realisasi Berjalan</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="realisasiberjalan" name="realisasiberjalan" placeholder="Realisasi Berjalan" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Rencana Sebelumnya</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="totalrencanasebelumnya" name="totalrencanasebelumnya" placeholder="Rencana Pengenal" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Nilai Rencana</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="nilairencana" name="nilairencana" placeholder="Nilai Rencana" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Rencana Pengenal</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="totalrencanapengenal" name="totalrencanapengenal" placeholder="Rencana Pengenal" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Sisa Pagu Pengenal</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control inputFormat" id="sisapagupengenal" name="sisapagupengenal" placeholder="Sisa Nilai Pengenal" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary saveBtnDetil" id="saveBtnDetil" value="create">Simpan</button>
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

            $('.bulanpencairan').select2({
                width: '100%',
                theme: 'bootstrap4',
            })

            $('#tabeldetilrencana thead th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });
            var idrencanakegiatan = document.getElementById('idrencanakegiatan').value;
            var table2 = $('.tabeldetilrencana').DataTable({
                destroy:true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getdetilrencanakegiatanindukbagian') }}",
                    type: "GET",
                    data: function(d) {
                        // Tambahkan data yang ingin Anda kirim ke server di sini
                        var data = {
                            idrencanakegiatan: idrencanakegiatan,
                        };
                        return $.extend({}, d, data);
                    }
                },
                columns: [
                    {data:'id',name:'id'},
                    {data: 'uraianrencanakegiatan', name:'rencanakegiatanrelation.uraiankegiatan'},
                    {data: 'bulanpencairan', name: 'bulanpencairan'},
                    {data: 'pengenal', name: 'pengenal'},
                    {data: 'rupiah', name: 'rupiah'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                columnDefs: [
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
                    },
                ],
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print' // Menambahkan tombol untuk ekspor ke format yang diinginkan
                ]
            });
            table2.buttons().container()
                .appendTo( $('.col-sm-6:eq(0)', table2.table().container() ) );

            // Filter event handler
            $( table2.table().container() ).on( 'keyup', 'thead input', function () {
                table2
                    .column( $(this).data('index') )
                    .search( this.value )
                    .draw();
            });

            $('#tambahdetil').click(function () {
                // Tampilkan modal tambah data detil
                $('#ajaxModelDetilRencana').modal('show');
                $('#saveBtnDetil').val("tambah");
                $('#pengenalawal').val('');
                $('#kdsatker').val('').trigger('change');
                $('#pengenal').val('').trigger('change');
                $('#pagupengenal').val('');
                $('#totalrencanapengenal').val('');
                $('#totalrencanasebelumnya').val('');
                $('#sisapagupengenal').val('');
                $('#nilairencana').val('');

            });

            $('#kembali').click(function () {
                window.location="{{URL::to('rencanakegiatanbagian')}}"
            });

            // Event handler untuk menangani klik pada tombol edit data
            $('body').on('click', '.editdetil', function () {
                // Tampilkan modal edit data detil
                var id = $(this).data('id');
                $.get("{{ route('editdetilrencana','') }}" +'/' + id, function (data) {
                    $('#modelHeading').html("Edit Detil");
                    $('#saveBtnDetil').val("edit");
                    $('#ajaxModelDetilRencana').modal('show');
                    $('#iddetilrencana').val(data[0]['id']);
                    console.log(document.getElementById('iddetilrencana').value);
                    $('#pengenalawal').val(data[0]['pengenal']);
                    $('#pengenal').val(data[0]['pengenal']).trigger('change');
                    $('#bulanpencairandetil').val(data[0]['bulanpencairan']);
                    $('#nilairencana').val(addThousandSeparator(data[0]['rupiah']));

                })
            });

            // Event handler untuk menangani klik pada tombol hapus data
            $('body').on('click', '.deletedetil', function () {
                var id = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Hapus Data Ini!")){
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('deletedetilrencana','') }}"+'/'+id,
                        success: function (data) {
                            if (data.status === "berhasil"){
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
                            table2.draw();
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

                            $('#saveBtnDetil').html('Simpan Data');
                        },
                    });
                }
            });

            $('#saveBtnDetil').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formdetilrencana');
                let fd = new FormData(form);
                let saveBtnDetil = document.getElementById('saveBtnDetil').value;
                fd.append('saveBtnDetil',saveBtnDetil)
                if(saveBtnDetil === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtnDetil === "tambah" ? "{{route('simpandetilrencana')}}":"{{route('updatedetilrencana')}}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status === "berhasil"){
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
                        $('#formdetilrencana').trigger("reset");
                        $('#ajaxModelDetilRencana').modal('hide');
                        $('#saveBtnDetil').html('Simpan Data');
                        table2.draw();

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
                        //$('#saveBtnDetil').html('Simpan Data');
                    },

                });
            });

            $('#nilairencana').on('input', function() {
                var nilaiRencana = $(this).val();

                // Hapus koma ribuan sebelum melakukan perhitungan
                nilaiRencana = removeThousandSeparator(nilaiRencana);


                // Tambahkan koma ribuan setelah perhitungan
                $(this).val(addThousandSeparator(nilaiRencana));
                checkAndSetSisaPagu();
            });

            $('#pengenal').on('change', function () {
                var pengenal = this.value;
                var iddetilrencana = document.getElementById('iddetilrencana').value;
                if(pengenal !== ""){
                    $.ajax({
                        url: "{{ url('ambildatapengenal') }}",
                        type: "POST",
                        data: {
                            pengenal: pengenal,
                            iddetilrencana: iddetilrencana,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function (result) {
                            var paguanggaran = result.data[0].paguanggaran;
                            var totalrencanasebelumnya = result.data[1][0].totalrencanasebelumnya;
                            var realisasiberjalan = result.data[0].rsd12;
                            console.log(totalrencanasebelumnya);

                            // Pastikan totalrencana tidak null sebelum menempatkannya
                            if (totalrencanasebelumnya !== null) {
                                $('#totalrencanasebelumnya').val(addThousandSeparator(totalrencanasebelumnya));

                            } else {
                                $('#totalrencanasebelumnya').val(0);
                            }

                            if (realisasiberjalan !== null) {
                                $('#realisasiberjalan').val(addThousandSeparator(realisasiberjalan));

                            } else {
                                $('#realisasiberjalan').val(0);
                            }

                            // Tempatkan nilai paguanggaran ke dalam field
                            $('#pagupengenal').val(addThousandSeparator(paguanggaran));
                            checkAndSetSisaPagu();
                        }
                    });
                }
            });

        });

        // Fungsi untuk menambahkan separator ribuan dengan koma
        function addThousandSeparator(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Fungsi untuk menghapus separator ribuan
        function removeThousandSeparator(number) {
            return number.replace(/,/g, '');
        }

        // Fungsi untuk memeriksa dan mengupdate nilai sisapagupengenal
        function checkAndSetSisaPagu() {
            var pagu = parseFloat(removeThousandSeparator($('#pagupengenal').val()));
            var totalRencanaSebelumnya = parseFloat(removeThousandSeparator($('#totalrencanasebelumnya').val()));
            var realisasiBerjalan = parseFloat(removeThousandSeparator($('#realisasiberjalan').val()));
            var nilaiRencana = parseFloat(removeThousandSeparator($('#nilairencana').val()));

            var sisapagupengenal = pagu - (realisasiBerjalan+totalRencanaSebelumnya + nilaiRencana);
            var totalrencana = totalRencanaSebelumnya+nilaiRencana;
            $('#sisapagupengenal').val(addThousandSeparator(sisapagupengenal));
            $('#totalrencanapengenal').val(addThousandSeparator(totalrencana));

            // Jika nilai sisapagupengenal kurang dari 0, disable tombol simpan
            if (sisapagupengenal < 0) {
                $('#saveBtnDetil').prop('disabled', true);
            } else {
                $('#saveBtnDetil').prop('disabled', false);
            }
        }

    </script>
@endsection
