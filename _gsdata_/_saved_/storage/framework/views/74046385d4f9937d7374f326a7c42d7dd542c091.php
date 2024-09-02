<?php $__env->startSection('content'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <?php if(session('status')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('status')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><?php echo e($judul); ?></li>
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
                        <div class="btn-group float-sm-right">
                            <a class="btn btn-success float-sm-right" href="javascript:void(0)" id="tambahdata">Tambah Data</a>
                            <a class="btn btn-primary float-sm-right" href="javascript:void(0)" id="importdatakontraktual">Import Kontrak Header</a>
                        </div>
                        <h3 class="card-title"><?php echo e($judul); ?></h3>
                    </div>
                    <div class="card-body">
                        <table id="tabeldetildata" class="table table-bordered table-striped tabeldetildata">
                            <thead>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>No Kontrak</th>
                                <th>Jenis Belanja</th>
                                <th>Nilai Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Penyelesaian</th>
                                <th>Jumlah Hari</th>
                                <th>Status</th>
                                <th>Nilai Ketepatan Waktu</th>
                                <th>Nilai Kontrak Dini</th>
                                <th>Nilai Akselerasi 51</th>
                                <th>Akum Nilai Ketepatan Waktu</th>
                                <th>Akum Nilai Kontrak Dini</th>
                                <th>Akum Nilai Akselerasi 53</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Satker</th>
                                <th>Biro</th>
                                <th>Bagian</th>
                                <th>Periode</th>
                                <th>No Kontrak</th>
                                <th>Jenis Belanja</th>
                                <th>Nilai Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Penyelesaian</th>
                                <th>Jumlah Hari</th>
                                <th>Status</th>
                                <th>Nilai Ketepatan Waktu</th>
                                <th>Nilai Kontrak Dini</th>
                                <th>Nilai Akselerasi 53</th>
                                <th>Akum Nilai Ketepatan Waktu</th>
                                <th>Akum Nilai Kontrak Dini</th>
                                <th>Akum Nilai Akselerasi 53</th>
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
                                        <form action="<?php echo e(route('importdetilkontraktual')); ?>" method="POST" id="formuploaddetilpenyelesaiantagihan" name="formuploaddetilpenyelesaiantagihan" class="form-horizontal" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="input-group">
                                                <label for="file" class="col-sm-6 control-label">Upload File Detail</label>
                                                <div class="col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" accept=".xls,.xlsx" class="custom-file-input" id="filedetail" name="filedetail">
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                    </div>
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
    <script src="<?php echo e(env('APP_URL')."/".asset('AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js')); ?>"></script>
    <script type="text/javascript">
        $(function () {
            bsCustomFileInput.init();

            // Setup - add a text input to each header cell
            $('#tabeldetildata thead th').each( function (i) {
                var title = $('#tabeldetildata thead th').eq( $(this).index() ).text();
                $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
            });
            var table = $('.tabeldetildata').DataTable({
                fixedColumn:true,
                scrollX:"100%",
                autoWidth:true,
                processing: true,
                serverSide: true,
                dom: 'lf<"floatright"B>rtip',
                buttons: ['copy','excel','pdf','csv','print'],
                ajax:"<?php echo e(route('getdetilkontraktual')); ?>",
                columns: [
                    {data: 'kodesatker', name: 'kodesatker'},
                    {data: 'biro', name: 'birorelation.uraianbiro'},
                    {data: 'bagian', name: 'bagianrelation.uraianbagian'},
                    {data: 'periode', name: 'periode'},
                    {data: 'no_kontrak', name: 'no_kontrak'},
                    {data: 'jenisbelanja', name: 'jenisbelanja'},
                    {data: 'nilai_kontrak', name: 'nilai_kontrak'},
                    {data: 'tanggal_kontrak', name: 'tanggal_kontrak'},
                    {data: 'tanggal_masuk', name: 'tanggal_masuk'},
                    {data: 'tanggal_penyelesaian', name: 'tanggal_penyelesaian'},
                    {data: 'jumlah_hari', name: 'jumlah_hari'},
                    {data: 'status', name: 'status'},
                    {data: 'nilai_ketepatan_waktu', name: 'nilai_ketepatan_waktu'},
                    {data: 'nilai_kontrak_dini', name: 'nilai_kontrak_dini'},
                    {data: 'nilai_akselerasi_53', name: 'nilai_akselerasi_53'},
                    {data: 'akum_nilai_ketepatan_waktu', name: 'akum_nilai_ketepatan_waktu'},
                    {data: 'akum_nilai_kontrak_dini', name: 'akum_nilai_kontrak_dini'},
                    {data: 'akum_nilai_akselerasi_53', name: 'akum_nilai_akselerasi_53'},
                ],
                columnDefs: [
                    {
                        targets: 6,
                        render: $.fn.dataTable.render.number('.', ',', 0, '')
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

            $('#tambahdata').click(function () {
                $('#saveBtn').val("tambah");
                $('#formuploaddetilpenyelesaiantagihan').trigger("reset");
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
            });

            $('#importdatakontraktual').click(function (e) {
                if( confirm("Apakah Anda Yakin Mau Import Kontrak Header ?")){
                    e.preventDefault();
                    $(this).html('Importing..');
                    window.location="<?php echo e(URL::to('importkontrakheaderjob')); ?>";
                }
            });

        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\digitall\resources\views/IKPA/Admin/detilikpakontraktual.blade.php ENDPATH**/ ?>