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
    <!-- /.content -->
    <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script type="text/javascript">
        function dapatkanidbulan(){
            let idbulan = document.getElementById('idbulan').value;
            if(idbulan === ""){
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

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabelrealisasi tfoot th').each( function (i) {
                var title = $('#tabelrealisasi thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });

            idbulan = dapatkanidbulan();
            var table = $('.tabelrealisasi').DataTable({
                destroy: true,
                fixedColumn:true,
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

            $('#idbulan').on('change',function (){
                let idbulan = dapatkanidbulan();
                /*
                $.get("{{ route('getdatarealisasi','') }}" +'/' + idbulan, function (data) {
                })
                */
                var table = $('#tabelrealisasi').DataTable({
                    destroy: true,
                    fixedColumn:true,
                    scrollX:"100%",
                    autoWidth:true,
                    processing: true,
                    serverSide: true,
                    dom: 'Bfrtip',
                    buttons: ['copy','excel','pdf','csv','print'],
                    ajax:"{{route('getdatarealisasiindikatorro','')}}"+"/"+idbulan,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'uraianro', name: 'uraianro'},
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

                $.ajax({
                    type: "GET",
                    url: "{{ route('cekjadwallaporindikatorro',['',''])}}"+"/"+idindikatorro+"/"+nilaibulan,
                    success: function (data) {
                        if (data.status == "Buka"){
                            $.ajax({
                                url: "{{url('rekaprealisasiindikatorro')}}",
                                type: "POST",
                                data: {
                                    idindikatorro: idindikatorro,
                                    nilaibulan: nilaibulan,
                                    _token: '{{csrf_token()}}'
                                },
                                dataType: 'json',
                                success: function (data) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: data.status,
                                        icon: 'success'
                                    })
                                    $('#tabelrealisasi').DataTable().ajax.reload();
                                }
                            });
                        }else{
                            Swal.fire({
                                title: 'Error!',
                                text: "Status: "+data.status+" Karena: "+data.kondisi,
                                icon: 'error'
                            })
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
        });
    </script>

@endsection
