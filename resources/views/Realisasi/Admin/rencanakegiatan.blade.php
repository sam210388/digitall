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
                        {!! $button !!}
                    </div>
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
                                            <input type="hidden" name="idbagianawal" id="idbagianawal">
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
                                                        <textarea class="form-control uraiankegiatanpok" id="uraiankegiatanpok" name="uraiankegiatanpok" placeholder="Uraian Kegiatan" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="uraiankegiatan" class="col-sm-6 control-label">Uraian Kegiatan Rinci</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <textarea class="form-control" id="uraiankegiatanrinci" name="uraiankegiatanrinci" placeholder="Uraian Kegiatan Rinci" required="" readonly></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Pagu Anggaran</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control paguanggaran" id="paguanggaran" name="paguanggaran" placeholder="Pagu Anggaran" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Total Rencana</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control totalrencana" id="totalrencana" name="totalrencana" placeholder="Total Rencana" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Januari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control januari" id="januari" name="januari" placeholder="Total Rencana januari" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Februari</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control februari" id="februari" name="februari" placeholder="Total Rencana februari" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Maret</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control maret" id="maret" name="maret" placeholder="Total Rencana maret" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">April</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control april" id="april" name="april" placeholder="Total Rencana April" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Mei</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control mei" id="mei" name="mei" placeholder="Total Rencana mei" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Juni</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control juni" id="juni" name="juni" placeholder="Total Rencana Juni" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Juli</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control juli" id="juli" name="juli" placeholder="Total Rencana Juli" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Agustus</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control agustus" id="agustus" name="agustus" placeholder="Total Rencana Agustus" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">September</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control september" id="september" name="september" placeholder="Total Rencana September" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Oktober</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control oktober" id="oktober" name="oktober" placeholder="Total Rencana Oktober" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">November</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control november" id="november" name="november" placeholder="Total Rencana November" value="" maxlength="500" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="peruntukan" class="col-sm-6 control-label">Desember</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control desember" id="desember" name="desember" placeholder="Total Rencana Desember" value="" maxlength="500" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button class="btn btn-primary" id="saveBtn" value="create">Tutup</button>
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
        $(function () {
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

            $('#exportrencana').click(function (){
                window.location="{{URL::to('exportrencanapenarikan')}}";
            });

            $('#tutupperiode').click(function (){
                window.location="{{URL::to('tutupperioderencana')}}";
            });

            $('#bukaperiode').click(function (){
                window.location="{{URL::to('bukaperioderencana')}}";
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
                destroy: true,
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                ajax:"{{route('getdatarencanakegiatan')}}",
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

            $('#idbagian').on('change',function (){
                let idbagian = document.getElementById('idbagian').value;
                var table = $('.tabelkasbon').DataTable();
                table.ajax.url("{{route('getdatarencanakegiatan','')}}"+"/"+idbagian).load();
            });


            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.edittransaksi', function () {
                var id = $(this).data('id');
                $.get("{{ route('lihatrencanakegiatan','') }}" +'/' + id, function (data) {
                    $('#modelHeading').html("Lihat Rencana");
                    $('#saveBtn').val("edit");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#kdsatker').val(data.kdsatker).trigger('change');
                    $('#pengenal').val(data.pengenal).trigger('change');
                    $('#idbagianawal').val(data.idbagian);
                    $('#pengenalawal').val(data.pengenal);
                    $('#uraiankegiatanpok').val(data.uraiankegiatanpok);
                    $('#uraiankegiatanrinci').val(data.uraiankegiatanbagian);
                    $('#paguanggaran').val(data.paguanggaran);
                    $('#totalrencana').val(data.totalrencana);
                    $('#januari').val(data.pok1);
                    $('#februari').val(data.pok2);
                    $('#maret').val(data.pok3);
                    $('#april').val(data.pok4);
                    $('#mei').val(data.pok5);
                    $('#juni').val(data.pok6);
                    $('#juli').val(data.pok7);
                    $('#agustus').val(data.pok8);
                    $('#september').val(data.pok9);
                    $('#oktober').val(data.pok10);
                    $('#november').val(data.pok11);
                    $('#desember').val(data.pok12);
                })
            });



            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $('#formrencanakegiatanbagian').trigger("reset");
                $('#ajaxModel').modal('hide');
                $('#saveBtn').html('Tutup');
            });
        });

    </script>
@endsection
