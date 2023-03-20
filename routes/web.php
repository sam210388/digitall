<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrasi\KewenanganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Administrasi\MenuController;
use App\Http\Controllers\Administrasi\SubMenuController;
use App\Http\Controllers\Administrasi\KewenanganMenuController;
use App\Http\Controllers\Administrasi\KewenanganUserController;
use App\Http\Controllers\Administrasi\AdministrasiUserController;
use App\Http\Controllers\ReferensiUnit\DeputiController;
use App\Http\Controllers\ReferensiUnit\BiroController;
use App\Http\Controllers\ReferensiUnit\BagianController;
Use App\Http\Controllers\Administrasi\UserBiroBagianController;
use App\Http\Controllers\BPK\Admin\RekomendasiController;
use App\Http\Controllers\BPK\Bagian\RekomendasiBagianController;
use App\Http\Controllers\BPK\Bagian\TindakLanjutBagianController;
use App\Http\Controllers\BPK\Admin\TindakLanjutAdminController;
use App\Http\Controllers\BPK\Admin\TemuanController;
use App\Http\Controllers\ReferensiAnggaran\ProgramController;
use App\Http\Controllers\ReferensiAnggaran\KegiatanController;
use App\Http\Controllers\ReferensiAnggaran\OutputController;
use App\Http\Controllers\ReferensiAnggaran\SubOutputController;
use App\Http\Controllers\ReferensiAnggaran\KomponenController;
use App\Http\Controllers\AdminAnggaran\RefstatusController;
use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\Administrasi\PegawaiController;
use App\Http\Controllers\Caput\Admin\KroController;
use App\Http\Controllers\Caput\Admin\RoController;
use App\Http\Controllers\AdminAnggaran\AnggaranBagianController;
use App\Http\Controllers\Caput\Admin\IndikatorRoController;
use App\Http\Controllers\ReferensiAnggaran\SubKomponenController;
use App\Http\Controllers\Caput\Admin\RincianIndikatorRoController;
use App\Http\Controllers\Caput\Bagian\RealisasiRincianIndikatorROConctroller;
use App\Http\Controllers\Caput\Admin\JadwalTutupController;
use App\Http\Controllers\Caput\Biro\RealisasiIndikatorROConctroller;
use App\Http\Controllers\Caput\Biro\RealisasiROConctroller;
use App\Http\Controllers\Caput\Biro\RealisasiKROConctroller;
use App\Http\Controllers\Caput\Biro\MonitoringRincianIndikatorROConctroller;
use App\Http\Controllers\Realisasi\Admin\RealisasiSemarController;
use App\Http\Controllers\Realisasi\Admin\SppHeaderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect("/login");
});

Route::match(["GET", "POST"], "register", function(){
    return redirect("/login");
})->name("register");



//ADMINISTRASI APLIKASI
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('kewenangan',KewenanganController::class);
Route::any('/tampillistmenu',[MenuController::class,'tampillistmenu'])->name('tampillistmenu');
Route::resource('menu',MenuController::class);
Route::resource('submenu',SubMenuController::class);
Route::resource('kewenanganmenu',KewenanganMenuController::class);
Route::resource('kewenanganuser',KewenanganUserController::class);
Route::any('/editpassword/{id}',[AdministrasiUserController::class,'editpassword']);
Route::resource('kelolauser',AdministrasiUserController::class);
//IMPORT USER DARI SIAP
Route::get('importsiap',[PegawaiController::class,'importsiap'])->name('importsiap')->middleware('cekadmin');
Route::get('getlistpegawai',[PegawaiController::class,'getlistpegawai'])->name('getlistpegawai')->middleware('cekadmin');
Route::get('pegawai',[PegawaiController::class,'pegawai'])->name('pegawai')->middleware('cekadmin');
Route::post('ambildatapegawai',[PegawaiController::class,'ambildatapegawai'])->name('ambildatapegawai')->middleware('cekadmin');



//REFERENSI UNIT KERJA
Route::resource('deputi',DeputiController::class);
Route::resource('biro',BiroController::class);
Route::post('/ambildatabiro',[BagianController::class,'dapatkandatabiro'])->name('ambildatabiro');
Route::post('/ambildatabagian',[BagianController::class,'dapatkandatabagian'])->name('ambildatabagian');
Route::resource('bagian',BagianController::class)->middleware(['auth']);
Route::resource('updateunitkerja',UserBiroBagianController::class);
Route::get('importunit',[BagianController::class,'importunit'])->middleware('cekadmin');

//ADMIN BPK
Route::resource('temuan',TemuanController::class)->middleware(['auth','cekadminbpk']);
Route::get('tampilrekomendasi/{idtemuan}',[RekomendasiController::class,'tampilrekomendasi'])->name('tampilrekomendasi')->middleware(['auth','cekadminbpk']);
Route::post('getdatarekomendasi}',[RekomendasiController::class,'getDataRekomendasi'])->name('getdatarekomendasi')->middleware(['auth','cekadminbpk']);
Route::get('/kirimrekomendasikeunit/{id}',[RekomendasiController::class,'kirimrekomendasikeunit'])->name('kirimrekomendasikeunit')->middleware(['auth','cekadminbpk']);
Route::resource('rekomendasi',RekomendasiController::class)->middleware(['auth','cekadminbpk']);
Route::get('/statusrekomendasiselesai/{id}',[RekomendasiController::class,'statusrekomendasiselesai'])->name('statusrekomendasiselesai')->middleware(['auth','cekadminbpk']);
Route::get('/statusrekomendasitddl/{id}',[RekomendasiController::class,'statustemuantddl'])->name('statustemuantddl')->middleware(['auth','cekadminbpk']);
Route::get('lihattindaklanjutbagian/{idrekomendasi}',[TindakLanjutAdminController::class,'tampiltindaklanjut'])->name('lihattindaklanjutbagian')->middleware(['cekadminbpk']);
Route::get('/ajukankebpk/{idtindaklanjut}',[TindakLanjutAdminController::class,'ajukankebpk'])->name('ajukankebpk')->middleware(['cekadminbpk']);
Route::get('/tindaklanjutselesai/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjutselesai'])->name('tindaklanjutselesai')->middleware(['cekadminbpk']);
Route::post('getdatatindaklanjutbagian', [TindakLanjutAdminController::class,'getdatatindaklanjutbagian'])->name('getdatatindaklanjutbagian');
Route::post('simpanpenjelasan', [TindakLanjutAdminController::class,'simpanpenjelasan'])->name('simpanpenjelasan')->middleware('cekadminbpk');
Route::get('/tindaklanjuttddl/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjuttddl'])->name('tindaklanjuttddl')->middleware(['cekadminbpk']);
Route::get('/lihattanggapan/{idtindaklanjut}',[TindakLanjutAdminController::class,'lihattanggapan'])->name('lihattanggapan')->middleware(['cekadminbpk']);
Route::get('getdetailtemuan/{idtemuan}',[TemuanController::class,'getdetailtemuan'])->name('getdetailtemuan')->middleware(['auth','cekadminbpk']);
//menyiapkan metode untuk menyimpan history tindaklanjut yang sudah dibuat sebelumnya
Route::post('simpantinjuthistory',[TindakLanjutAdminController::class,'simpantinjuthistory'])->name('simpantinjuthistory')->middleware(['cekadminbpk']);
Route::put('updatetinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'updatetinjuthistory'])->name('updatetinjuthistory')->middleware(['cekadminbpk']);
Route::get('edittinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'edittinjuthistory'])->name('edittinjuthistory')->middleware(['cekadminbpk']);
Route::delete('destroytinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'destroytinjuthistory'])->name('destroytinjuthistory')->middleware(['cekadminbpk']);

//BAGIAN
//BPK
Route::resource('rekomendasibpkbagian',RekomendasiBagianController::class)->middleware('cekoperatorbagian');
Route::get('tindaklanjutbagian/{idrekomendasi}',[TindakLanjutBagianController::class,'tampiltindaklanjut'])->name('tindaklanjutbagian')->middleware(['cekpemilikrekomendasi']);
Route::post('getdatatindaklanjut', [TindakLanjutBagianController::class,'getdatatindaklanjut'])->name('getdatatindaklanjut')->middleware(['cekpemilikrekomendasi']);
Route::resource('kelolatindaklanjut',TindakLanjutBagianController::class)->middleware(['cekoperatorbagian','cekpemilikrekomendasi']);
Route::get('/ajukankeirtama/{idtindaklanjut}',[TindakLanjutBagianController::class,'ajukankeirtama'])->name('ajukankeirtama')->middleware(['cekpemilikrekomendasi']);
Route::post('simpantanggapan', [TindakLanjutBagianController::class,'simpantanggapan'])->name('simpanpenjelasan')->middleware('cekpemilikrekomendasi');
Route::get('getdetiltemuan/{idrekomendasi}',[RekomendasiBagianController::class,'getdetiltemuan'])->name('getdetiltemuan')->middleware(['cekpemilikrekomendasi']);

//referensi anggaran
//program
Route::get('program',[ProgramController::class,'program'])->name('program')->middleware('cekadminanggaran');
Route::get('getlistprogram',[ProgramController::class,'getListProgram'])->name('getlistprogram')->middleware('cekadminanggaran');
Route::get('importprogram',[ProgramController::class,'importprogram'])->name('importprogram')->middleware('cekadminanggaran');

//Kegiatan
Route::get('kegiatan',[KegiatanController::class,'kegiatan'])->name('kegiatan')->middleware('cekadminanggaran');
Route::get('getlistkegiatan',[KegiatanController::class,'getListKegiatan'])->name('getlistkegiatan')->middleware('cekadminanggaran');
Route::get('importkegiatan',[KegiatanController::class,'importkegiatan'])->name('importkegiatan')->middleware('cekadminanggaran');

//output
Route::get('output',[OutputController::class,'output'])->name('output')->middleware('cekadminanggaran');
Route::get('getlistoutput',[OutputController::class,'getListOutput'])->name('getlistoutput')->middleware('cekadminanggaran');
Route::get('importoutput',[OutputController::class,'importoutput'])->name('importoutput')->middleware('cekadminanggaran');
Route::post('ambildataoutput',[OutputController::class,'dapatkandataoutput'])->name('ambildataoutput')->middleware('auth');

//suboutput
Route::get('suboutput',[SubOutputController::class,'suboutput'])->name('suboutput')->middleware('cekadminanggaran');
Route::get('getlistsuboutput',[SubOutputController::class,'getListSubOutput'])->name('getlistsuboutput')->middleware('cekadminanggaran');
Route::get('importsuboutput',[SubOutputController::class,'importsuboutput'])->name('importsuboutput')->middleware('cekadminanggaran');
Route::post('ambildatasuboutput',[SubOutputController::class,'ambildatasuboutput'])->name('ambildatasuboutput')->middleware('auth');

//komponen
Route::get('komponen',[KomponenController::class,'komponen'])->name('komponen')->middleware('cekadminanggaran');
Route::get('getlistkomponen',[KomponenController::class,'getListKomponen'])->name('getlistkomponen')->middleware('cekadminanggaran');
Route::get('importkomponen',[KomponenController::class,'importkomponen'])->name('importkomponen')->middleware('cekadminanggaran');
Route::post('ambildatakomponen',[KomponenController::class,'ambildatakomponen'])->name('ambildatakomponen')->middleware('auth');


//subkomponen
Route::get('subkomponen',[SubKomponenController::class,'subkomponen'])->name('subkomponen')->middleware('cekadminanggaran');
Route::get('getlistsubkomponen',[SubKomponenController::class,'getListSubKomponen'])->name('getlistsubkomponen')->middleware('cekadminanggaran');
Route::get('importsubkomponen',[SubKomponenController::class,'importsubkomponen'])->name('importsubkomponen')->middleware('cekadminanggaran');
Route::post('ambildatasubkomponen',[SubKomponenController::class,'ambildatasubkomponen'])->name('ambildatasubkomponen')->middleware('auth');


//admin anggaran
//refstatus
Route::get('refstatus',[RefstatusController::class,'refstatus'])->name('refstatus')->middleware('cekadminanggaran');
Route::get('getlistrefstatus',[RefstatusController::class,'getListRefstatus'])->name('getlistrefstatus')->middleware('cekadminanggaran');
Route::get('importrefstatus',[RefstatusController::class,'importrefstatus'])->name('importrefstatus')->middleware('cekadminanggaran');
//data anggaran
Route::get('importanggaran/{kdsatker}/{kdstshistory}',[DataAngController::class,'importdataang'])->name('importanggaran')->middleware('cekadminanggaran');
Route::post('checkdataang',[DataAngController::class,'checkdata'])->name('checkdataang')->middleware('cekadminanggaran');
Route::get('rekapanggaran/{idrefstatus}',[DataAngController::class,'rekapanggaran'])->name('rekapanggaran')->middleware('cekadminanggaran');
Route::post('checkrekapanggaran',[DataAngController::class,'checkrekapanggaran'])->name('checkrekapanggaran');
//anggaranbagian
Route::resource('anggaranbagian',AnggaranBagianController::class)->middleware('cekadminanggaran');


//ADMIN CAPUT
//kro
Route::resource('kro',KroController::class)->middleware('cekadmincaput');
Route::get('importkro',[KroController::class,'importkro'])->name('importkro')->middleware('cekadmincaput');
//RO
Route::resource('ro',RoController::class)->middleware('cekadmincaput');
Route::get('importro',[RoController::class,'importro'])->name('importro')->middleware('cekadmincaput');

//INDIKATORRO
Route::resource('indikatorro',IndikatorRoController::class)->middleware('cekadmincaput');
Route::get('importindikatorro',[IndikatorRoController::class,'importindikatorro'])->name('importindikatorro')->middleware('cekadmincaput');
//RINCIAN INDIKATOR RO
Route::resource('rincianindikatorro',RincianIndikatorRoController::class)->middleware('cekadmincaput');
//REALISASI RINCIAN INDIKATOR RO
Route::get('realisasirincianindikatorro',[RealisasiRincianIndikatorROConctroller::class,'realisasirincianindikatorro'])->name('realisasirincianindikatorro')->middleware('cekoperatorbagian');
Route::get('getdatarealisasi/{idbulan}',[RealisasiRincianIndikatorROConctroller::class,'getdatarealisasi'])->name('getdatarealisasi')->middleware('cekoperatorbagian');
Route::post('getdatarincianindikatorro',[RealisasiRincianIndikatorROConctroller::class,'getdatarincianindikatorro'])->name('getdatarincianindikatorro')->middleware('cekoperatorbagian');
Route::post('simpanrealisasirincian',[RealisasiRincianIndikatorROConctroller::class,'simpanrealisasirincian'])->name('simpanrealisasirincian')->middleware('cekoperatorbagian');
Route::post('updaterealisasirincian/{idrealisasi}',[RealisasiRincianIndikatorROConctroller::class,'updaterealisasirincian'])->name('updaterealisasirincian')->middleware('cekoperatorbagian');
Route::post('editrealisasirincian',[RealisasiRincianIndikatorROConctroller::class,'editrealisasirincian'])->name('editrealisasirincian')->middleware('cekoperatorbagian');
Route::delete('deleterealisasirincian/{idrealisasi}',[RealisasiRincianIndikatorROConctroller::class,'deleterealisasi'])->name('deleterealisasirincian')->middleware('cekoperatorbagian');
Route::get('cekjadwallapor/{idrincianindikatorro}/{idbulan}',[RealisasiRincianIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallapor')->middleware('cekoperatorbagian');

//JADWALTUTUP
Route::resource('jadwaltutup',JadwalTutupController::class)->middleware('cekadmincaput');

//realisasi indikator ro
Route::get('realisasiindikatorro',[RealisasiIndikatorROConctroller::class,'realisasiindikatorro'])->name('realisasiindikatorro')->middleware('cekoperatorbiro');
Route::get('getdatarealisasiindikatorro/{idbulan}',[RealisasiIndikatorROConctroller::class,'getdatarealisasiindikatorro'])->name('getdatarealisasiindikatorro')->middleware('cekoperatorbiro');
Route::get('cekjadwallaporindikatorro/{idindikatorro}/{nilaibulan}',[RealisasiIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallaporindikatorro')->middleware('cekoperatorbiro');
Route::post('rekaprealisasiindikatorro',[RealisasiIndikatorROConctroller::class,'rekaprealisasiindikatorro'])->name('rekaprealisasiindikatorro')->middleware('cekoperatorbiro');

//realisasi ro
Route::get('realisasiro',[RealisasiROConctroller::class,'realisasiro'])->name('realisasiro')->middleware('cekoperatorbiro');
Route::get('getdatarealisasiro/{idbulan}',[RealisasiROConctroller::class,'getdatarealisasiro'])->name('getdatarealisasiro')->middleware('cekoperatorbiro');
Route::get('cekjadwallaporro/{idro}/{nilaibulan}',[RealisasiROConctroller::class,'cekjadwallapor'])->name('cekjadwallaporro')->middleware('cekoperatorbiro');
Route::post('rekaprealisasiro',[RealisasiROConctroller::class,'rekaprealisasiro'])->name('rekaprealisasiro')->middleware('cekoperatorbiro');

//realisasi kro
Route::get('realisasikro',[RealisasiKROConctroller::class,'realisasikro'])->name('realisasikro')->middleware('cekoperatorbiro');
Route::get('getdatarealisasikro/{idbulan}',[RealisasiKROConctroller::class,'getdatarealisasikro'])->name('getdatarealisasikro')->middleware('cekoperatorbiro');
Route::get('cekjadwallaporkro/{idro}/{nilaibulan}',[RealisasiKROConctroller::class,'cekjadwallapor'])->name('cekjadwallaporkro')->middleware('cekoperatorbiro');
Route::post('rekaprealisasikro',[RealisasiKROConctroller::class,'rekaprealisasikro'])->name('rekaprealisasikro')->middleware('cekoperatorbiro');

//monitoring operator biro
Route::get('monitoringrincianindikatorro',[MonitoringRincianIndikatorROConctroller::class,'realisasirincianindikatorro'])->name('monitoringrincianindikatorro')->middleware('cekoperatorbiro');
Route::get('getdatarealisasimonitoring/{idbulan}',[MonitoringRincianIndikatorROConctroller::class,'getdatarealisasi'])->name('getdatarealisasimonitoring')->middleware('cekoperatorbiro');
Route::get('cekjadwallapormonitoring/{idrincianindikatorro}/{idbulan}',[MonitoringRincianIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallapormonitoring')->middleware('cekoperatorbiro');
Route::post('batalvalidasi',[MonitoringRincianIndikatorROConctroller::class,'batalvalidasirincianindikator'])->name('batalvalidasi')->middleware('cekoperatorbiro');

//ADMIN REALISASI
//REALISASI SEMAR
Route::get('realisasisemar',[RealisasiSemarController::class,'realisasisemar'])->name('realisasisemar')->middleware('auth');
Route::get('importrealisasisemar',[RealisasiSemarController::class,'importrealisasisemar'])->name('importrealisasisemar');

//REALISASI SP2D
Route::get('sppheader',[SppHeaderController::class,'sppheader'])->name('sppheader')->middleware('auth');
Route::get('importsppheader',[SppHeaderController::class,'importsppheader'])->name('importsppheader')->middleware('auth');





