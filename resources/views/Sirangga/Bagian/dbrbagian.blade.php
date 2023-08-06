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
                <div class="row">
                </div>
                <div class="row">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{$judul}}</h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeldbr" class="table table-bordered table-striped tabeldbr">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Penanggungjawab</th>
                                <th>Gedung</th>
                                <th>ID Ruangan</th>
                                <th>Ruangan</th>
                                <th>Status DBR</th>
                                <th>Editor</th>
                                <th>Last Edit</th>
                                <th>Versi</th>
                                <th>Dokumen DBR</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Penanggungjawab</th>
                                <th>Gedung</th>
                                <th>ID Ruangan</th>
                                <th>Ruangan</th>
                                <th>Status DBR</th>
                                <th>Editor</th>
                                <th>Last Edit</th>
                                <th>Versi</th>
                                <th>Dokumen DBR</th>
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
    <script type="text/javascript">
        $(function () {
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            // Setup - add a text input to each footer cell
            $('#tabeldbr tfoot th').each( function (i) {
                var title = $('#tabeldbr thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' ).css(
                    {"width":"5%"},
                );
            });
            var table = $('.tabeldbr').DataTable({
                scrollY: true,
                scrollX:true,
                autoWidth:true,
                paging:true,
                deferRender: true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['copy','excel','csv','print'],
                ajax: "{{ route('getdatadbrbagian') }}",
                columns: [
                    {data: 'iddbr', name: 'iddbr'},
                    {data: 'idpenanggungjawab', name:'idpenanggungjawab'},
                    {data: 'idgedung', name: 'idgedung'},
                    {data: 'idruangan', name: 'idruangan'},
                    {data: 'uraianruangan', name: 'uraianruangan'},
                    {data: 'statusdbr', name: 'statusdbr'},
                    {data: 'useredit', name: 'useredit'},
                    {data: 'terakhiredit', name: 'terakhiredit'},
                    {data: 'versike', name: 'versike'},
                    {data: 'dokumendbr', name: 'dokumendbr'},
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

            $('body').on('click', '.laporpenambahan', function () {
                var iddbr = $(this).data('id');
                $('#modelHeading').html("Lapor Penambahan");
                $('#saveBtn').val("tambah");
                $('#ajaxModel').modal('show');
            });


            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');
                let form = document.getElementById('formlaporperubahanfinal');
                let fd = new FormData(form);
                let saveBtn = document.getElementById('saveBtn').value;
                let iddbr = document.getElementById('iddbr').value;
                fd.append('saveBtn',saveBtn)
                if(saveBtn === "edit"){
                    fd.append('_method','PUT')
                }
                for (var pair of fd.entries()) {
                    console.log(pair[0]+ ', ' + pair[1]);
                }

                $.ajax({
                    data: fd,
                    url: saveBtn === "tambah" ? "{{route('laporperubahanfinal')}}":"{{route('updateperubahanfinal','')}}"+"/"+iddbr,
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
                        $('#formruangan').trigger("reset");
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
            $('body').on('click', '.setujuidbr', function () {
                var iddbr = $(this).data("id");
                if(confirm("Apakah Anda Yakin AKan Menyetujui Data Ini? " +
                    "<br>"+
                    "Dengan Persetujuan Ini, Anda Telah Memastikan Bahwa Semua BMN" +
                    " Yang Tercantum Telah Anda Konfirmasi dan Benar Benar ada Secara Fisik, dan Siap Anda Pertanggungjawabkan")){
                    $.ajax({
                        url: "{{ url('/setujuidbr') }}"+'/'+iddbr,
                        success: function (data) {
                            if (data.status == "berhasil"){
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Persetujuan DBR Berhasil Dikirim Ke BMN',
                                    icon: 'success'
                                })
                            }else if (data.status == "konfirmbarang"){
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Persetujuan DBR Gagal, Ada Barang Belum Dikonfirmasi',
                                    icon: 'error'
                                })
                            }
                            else{
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Persetujuan DBR Gagal',
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
                        },
                    });
                }
            });


        });

    </script>
@endsection
