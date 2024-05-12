<?php


use App\Http\Controllers\IKPA\Admin\IKPADeviasiController;
use App\Http\Controllers\IKPA\Admin\IKPAPenyelesaianTagihanController;
use App\Http\Controllers\IKPA\Admin\IKPAPenyerapanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


use App\Http\Controllers\Administrasi\MenuController;
use App\Http\Controllers\Administrasi\SubMenuController;
use App\Http\Controllers\Administrasi\KewenanganMenuController;
use App\Http\Controllers\Administrasi\KewenanganUserController;
use App\Http\Controllers\Administrasi\AdministrasiUserController;
use App\Http\Controllers\Administrasi\TokenApiController;
Use App\Http\Controllers\Administrasi\UserBiroBagianController;
use App\Http\Controllers\Administrasi\KewenanganController;
use App\Http\Controllers\Administrasi\PegawaiController;
use App\Http\Controllers\Administrasi\PPKSatkerController;
use App\Http\Controllers\Administrasi\KewenanganPPKController;
use App\Http\Controllers\Administrasi\PenetapanPPKController;
use App\Http\Controllers\Administrasi\PenetapanBendaharaController;
use App\Http\Controllers\Administrasi\PenetapanKasirController;

use App\Http\Controllers\ReferensiUnit\DeputiController;
use App\Http\Controllers\ReferensiUnit\BiroController;
use App\Http\Controllers\ReferensiUnit\BagianController;

use App\Http\Controllers\BPK\Admin\RekomendasiController;
use App\Http\Controllers\BPK\Admin\TindakLanjutAdminController;
use App\Http\Controllers\BPK\Admin\TemuanController;
use App\Http\Controllers\BPK\Admin\IndikatorRekomendasiController;

use App\Http\Controllers\BPK\Bagian\IndikatorRekomendasiBagianController;
use App\Http\Controllers\BPK\Bagian\TindakLanjutBagianController;

use App\Http\Controllers\ReferensiAnggaran\ProgramController;
use App\Http\Controllers\ReferensiAnggaran\KegiatanController;
use App\Http\Controllers\ReferensiAnggaran\OutputController;
use App\Http\Controllers\ReferensiAnggaran\SubOutputController;
use App\Http\Controllers\ReferensiAnggaran\KomponenController;
use App\Http\Controllers\ReferensiAnggaran\SubKomponenController;

use App\Http\Controllers\AdminAnggaran\RefstatusController;
use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\AdminAnggaran\AnggaranBagianController;

use App\Http\Controllers\Caput\Admin\KroController;
use App\Http\Controllers\Caput\Admin\RoController;
use App\Http\Controllers\Caput\Admin\IndikatorRoController;
use App\Http\Controllers\Caput\Admin\RincianIndikatorRoController;
use App\Http\Controllers\Caput\Admin\JadwalTutupController;
use App\Http\Controllers\Caput\Admin\RealisasiIndikatorROConctrollerAdmin;
use App\Http\Controllers\Caput\Admin\MonitoringRincianIndikatorROAdminConctroller;
use App\Http\Controllers\Caput\Admin\MonitoringNormalisasiDataRincian;
use App\Http\Controllers\Caput\Admin\MonitoringRealisasiROConctroller;
use App\Http\Controllers\Caput\Admin\RoSaktiController;

use App\Http\Controllers\Caput\Bagian\RealisasiRincianIndikatorROConctroller;
use App\Http\Controllers\Caput\Bagian\RealisasiIndikatorROBagianConctroller;

use App\Http\Controllers\Caput\Biro\RealisasiRincianIndikatorROBiroConctroller;
use App\Http\Controllers\Caput\Biro\RealisasiIndikatorROConctroller;
use App\Http\Controllers\Caput\Biro\RealisasiROConctroller;
use App\Http\Controllers\Caput\Biro\RealisasiKROConctroller;
use App\Http\Controllers\Caput\Biro\MonitoringRincianIndikatorROConctroller;

use App\Http\Controllers\Sirangga\Admin\AreaController;
use App\Http\Controllers\Sirangga\Admin\SubAreaController;
use App\Http\Controllers\Sirangga\Admin\GedungController;
use App\Http\Controllers\Sirangga\Admin\LantaiController;
use App\Http\Controllers\Sirangga\Admin\RuanganController;
use App\Http\Controllers\Sirangga\Admin\DBRController;
use App\Http\Controllers\Sirangga\Admin\ListImportSaktiController;
use App\Http\Controllers\Sirangga\Admin\BarangController;
use App\Http\Controllers\Sirangga\Admin\ImportSaktiController;
use App\Http\Controllers\Sirangga\Bagian\DBRBagianController;
use App\Http\Controllers\Sirangga\Admin\TestDBRController;
use App\Http\Controllers\Sirangga\Admin\MonitoringPenghapusanBarangController;
use App\Http\Controllers\Sirangga\Admin\DetilDBRController;
use App\Http\Controllers\Sirangga\Admin\KonfirmBarangController;
use App\Http\Controllers\Sirangga\Admin\DetilDBRTidakNormalController;
use App\Http\Controllers\Pemanfaatan\PenyewaController;
use App\Http\Controllers\Pemanfaatan\Penyewa\ReferensiPenyewaController;
use App\Http\Controllers\Pemanfaatan\PenanggungjawabSewaController;

use App\Http\Controllers\Pemanfaatan\Penyewa\TransaksiPemanfaatanController;
use App\Http\Controllers\Pemanfaatan\Penyewa\ReferensiPenanggungjawabSewaController;
use App\Http\Controllers\Pemanfaatan\ObjekSewaController;
use App\Http\Controllers\Pemanfaatan\MonitoringPemanfaatanController;

use App\Http\Controllers\GL\BukuBesarController;
use App\Http\Controllers\GL\FaDetailController;

use App\Http\Controllers\Realisasi\Admin\BASTKontrakHeaderController;
use App\Http\Controllers\Realisasi\Admin\RealisasiSemarController;
use App\Http\Controllers\Realisasi\Admin\SppHeaderController;
use App\Http\Controllers\Realisasi\Admin\SppPotonganController;
use App\Http\Controllers\Realisasi\Admin\RealisasiPerBiroController;
use App\Http\Controllers\Realisasi\Admin\SppPengeluaranController;
use App\Http\Controllers\Realisasi\Admin\RealisasiPengenal;
use App\Http\Controllers\Realisasi\Biro\RealisasiPerBagianController;
use App\Http\Controllers\Realisasi\Bagian\RealisasiBagianPerPengenal;
use App\Http\Controllers\Realisasi\Bagian\DetilRealisasiBagian;
use App\Http\Controllers\Realisasi\Admin\DetilRealisasi;
use App\Http\Controllers\Realisasi\Biro\DetilRealisasiBiro;
use App\Http\Controllers\Realisasi\Admin\AdminRealisasiPerBagianController;
use App\Http\Controllers\Realisasi\Admin\RencanaRealisasiPengenal;
use App\Http\Controllers\Realisasi\Bagian\RencanaRealisasiBagian;
use App\Http\Controllers\Realisasi\Biro\RencanaRealisasiBiro;
use App\Http\Controllers\Realisasi\Bagian\KasbonController;
use App\Http\Controllers\Realisasi\PPK\PPKKasbonController;
use App\Http\Controllers\Realisasi\PPSPM\PPSPMKasbonController;
use App\Http\Controllers\Realisasi\Bendahara\BendaharaKasbonController;
use App\Http\Controllers\Realisasi\Kasir\KasirKasbonController;
use App\Http\Controllers\Realisasi\Admin\RealisasiSaktiController;
use App\Http\Controllers\Realisasi\Bagian\RencanaKegiatanIndukBagianController;

use App\Http\Controllers\Realisasi\Admin\RencanaKegiatanController;
use App\Http\Controllers\Realisasi\Biro\RencanaKegiatanBiroController;
use App\Http\Controllers\Realisasi\Admin\KontrakHeaderController;

use App\Http\Controllers\IKPA\Admin\DetilPenyelesaianController;
use App\Http\Controllers\IKPA\Admin\DetilIKPAKontraktualController;
use App\Http\Controllers\IKPA\Admin\IKPAKontraktualController;
use App\Http\Controllers\IKPA\Admin\IKPACaputController;
use App\Http\Controllers\IKPA\Admin\IKPACaputBiroController;
use App\Http\Controllers\IKPA\Admin\IKPARevisiController;
use App\Http\Controllers\IKPA\Admin\DetilIKPARevisiController;
use App\Http\Controllers\IKPA\Admin\RekapIKPABagianController;


use App\Http\Controllers\IKPA\Biro\IKPAPenyerapanAksesBiroController;
use App\Http\Controllers\IKPA\Biro\IKPAKontraktualAksesBiroController;
use App\Http\Controllers\IKPA\Biro\IKPADeviasiAksesBiroController;
use App\Http\Controllers\IKPA\Biro\IKPAPenyelesaianTagihanAksesBiroController;
use App\Http\Controllers\IKPA\Biro\RekapIKPABagianAksesBiroController;
use App\Http\Controllers\IKPA\Biro\RekapIKPABiroAksesBiroController;

use App\Http\Controllers\IKPA\Bagian\IKPAPenyerapanBagianController;
use App\Http\Controllers\IKPA\Bagian\IKPADeviasiBagianController;
use App\Http\Controllers\IKPA\Bagian\IKPAPenyelesaianTagihanBagianController;
use App\Http\Controllers\IKPA\Bagian\IKPAKontraktualBagianController;
use App\Http\Controllers\IKPA\Bagian\RekapIKPAAksesBagianController;







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
Route::group(['middleware' => ['cekadminadministrasi']], function() {
    Route::resource('kewenangan',KewenanganController::class);
    Route::resource('menu',MenuController::class);
    Route::resource('submenu',SubMenuController::class);
    Route::resource('kewenanganmenu',KewenanganMenuController::class);
    Route::resource('kewenanganuser',KewenanganUserController::class);
    Route::any('/editpassword/{id}',[AdministrasiUserController::class,'editpassword']);
    Route::resource('kelolauser',AdministrasiUserController::class);
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::any('/tampillistmenu',[MenuController::class,'tampillistmenu'])->name('tampillistmenu');
Route::resource('tokenapi', TokenApiController::class)->middleware('cekadmin');
Route::get('resettoken/{idtokenapi}',[TokenApiController::class,'resettoken'])->name('resettoken')->middleware('cekadmin');

//IMPORT USER DARI SIAP
Route::get('importsiap',[PegawaiController::class,'importsiap'])->name('importsiap')->middleware('cekadmin');
Route::get('getlistpegawai',[PegawaiController::class,'getlistpegawai'])->name('getlistpegawai')->middleware('cekadmin');
Route::get('pegawai',[PegawaiController::class,'pegawai'])->name('pegawai')->middleware('cekadmin');
Route::post('ambildatapegawai',[PegawaiController::class,'ambildatapegawai'])->name('ambildatapegawai')->middleware('cekadmin');



//REFERENSI UNIT KERJA
Route::resource('deputi',DeputiController::class)->middleware('cekadmin');
Route::resource('biro',BiroController::class)->middleware('cekadmin');
Route::post('/ambildatabiro',[BagianController::class,'dapatkandatabiro'])->name('ambildatabiro');
Route::post('/ambildatabagian',[BagianController::class,'dapatkandatabagian'])->name('ambildatabagian');
Route::resource('bagian',BagianController::class);
Route::resource('updateunitkerja',UserBiroBagianController::class);
Route::get('importunit',[BagianController::class,'importunit'])->middleware('cekadmin');

//ADMIN BPK -- TEMUAN
Route::resource('temuan',TemuanController::class)->middleware(['auth','cekadminbpk']);
Route::get('getdetailtemuan/{idtemuan}',[TemuanController::class,'getdetailtemuan'])->name('getdetailtemuan')->middleware(['auth','cekadminbpk']);



//ADMIN BPK REKOMENDASI
Route::resource('rekomendasi',RekomendasiController::class)->middleware('cekadminbpk');
Route::get('tampilrekomendasi/{idtemuan}',[RekomendasiController::class,'tampilrekomendasi'])->name('tampilrekomendasi')->middleware(['auth','cekadminbpk']);
Route::post('getdatarekomendasi}',[RekomendasiController::class,'getDataRekomendasi'])->name('getdatarekomendasi')->middleware(['auth','cekadminbpk']);

//ADMIN BPK INDIKATOR REKOMENDASI
Route::get('tampilindikatorrekomendasi/{idrekomendasi}',[IndikatorRekomendasiController::class,'tampilindikatorrekomendasi'])->name('tampilindikatorrekomendasi')->middleware('cekadminbpk');
Route::resource('indikatorrekomendasi',IndikatorRekomendasiController::class)->middleware('cekadminbpk');
Route::post('getdataindikatorrekomendasi}',[IndikatorRekomendasiController::class,'getdataindikatorrekomendasi'])->name('getdataindikatorrekomendasi')->middleware('cekadminbpk');

Route::get('/kirimindikatorrekomendasikeunit/{id}',[IndikatorRekomendasiController::class,'kirimindikatorrekomendasikeunit'])->name('kirimindikatorrekomendasikeunit')->middleware('cekadminbpk');
Route::get('/statusindikatorrekomendasiselesai/{id}',[IndikatorRekomendasiController::class,'statusrekomendasiselesai'])->name('statusrekomendasiselesai')->middleware(['auth','cekadminbpk']);
Route::get('/statusindikatorrekomendasitddl/{id}',[IndikatorRekomendasiController::class,'statustemuantddl'])->name('statustemuantddl')->middleware(['auth','cekadminbpk']);

//ADMIN BPK TINDAK LANJUT INDIKATOR REKOMENDASI
Route::get('lihattindaklanjutbagian/{idindikatorrekomendasi}',[TindakLanjutAdminController::class,'tampiltindaklanjut'])->name('lihattindaklanjutbagian')->middleware(['cekadminbpk']);
Route::get('/ajukankebpk/{idtindaklanjut}',[TindakLanjutAdminController::class,'ajukankebpk'])->name('ajukankebpk')->middleware(['cekadminbpk']);
Route::get('/tindaklanjutselesai/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjutselesai'])->name('tindaklanjutselesai')->middleware(['cekadminbpk']);
Route::post('getdatatindaklanjutbagian', [TindakLanjutAdminController::class,'getdatatindaklanjutbagian'])->name('getdatatindaklanjutbagian');
Route::post('simpanpenjelasan', [TindakLanjutAdminController::class,'simpanpenjelasan'])->name('simpanpenjelasan')->middleware('cekadminbpk');
Route::get('/tindaklanjuttddl/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjuttddl'])->name('tindaklanjuttddl')->middleware(['cekadminbpk']);
Route::get('/lihattanggapan/{idtindaklanjut}',[TindakLanjutAdminController::class,'lihattanggapan'])->name('lihattanggapan')->middleware(['cekadminbpk']);

//menyiapkan metode untuk menyimpan history tindaklanjut yang sudah dibuat sebelumnya
Route::post('simpantinjuthistory',[TindakLanjutAdminController::class,'simpantinjuthistory'])->name('simpantinjuthistory')->middleware(['cekadminbpk']);
Route::put('updatetinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'updatetinjuthistory'])->name('updatetinjuthistory')->middleware(['cekadminbpk']);
Route::get('edittinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'edittinjuthistory'])->name('edittinjuthistory')->middleware(['cekadminbpk']);
Route::delete('destroytinjuthistory/{idtindaklanjut}',[TindakLanjutAdminController::class,'destroytinjuthistory'])->name('destroytinjuthistory')->middleware(['cekadminbpk']);


//BAGIAN
//BPK
Route::resource('indikatorrekomendasibpkbagian',IndikatorRekomendasiBagianController::class)->middleware('cekoperatorbagian');
Route::get('tindaklanjutbagian/{idindikatorrekomendasi}',[TindakLanjutBagianController::class,'tampiltindaklanjut'])->name('tindaklanjutbagian')->middleware('cekpemilikrekomendasi');
Route::post('getdatatindaklanjut', [TindakLanjutBagianController::class,'getdatatindaklanjut'])->name('getdatatindaklanjut')->middleware('cekpemilikrekomendasi');
Route::resource('kelolatindaklanjut',TindakLanjutBagianController::class)->middleware('cekpemilikrekomendasi');
Route::get('/ajukankeirtama/{idtindaklanjut}',[TindakLanjutBagianController::class,'ajukankeirtama'])->name('ajukankeirtama')->middleware('cekpemilikrekomendasi');
Route::post('simpantanggapan', [TindakLanjutBagianController::class,'simpantanggapan'])->name('simpanpenjelasan')->middleware('cekpemilikrekomendasi');
Route::get('getdetiltemuan/{idindikatorrekomendasi}',[IndikatorRekomendasiBagianController::class,'getdetiltemuan'])->name('getdetiltemuan');

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
Route::get('updatestatusaktif',[RefstatusController::class,'updatestatusaktif'])->name('updatestatusaktif')->middleware('cekadminanggaran');
Route::get('exportanggaran/{idrefstatus}',[RefstatusController::class,'exportanggaran'])->name('exportanggaran')->middleware('cekadminanggaran');

//data anggaran
Route::get('importanggaran/{idrefstatus}',[DataAngController::class,'importdataang'])->name('importanggaran')->middleware('cekadminanggaran');
Route::get('rekondataang/{idrefstatus}',[DataAngController::class,'rekondataang'])->name('rekondataang')->middleware('cekadminanggaran');
Route::post('checkdataang',[DataAngController::class,'checkdata'])->name('checkdataang');
Route::get('rekapanggaran/{idrefstatus}',[DataAngController::class,'rekapanggaran'])->name('rekapanggaran')->middleware('cekadminanggaran');
Route::post('checkrekapanggaran',[DataAngController::class,'checkrekapanggaran'])->name('checkrekapanggaran');

//anggaranbagian
Route::resource('anggaranbagian',AnggaranBagianController::class)->middleware('cekadminanggaran');
Route::get('getdataanggaranbagian/{status}',[AnggaranBagianController::class,'getdataanggaranbagian'])->name('getdataanggaranbagian');

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

//MONITORING REALISASI INDIKATOR RO UNTUK BAPENAS
Route::get('realisasiindikatorroadmin',[RealisasiIndikatorROConctrollerAdmin::class,'realisasiindikatorro'])->name('realisasiindikatorroadmin')->middleware('cekadmincaput');
Route::get('getdatarealisasiindikatorroadmin/{idbulan}/{idbiro?}',[RealisasiIndikatorROConctrollerAdmin::class,'getdatarealisasiindikatorro'])->name('getdatarealisasiindikatorroadmin')->middleware('cekadmincaput');
Route::get('exportrealisasiindikatorro',[RealisasiIndikatorROConctrollerAdmin::class,'exportrealisasiindikatorro'])->name('exportrealisasiindikatorro')->middleware('cekadmincaput');
Route::get('exportrealisasianggaranindikatorro',[RealisasiIndikatorROConctrollerAdmin::class,'exportrealisasianggaranindikatorro'])->name('exportrealisasianggaranindikatorro')->middleware('cekadmincaput');
Route::get('exportrealisasiindikatorro',[RealisasiIndikatorROConctrollerAdmin::class,'exportrealisasiindikatorro'])->name('exportrealisasiindikatorro')->middleware('cekadmincaput');
Route::get('normalisasidataindikatoroutput/{idbulan}',[RealisasiIndikatorROConctrollerAdmin::class, 'normalisasidataindikatoroutput'])->name('normalisasidatarincian')->middleware('cekadmincaput');


//monitoring rincian indikator ro
Route::get('monitoringrincianindikatorroadmin',[MonitoringRincianIndikatorROAdminConctroller::class,'realisasirincianindikatorro'])->name('monitoringrincianindikatorroadmin')->middleware('cekadmincaput');
Route::get('getdatarealisasimonitoringadmin/{idbulan}/{idbiro?}/{idbagian?}',[MonitoringRincianIndikatorROAdminConctroller::class,'getdatarealisasi'])->name('getdatarealisasimonitoringadmin')->middleware('cekadmincaput');
Route::get('datanormalisasirincian',[MonitoringNormalisasiDataRincian::class,'datanormalisasirincianindikator'])->name('datanormalisasirincian')->middleware('cekadmincaput');
Route::get('getdatanormalisasi/{idbulan}/{idbiro?}/{idbagian?}',[MonitoringNormalisasiDataRincian::class,'getdatanormalisasi'])->name('getdatanormalisasi')->middleware('cekadmincaput');
Route::get('normalisasidatarincian/{idbulan}',[MonitoringRincianIndikatorROAdminConctroller::class, 'normalisasidatarincian'])->name('normalisasidatarincian')->middleware('cekadmincaput');
Route::get('hapusnormalisasidatarincian/{idbulan}',[MonitoringNormalisasiDataRincian::class,'hapusnormalisasidatarincian'])->name('hapusnormalisasidatarincian')->middleware('cekadmincaput');


//RINCIAN INDIKATOR RO
Route::resource('rincianindikatorro',RincianIndikatorRoController::class)->middleware('cekadmincaput');

//MONITORING REALISASI RO
Route::get('monitoringrealisasiro',[MonitoringRealisasiROConctroller::class,'realisasiro'])->name('monitoringrealisasiro')->middleware('cekadmincaput');
Route::get('getdatarealisasiroadmin/{idbulan}/{idbiro?}',[MonitoringRealisasiROConctroller::class, 'getdatarealisasiro'])->name('getdatarealisasiroadmin')->middleware('cekadmincaput');
Route::post('rekaprealisasiroadmin',[MonitoringRealisasiROConctroller::class,'rekaprealisasiro'])->name('rekaprealisasiroadmin')->middleware('cekadmincaput');
Route::get('exportrealisasiro',[MonitoringRealisasiROConctroller::class,'exportrealisasiro'])->name('exportrealisasiro')->middleware('cekadmincaput');
Route::get('exportrealisasianggaran',[MonitoringRealisasiROConctroller::class,'exportrealisasianggaran'])->name('exportrealisasiro')->middleware('cekadmincaput');


//REKON REALISASI RO SAKTI
Route::get('realisasirosakti',[RoSaktiController::class,'tampilrosakti'])->name('realisasirosakti')->middleware('cekadmincaput');
Route::get('getdatarealisasirosakti/{idbulan}/{idbiro?}',[RoSaktiController::class,'getdatarealisasiro'])->name('getdatarealisasirosakti')->middleware('cekadmincaput');
Route::get('importrealisasirosakti',[RoSaktiController::class,'importrosakti'])->name('importrealisasirosakti')->middleware('cekadmincaput');


//REALISASI RINCIAN INDIKATOR RO
Route::get('realisasirincianindikatorro',[RealisasiRincianIndikatorROConctroller::class,'realisasirincianindikatorro'])->name('realisasirincianindikatorro')->middleware('cekoperatorbagian');
Route::get('getdatarealisasi/{idbulan}',[RealisasiRincianIndikatorROConctroller::class,'getdatarealisasi'])->name('getdatarealisasi')->middleware('cekoperatorbagian');
Route::post('getdatarincianindikatorro',[RealisasiRincianIndikatorROConctroller::class,'getdatarincianindikatorro'])->name('getdatarincianindikatorro')->middleware('cekoperatorbagian');
Route::post('simpanrealisasirincian',[RealisasiRincianIndikatorROConctroller::class,'simpanrealisasirincian'])->name('simpanrealisasirincian')->middleware('cekoperatorbagian');
Route::post('updaterealisasirincian/{idrealisasi}',[RealisasiRincianIndikatorROConctroller::class,'updaterealisasirincian'])->name('updaterealisasirincian')->middleware('cekoperatorbagian');
Route::post('editrealisasirincian',[RealisasiRincianIndikatorROConctroller::class,'editrealisasirincian'])->name('editrealisasirincian')->middleware('cekoperatorbagian');
Route::delete('deleterealisasirincian/{idrealisasi}',[RealisasiRincianIndikatorROConctroller::class,'deleterealisasi'])->name('deleterealisasirincian')->middleware('cekoperatorbagian');
Route::get('cekjadwallapor/{idrincianindikatorro}/{idbulan}',[RealisasiRincianIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallapor');


//REALISASI RINCIAN INDIKATOR RO dipegang biro
Route::get('realisasirincianindikatorrobiro',[RealisasiRincianIndikatorROBiroConctroller::class,'realisasirincianindikatorro'])->name('realisasirincianindikatorrobiro');
Route::get('getdatarealisasibiro/{idbulan}',[RealisasiRincianIndikatorROBiroConctroller::class,'getdatarealisasi'])->name('getdatarealisasibiro');
Route::post('getdatarincianindikatorrobiro',[RealisasiRincianIndikatorROBiroConctroller::class,'getdatarincianindikatorro'])->name('getdatarincianindikatorrobiro');
Route::post('simpanrealisasirincianbiro',[RealisasiRincianIndikatorROBiroConctroller::class,'simpanrealisasirincian'])->name('simpanrealisasirincianbiro');
Route::post('updaterealisasirincianbiro/{idrealisasi}',[RealisasiRincianIndikatorROBiroConctroller::class,'updaterealisasirincian'])->name('updaterealisasirincianbiro');
Route::post('editrealisasirincianbiro',[RealisasiRincianIndikatorROBiroConctroller::class,'editrealisasirincian'])->name('editrealisasirincianbiro');
Route::delete('deleterealisasirincianbiro/{idrealisasi}',[RealisasiRincianIndikatorROBiroConctroller::class,'deleterealisasi'])->name('deleterealisasirincianbiro');

//JADWALTUTUP
Route::resource('jadwaltutup',JadwalTutupController::class)->middleware('cekadmincaput');

//realisasi indikator ro
Route::get('realisasiindikatorrobagian',[RealisasiIndikatorROConctroller::class,'realisasiindikatorro'])->name('realisasiindikatorro')->middleware('cekoperatorbiro');
Route::get('getdatarealisasiindikatorrobagian/{idbulan}',[RealisasiIndikatorROConctroller::class,'getdatarealisasiindikatorro'])->name('getdatarealisasiindikatorrobagian')->middleware('cekoperatorbiro');
Route::get('cekjadwallaporindikatorrobagian/{idindikatorro}/{nilaibulan}',[RealisasiIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallaporindikatorrobagian')->middleware('cekoperatorbiro');
Route::post('rekaprealisasiindikatorro',[RealisasiIndikatorROConctroller::class,'rekaprealisasiindikatorro'])->name('rekaprealisasiindikatorro')->middleware('cekoperatorbiro');

//realisasi indikator ro dipindahkan ke bagian
Route::get('realisasiindikatorro',[RealisasiIndikatorROBagianConctroller::class,'realisasiindikatorro'])->name('realisasiindikatorro')->middleware('cekoperatorbagian');
Route::get('getdatarealisasiindikatorro/{idbulan}',[RealisasiIndikatorROBagianConctroller::class,'getdatarealisasiindikatorro'])->name('getdatarealisasiindikatorro')->middleware('cekoperatorbagian');
Route::get('cekjadwallaporindikatorro/{idindikatorro}/{nilaibulan}',[RealisasiIndikatorROBagianConctroller::class,'cekjadwallapor'])->name('cekjadwallaporindikatorro')->middleware('cekoperatorbagian');
Route::post('getdataindikatorro',[RealisasiIndikatorROBagianConctroller::class,'getdataindikatorro'])->name('getdataindikatorro')->middleware('cekoperatorbagian');
Route::post('simpanrealisasiindikatorro',[RealisasiIndikatorROBagianConctroller::class,'simpanrealisasirincian'])->name('simpanrealisasiindikatorro')->middleware('cekoperatorbagian');
Route::post('updaterealisasiindikatorro/{idrealisasi}',[RealisasiIndikatorROBagianConctroller::class,'updaterealisasirincian'])->name('updaterealisasiindikatorro')->middleware('cekoperatorbagian');
Route::post('editrealisasiindikatorro',[RealisasiIndikatorROBagianConctroller::class,'editrealisasiindikatorro'])->name('editrealisasiindikatorro')->middleware('cekoperatorbagian');
Route::delete('deleterealisasiindikatorro/{idrealisasi}',[RealisasiIndikatorROBagianConctroller::class,'deleterealisasi'])->name('deleterealisasiindikatorro')->middleware('cekoperatorbagian');

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
Route::get('getdatarealisasimonitoring/{idbulan}/{idbagian?}',[MonitoringRincianIndikatorROConctroller::class,'getdatarealisasi'])->name('getdatarealisasimonitoring')->middleware('cekoperatorbiro');
Route::get('cekjadwallapormonitoring/{idrincianindikatorro}/{idbulan}',[MonitoringRincianIndikatorROConctroller::class,'cekjadwallapor'])->name('cekjadwallapormonitoring')->middleware('cekoperatorbiro');
Route::post('batalvalidasi',[MonitoringRincianIndikatorROConctroller::class,'batalvalidasirincianindikator'])->name('batalvalidasi')->middleware('cekoperatorbiro');

//ADMIN REALISASI
//REALISASI PER BIRO
Route::get('updatebagian',[DataAngController::class,'updatebagian'])->name('updatebagian');
Route::get('realisasiperbiro',[RealisasiPerBiroController::class,'index'])->name('realisasiperbiro');
Route::get('getrealisasiperbiro',[RealisasiPerBiroController::class,'getrealisasiperbiro'])->name('getrealisasiperbiro');
Route::get('exportrealisasiperbiro',[RealisasiPerBiroController::class,'exportrealisasiperbiro'])->name('exportrealisasiperbiro');

Route::get('detilrealisasi',[DetilRealisasi::class,'index'])->name('detilrealisasi');
Route::get('getdetilrealisasi',[DetilRealisasi::class,'getdetilrealisasi'])->name('getdetilrealisasi');
Route::get('exportdetilrealisasi',[DetilRealisasi::class,'exportdetilrealisasi'])->name('exportdetilrealisasi');
Route::get('detilrealisasibiro',[DetilRealisasiBiro::class,'index'])->name('detilrealisasibiro');
Route::get('getdetilrealisasibiro/{idbiro}',[DetilRealisasiBiro::class,'getdetilrealisasibiro'])->name('getdetilrealisasibiro');
Route::get('exportdetilrealisasibiro/{idbiro}',[DetilRealisasiBiro::class,'exportdetilrealisasibiro'])->name('exportdetilrealisasibiro');

Route::get('adminrealisasiperbagian',[AdminRealisasiPerBagianController::class,'index'])->name('adminrealisasiperbagian');
Route::get('admingetrealisasiperbagian',[AdminRealisasiPerBagianController::class,'getrealisasiperbagian'])->name('admingetrealisasiperbagian');
Route::get('adminrealisasibagianperpengenal/{idbagian}',[AdminRealisasiPerBagianController::class,'realisasibagianperpengenal'])->name('adminrealisasibagianperpengenal');
Route::get('admingetrealisasibagianperpengenal/{idbagian}',[AdminRealisasiPerBagianController::class,'getrealisasibagianperpengenal'])->name('admingetrealisasibagianperpengenal');
Route::get('exportrealisasiperbagian',[AdminRealisasiPerBagianController::class,'exportrealisasiperbagian'])->name('exportrealisasiperbagian');

//REALISASI PER PENGENAL
Route::get('realisasipengenal',[RealisasiPengenal::class,'index'])->name('realisasipengenal');
Route::get('getrealisasipengenal',[RealisasiPengenal::class,'getrealisasiperpengenal'])->name('getrealisasipengenal');
Route::get('exportrealisasiperpengenal',[RealisasiPengenal::class,'exportrealisasiperpengenal'])->name('exportrealisasiperpengenal');

Route::get('rencanarealisasipengenal',[RencanaRealisasiPengenal::class,'index'])->name('rencanarealisasipengenal');
Route::get('getrencanarealisasipengenal/{idbulan}',[RencanaRealisasiPengenal::class,'getrencanarealisasipengenal'])->name('getrencanarealisasipengenal');
Route::get('exportrencanarealisasipengenal/{idbulan}',[RencanaRealisasiPengenal::class,'exportrencanarealisasipengenal'])->name('exportrencanarealisasipengenal');
Route::get('rekaprencana',[RencanaRealisasiPengenal::class,'rekaprencana'])->name('rekaprencana');



//BIRO REALISASI
//REALISASI PERBAGIAN
Route::get('realisasiperbagian',[RealisasiPerBagianController::class,'index'])->name('realisasiperbagian');
Route::get('getrealisasiperbagian',[RealisasiPerBagianController::class,'getrealisasiperbagian'])->name('getrealisasiperbagian');
Route::get('realisasibagianperpengenal/{idbagian}',[RealisasiPerBagianController::class,'realisasibagianperpengenal'])->name('realisasibagianperpengenal');
Route::get('getrealisasibagianperpengenal/{idbagian}',[RealisasiPerBagianController::class,'getrealisasibagianperpengenal'])->name('getrealisasibagianperpengenal');
Route::get('exportrealisasibagianperpengenal/{idbagian}',[RealisasiPerBagianController::class,'exportrealisasibagianperpengenal'])->name('exportrealisasibagianperpengenal');

//RENCANA REALISASI TINGKAT BIRO
Route::get('rencanarealisasibiro',[RencanaRealisasiBiro::class,'index'])->name('rencanarealisasibiro');
Route::get('getrencanarealisasibiro/{idbulan}',[RencanaRealisasiBiro::class,'getrencanarealisasibiro'])->name('getrencanarealisasibiro');
Route::get('exportrencanarealisasibiro/{idbulan}',[RencanaRealisasiBiro::class,'exportrencanarealisasibiro'])->name('exportrencanarealisasibiro');

//BAGIAN REALISASI
Route::get('realisasiperpengenal',[RealisasiBagianPerPengenal::class,'index'])->name('realisasiperpengenal');
Route::get('getrealisasiperpengenal/{idbagian}',[RealisasiBagianPerPengenal::class,'getrealisasiperpengenal'])->name('getrealisasiperpengenal');
Route::get('exportrealisasipengenal/{idbagian}',[RealisasiBagianPerPengenal::class,'exportrealisasiperpengenal'])->name('exportrealisasiperpengenal');
Route::get('detilrealisasibagian',[DetilRealisasiBagian::class,'index'])->name('detilrealisasibagian');
Route::get('getdetilrealisasibagian/{idbagian}',[DetilRealisasiBagian::class,'getdetilrealisasibagian'])->name('getdetilrealisasibagian');
Route::get('exportdetilrealisasi/{idbagian}',[DetilRealisasiBagian::class,'exportdetilrealisasi'])->name('exportdetilrealisasi');

//RENCANA REALISASI PERBAGIAN
Route::get('rencanarealisasibagian',[RencanaRealisasiBagian::class,'index'])->name('rencanarealisasibagian');
Route::get('getrencanarealisasibagian/{idbulan}',[RencanaRealisasiBagian::class,'getrencanarealisasibagian'])->name('getrencanarealisasibagian');
Route::get('exportrencanarealisasibagian/{idbulan}',[RencanaRealisasiBagian::class,'exportrencanarealisasibagian'])->name('exportrencanarealisasibagian');



//REALISASI SEMAR
Route::get('realisasisemar',[RealisasiSemarController::class,'realisasisemar'])->name('realisasisemar')->middleware('auth');
Route::get('importrealisasisemar',[RealisasiSemarController::class,'importrealisasisemar'])->name('importrealisasisemar');

//REALISASI SP2D
Route::get('sppheader',[SppHeaderController::class,'sppheader'])->name('sppheader')->middleware('auth');
Route::get('importsppheader',[SppHeaderController::class,'importsppheader'])->name('importsppheader');
Route::get('importseluruhcoa',[SppHeaderController::class,'importseluruhcoa'])->name('importseluruhcoa')->middleware('auth');
Route::get('importcoa/{idspp}/{ta?}',[SppPengeluaranController::class,'importcoa'])->name('importcoa')->middleware('auth');
Route::get('lihatcoa/{idspp}',[SppPengeluaranController::class,'lihatcoa'])->name('lihatcoa')->middleware('auth');
Route::get('getlistpengeluaran/{ID_SPP}',[SppPengeluaranController::class,'getlistpengeluaran'])->name('getlistpengeluaran')->middleware('auth');
Route::get('getlistpotongan/{ID_SPP}',[SppPotonganController::class,'getlistpotongan'])->name('getlistpotongan')->middleware('auth');
Route::get('monitoringanggaranrealisasi',[SppHeaderController::class,'sppheader'])->name('sppheader')->middleware('auth');

//REALISASI SAKTI
Route::get('realisasisakti',[RealisasiSaktiController::class,'index'])->name('realisasisakti')->middleware('auth');
Route::get('getdatarealisasisakti',[RealisasiSaktiController::class,'getdetilrealisasi'])->name('getdatarealisasisakti')->middleware('auth');
Route::get('exportrealisasisakti',[RealisasiSaktiController::class,'exportdetilrealisasi'])->name('exportrealisasisakti')->middleware('auth');
Route::get('importrealisasisakti',[RealisasiSaktiController::class,'importrealisasisakti'])->name('importrealisasisakti')->middleware('auth');
Route::get('rekaprealisasiharian',[RealisasiSaktiController::class,'rekaprealisasiharian'])->name('rekaprealisasiharian')->middleware('auth');


//ADMIN SIRANGGA
Route::resource('area',AreaController::class);
Route::resource('subarea',SubAreaController::class);
Route::resource('gedung',GedungController::class);
Route::resource('lantai',LantaiController::class);
Route::resource('ruangan',RuanganController::class);
Route::post('/ambildatasubarea',[GedungController::class,'dapatkansubarea'])->name('ambildatasubarea');
Route::post('/ambildatagedung',[LantaiController::class,'dapatkangedung'])->name('ambildatagedung');
Route::post('/ambildatalantai',[RuanganController::class,'dapatkanlantai'])->name('ambildatalantai');
Route::get('getdataruangan',[RuanganController::class,'getdataruangan'])->name('getdataruangan');
Route::get('/buatdbr/{idruangan}',[RuanganController::class,'buatdbr'])->name('buatdbr');
Route::post('/dapatkandataaset',[BarangController::class,'dapatkandataaset'])->name('dapatkandataaset');


//ADMINISTRASI DBR
Route::get('dbrinduk',[DBRController::class,'dbrinduk'])->name('dbrinduk')->middleware('cekadminsirangga');
Route::get('getdatadbr',[DBRController::class,'getDataBDR'])->name('getdatadbr')->middleware('cekadminsirangga');
Route::post('updatepenanggungjawabdbr/{iddbr}',[DBRController::class,'updatepenanggungjawabdbr'])->name('updatepenanggungjawabdbr')->middleware('cekadminsirangga');
Route::get('editdbr/{iddbr}',[DBRController::class,'editdbr'])->name('editdbr')->middleware('cekadminsirangga');
Route::get('updatepenanggungjawab/{iddbr}',[DBRController::class,'updatepenanggungjawab'])->name('updatepenanggungjawab')->middleware('cekadminsirangga');
Route::post('aksiupdatepenanggungjawab/{iddbr}',[DBRController::class,'aksiupdatepenanggungjawab'])->name('aksiupdatepenanggungjawab')->middleware('cekadminsirangga');
Route::delete('deletedbr/{iddbr}',[DBRController::class,'deletedbr'])->name('deletedbr')->middleware('cekadminsirangga');
Route::get('kirimdbrkeunit/{iddbr}',[DBRController::class,'kirimdbrkeunit'])->name('kirimdbrkeunit')->middleware('cekadminsirangga');
Route::get('/perubahanfinal/{iddbr}',[DBRController::class,'perubahanfinal'])->name('perubahanfinal')->middleware('cekadminsirangga');
Route::get('/lihatdbr/{iddbr}',[DBRController::class,'lihatdbr'])->name('lihatdbr')->middleware('cekadminsirangga');
Route::get('getdatadetildbr/{iddbr}',[DBRController::class,'getdatadetildbr'])->name('getdatadetildbr')->middleware('cekadminsirangga');
Route::get('getdatabarangtambah',[DBRController::class,'getdatabarangtambah'])->name('getdatabarangtambah')->middleware('cekadminsirangga');
Route::post('insertbarangdipilih',[DBRController::class,'insertbarangdipilih'])->name('insertbarangdipilih')->middleware('cekadminsirangga');
Route::post('deletebarangdipilih',[DBRController::class,'deletebarangdipilih'])->name('deletebarangdipilih')->middleware('cekadminsirangga');
Route::post('konfirmasibarangada',[DBRController::class,'konfirmasibarangada'])->name('konfirmasibarangada')->middleware('cekadminsirangga');
Route::get('rekapbarang',[ImportSaktiController::class,'rekapdataaset'])->name('rekapbarang')->middleware('cekadminsirangga');
Route::get('exportdatabartender/{iddbr}',[DBRController::class,'databartenderexport'])->name('exportdatabartender');
Route::get('cekfisik/{iddbr}',[DBRController::class,'cekfisik'])->name('cekfisik');
Route::get('ingatkanunit/{iddbr}',[DBRController::class,'ingatkanunit'])->name('ingatkanunit');
Route::get('cetakdbr/{iddbr}',[DBRController::class,'cetakdbr'])->name('cetakdbr');
Route::get('cetakdbrgedung/{idgedung}',[GedungController::class,'cetakdbrgedung'])->name('cetakdbrgedung');
Route::get('testdbr',[TestDBRController::class,'testdbr'])->name('testdbr');
Route::get('exportdetildbr/{statusbarang}',[DetilDBRController::class,'exportdetildbr'])->name('exportdetildbr')->middleware('cekadminsirangga');
Route::get('exportdatabarang/{statusbarang}',[BarangController::class,'exportdatabarang'])->name('exportdatabarang')->middleware('cekadminsirangga');
Route::get('updatemasamanfaat',[BarangController::class,'updatemasamanfaat'])->name('updatemasamanfaat')->middleware('cekadminsirangga');
//Route::get('testanggal',[DBRController::class,'testanggal'])->name('testanggal');
//Route::get('kirimperingatan',[DBRController::class,'aksikirimperingatankeunit'])->name('kirimperingatan');

//detil dbr admin
Route::get('detildbr',[DetilDBRController::class,'detildbr'])->name('detildbr')->middleware('cekadminsirangga');
Route::get('/getdatadetildbradmin',[DetilDBRController::class,'getDataDetilBDR'])->name('getdatadetildbradmin')->middleware('cekadminsirangga');

//admin mengkonfirmasi barang yang dilaporkan hilang atau kembali
Route::post('konfirmhilangkembali',[DetilDBRController::class,'konfirmhilangkembali'])->name('konfirmhilangkembali')->middleware('cekadminsirangga');
Route::post('deletebarangterkonfirmasi',[KonfirmBarangController::class,'deletebarangterkonfirmasi'])->name('deletebarangterkonfirmasi')->middleware('cekadminsirangga');

//untuk menampilkan kumpulam barang yang sudah terkonfirmasi oleh admin
Route::get('barangterkonfirmasi',[KonfirmBarangController::class,'barangterkonfirmasi'])->name('barangterkonfirmasi')->middleware('cekadminsirangga');
Route::get('getdatabarangterkonfirmasi',[KonfirmBarangController::class,'getdatabarangterkonfirmasi'])->name('getdatabarangterkonfirmasi')->middleware('cekadminsirangga');
Route::get('exportbarangterkonfirmasi/{statusbarang}',[KonfirmBarangController::class,'exportbarangterkonfirmasi'])->name('exportbarangterkonfirmasi')->middleware('cekadminsirangga');

//monitoring detil dbr tidak normal -> barang sdh dihentikan, hapus tp masih ada di DBR
Route::get('detildbrtidaknormal',[DetilDBRTidakNormalController::class,'detildbr'])->name('detildbrtidaknormal')->middleware('cekadminsirangga');
Route::get('getdetildbrtidaknormal',[DetilDBRTidakNormalController::class,'getDataDetilBDRTidakNormal'])->name('getdetildbrtidaknormal')->middleware('cekadminsirangga');

//list import aset
Route::resource('listimportaset',ListImportSaktiController::class);
Route::get('importtransaksiaset/{kodebarang}',[ListImportSaktiController::class,'importtransaksiaset'])->name('importtransaksiaset');
Route::get('barang',[BarangController::class,'barang'])->name('barang');
Route::get('getdatabarang',[BarangController::class,'getdatabarang'])->name('getdatabarang');

//penghapusan
Route::get('penghapusanbarang',[MonitoringPenghapusanBarangController::class,'penghapusanbarang'])->name('penghapusanbarang')->middleware('cekadminsirangga');
Route::get('getdatapenghapusanbarang',[MonitoringPenghapusanBarangController::class,'getdatapenghapusanbarang'])->name('getdatapenghapusanbarang')->middleware('cekadminsirangga');
Route::get('rekappenghapusanbarang',[MonitoringPenghapusanBarangController::class,'rekappenghapusanbarang'])->name('rekappenghapusanbarang')->middleware('cekadminsirangga');
Route::get('exportpenghapusanbarang',[MonitoringPenghapusanBarangController::class,'exportpenghapusanbarang'])->name('exportpenghapusanbarang')->middleware('cekadminsirangga');
Route::get('exportdbrinduk',[DBRController::class,'exportdbrinduk'])->name('exportdbrinduk')->middleware('cekadminsirangga');

//DBR BAGIAN
Route::get('dbrindukbagian',[DBRBagianController::class,'dbrindukbagian'])->name('dbrindukbagian');
Route::get('lihatdbrbagian/{iddbr}',[DBRBagianController::class,'lihatdbrbagian'])->name('lihatdbrbagian');
Route::get('getdatadbrbagian',[DBRBagianController::class,'getdatadbrbagian'])->name('getdatadbrbagian');
Route::get('bagiangetdatadetildbr/{iddbr}',[DBRBagianController::class,'getdatadetildbr'])->name('bagiangetdatadetildbr');
Route::post('bagiankonfirmbarangada',[DBRBagianController::class,'konfirmasibarangada'])->name('bagiankonfirmbarangada');
Route::post('bagiankonfirmbarangtidakada',[DBRBagianController::class,'konfirmasibarangtidakada'])->name('bagiankonfirmbarangtidakada');
Route::post('bagiankonfirmbaranghilang',[DBRBagianController::class,'konfirmasibaranghilang'])->name('bagiankonfirmbaranghilang');
Route::post('bagiankonfirmpemeliharaan',[DBRBagianController::class,'konfirmasibarangpemeliharaan'])->name('bagiankonfirmpemeliharaan');
Route::post('bagiankonfirmpengembalian',[DBRBagianController::class,'konfirmasibarangpengembalian'])->name('bagiankonfirmpengembalian');
Route::get('setujuidbr/{iddbr}',[DBRBagianController::class,'setujuidbr'])->name('setujuidbr');
Route::post('penolakandbr/{iddbr}',[DBRBagianController::class,'penolakandbr'])->name('penolakandbr');
Route::get('/laporperubahan/{iddbr}',[DBRBagianController::class,'laporperubahan'])->name('laporperubahan');


//MODUL GL
Route::resource('monitoringimportbukubesar',BukuBesarController::class)->middleware('auth');
Route::get('importgl/{id}',[BukuBesarController::class,'importbukubesar'])->middleware('auth');

Route::get('fadetail',[FaDetailController::class,'fadetail'])->name('fadetail')->middleware('auth');
Route::get('importfadetail/{kdsatker}/{periode}',[FaDetailController::class,'importfadetail'])->name('importfadetail')->middleware('auth');
Route::get('exportfadetail/{kdsatker}/{periode}',[FaDetailController::class,'exportfadetail'])->name('exportfadetail')->middleware('auth');


//MODUL KOMITMEN BAST KONTRAK
Route::get('bastkontrakheader', [BASTKontrakHeaderController::class,'bastkontrakheader'])->name('bastkontrakheader');
Route::get('importbastkontrakheader',[BASTKontrakHeaderController::class,'importbastkontrakheader'])->name('importbastkontrakheader');
Route::get('importcoabastkontrak',[BASTKontrakHeaderController::class,'importcoabastkontrak'])->name('importcoabastkontrak');

//MODUL KOMITMEN KONTRAK
Route::get('kontrakheader', [KontrakHeaderController::class,'kontrakheader'])->name('kontrakheader');
Route::get('importkontrakheader',[KontrakHeaderController::class,'importkontrakheader'])->name('importkontrakheader');

//MODUL PEMANFAATAN
Route::resource('objeksewa',ObjekSewaController::class)->middleware('auth');
Route::get('getdataobjeksewa',[ObjekSewaController::class,'getDataObjekSewa'])->name('getdataobjeksewa');
Route::resource('penyewa',PenyewaController::class)->middleware('auth');
Route::get('getdatapenyewa',[PenyewaController::class,'getDataPenyewa'])->name('getdatapenyewa');
//penanggungjawabsewa
Route::resource('penanggungjawabsewa',PenanggungjawabSewaController::class);
Route::get('getdatapenanggungjawabsewa',[PenanggungjawabSewaController::class,'getdatapenanggungjawabsewa'])->name('getdatapenanggungjawabsewa');
Route::resource('monitoringpemanfaatan',MonitoringPemanfaatanController::class);
Route::get('getmonitoringtransaksipemanfaatan',[PenanggungjawabSewaController::class,'getdatapenanggungjawabsewa'])->name('getdatapenanggungjawabsewa');


//PEMANFAATAN | PENYEWA
Route::resource('referensipenyewa',ReferensiPenyewaController::class)->middleware('auth');
Route::get('getdatareferensipenyewa',[ReferensiPenyewaController::class,'getDataReferensiPenyewa'])->name('getdatareferensipenyewa');

//pemanfaatan | PENYEWA
Route::resource('referensipenanggungjawab',ReferensiPenanggungjawabSewaController::class)->middleware('auth');
Route::get('getdatareferensipenanggungjawab',[ReferensiPenanggungjawabSewaController::class,'getdatareferensipenanggungjawabsewa'])->name('getdatareferensipenanggungjawab');

//pemanfaatan || penyewa
Route::resource('transaksipemanfaatan',TransaksiPemanfaatanController::class);
Route::get('getdatatransaksipemanfaatan',[TransaksiPemanfaatanController::class,'getdatatransaksipemanfaatan'])->name('getdatatransaksipemanfaatan');



//MODUL KASBON
//BAGIAN
Route::resource('kasbonbagian',KasbonController::class)->middleware('cekoperatorbagian');
Route::get('getdatakasbonbagian',[KasbonController::class,'getdatakasbonbagian'])->name('getdatakasbonbagian')->middleware('cekoperatorbagian');
Route::post('ambildatapengenalbagian',[KasbonController::class,'ambildatapengenalbagian'])->name('ambildatapengenalbagian');
Route::get('ajukankasbonkeppk/{idkasbon}',[KasbonController::class,'ajukankasbonkeppk'])->name('ajukankasbonkeppk')->middleware('cekoperatorbagian');
Route::post('ambilrealisasipengenal',[KasbonController::class,'ambilpagurealisasi'])->name('ambilrealisasipengenal');
Route::get('prosespertanggungjawaban/{idkasbon}',[KasbonController::class,'prosespertanggungjawaban'])->name('prosespertanggungjawaban')->middleware('cekoperatorbagian');

//PPK Kasbon
Route::get('ppkkasbon',[PPKKasbonController::class,'index'])->name('ppkkasbon')->middleware('cekppk');
Route::get('getdatakasbonppk',[PPKKasbonController::class,'getdatakasbonppk'])->name('getdatakasbonppk')->middleware('cekppk');
Route::post('prosespengajuankasbonppk',[PPKKasbonController::class,'prosestransaksi'])->name('prosespengajuankasbonppk')->middleware('cekppk');
Route::get('editkasbonppk/{idkasbon}',[PPKKasbonController::class,'edit'])->name('editkasbonppk')->middleware('cekppk');

//PPSPM Kasbon
Route::get('ppspmkasbon',[PPSPMKasbonController::class,'index'])->name('ppspmkasbon')->middleware('cekppspm');
Route::get('getdatakasbonppspm',[PPSPMKasbonController::class,'getdatakasbonppspm'])->name('getdatakasbonppspm')->middleware('cekppspm');
Route::post('prosespengajuankasbonppspm',[PPSPMKasbonController::class,'prosestransaksi'])->name('prosespengajuankasbonppspm')->middleware('cekppspm');
Route::get('editkasbonppspm/{idkasbon}',[PPSPMKasbonController::class,'edit'])->name('editkasbonppspm')->middleware('cekppspm');

//Bendahara Kasbon
Route::get('bendaharakasbon',[BendaharaKasbonController::class,'index'])->name('bendaharakasbon')->middleware('cekbendahara');
Route::get('getdatakasbonbendahara',[BendaharaKasbonController::class,'getdatakasbonbendahara'])->name('getdatakasbonbendahara')->middleware('cekbendahara');
Route::post('prosespengajuankasbonbendahara',[BendaharaKasbonController::class,'prosestransaksi'])->name('prosespengajuankasbonbendahara')->middleware('cekbendahara');
Route::get('editkasbonbendahara/{idkasbon}',[BendaharaKasbonController::class,'edit'])->name('editkasbonbendahara')->middleware('cekbendahara');

//Kasir Kasbon
Route::get('kasirkasbon',[KasirKasbonController::class,'index'])->name('kasirkasbon')->middleware('cekkasir');
Route::get('getdatakasbonkasir',[KasirKasbonController::class,'getdatakasbonkasir'])->name('getdatakasbonkasir')->middleware('cekkasir');
Route::post('prosespengajuankasbonkasir',[KasirKasbonController::class,'prosestransaksi'])->name('prosespengajuankasbonkasir')->middleware('cekkasir');
Route::get('editkasbonkasir/{idkasbon}',[KasirKasbonController::class,'edit'])->name('editkasbonkasir')->middleware('cekkasir');
Route::get('prosespertanggungjawabankasir/{idkasbon}',[KasirKasbonController::class,'prosespertanggungjawaban'])->name('prosespertanggungjawabankasir')->middleware('cekkasir');


//MODUL IKPA
//ADMIN
//MODUL PENYERAPAN
Route::get('ikpapenyerapan',[IKPAPenyerapanController::class,'index'])->name('ikpapenyerapan')->middleware('cekadminikpa');
Route::get('getdatakinerjapenyerapan/{idbagian?}',[IKPAPenyerapanController::class,'getdataikpapenyerapanbagian'])->name('getdatakinerjapenyerapan')->middleware('cekadminikpa');
Route::get('hitungikpapenyerapanbagian',[IKPAPenyerapanController::class,'hitungikpapenyerapanbagian'])->name('hitungikpapenyerapanbagian')->middleware('cekadminikpa');
Route::get('exportikpapenyerapanbagian',[IKPAPenyerapanController::class,'exportikpapenyerapanbagian'])->name('exportikpapenyerapanbagian')->middleware('cekadminikpa');

Route::get('ikpapenyerapanbiro',[IKPAPenyerapanController::class,'indexbiro'])->name('ikpapenyerapanbiro')->middleware('cekadminikpa');
Route::get('getdatakinerjapenyerapanbiro/{idbiro?}',[IKPAPenyerapanController::class,'getdataikpapenyerapanbiro'])->name('getdatakinerjapenyerapanbiro')->middleware('cekadminikpa');
Route::get('hitungikpapenyerapanbiro',[IKPAPenyerapanController::class,'hitungikpapenyerapanbiro'])->name('hitungikpapenyerapanbiro')->middleware('cekadminikpa');
Route::get('exportikpapenyerapanbiro',[IKPAPenyerapanController::class,'exportikpapenyerapanbiro'])->name('exportikpapenyerapanbiro')->middleware('cekadminikpa');

//MODUL DEVIASI HAL III DIPA
Route::get('ikpadeviasi',[IKPADeviasiController::class,'index'])->name('ikpadeviasi')->middleware('cekadminikpa');
Route::get('getdatadeviasi/{idbagian?}',[IKPADeviasiController::class,'getdataikpadeviasi'])->name('getdatadeviasi')->middleware('cekadminikpa');
Route::get('hitungikpadeviasi',[IKPADeviasiController::class,'hitungikpadeviasibagian'])->name('hitungikpadeviasi')->middleware('cekadminikpa');
Route::get('exportikpadeviasi',[IKPADeviasiController::class,'exportikpadeviasibagian'])->name('exportikpadeviasi')->middleware('cekadminikpa');

Route::get('ikpadeviasibiro',[IKPADeviasiController::class,'indexbiro'])->name('ikpadeviasibiro')->middleware('cekadminikpa');
Route::get('getdatadeviasibiro/{idbiro?}',[IKPADeviasiController::class,'getdataikpadeviasibiro'])->name('getdatadeviasibiro')->middleware('cekadminikpa');
Route::get('hitungikpadeviasibiro',[IKPADeviasiController::class,'hitungikpadeviasibiro'])->name('hitungikpadeviasibiro')->middleware('cekadminikpa');
Route::get('exportikpadeviasibiro',[IKPADeviasiController::class,'exportikpadeviasibiro'])->name('exportikpadeviasibiro')->middleware('cekadminikpa');


//MODUL PENYELESAIAN TAGIHAN
Route::get('detilpenyelesaiantagihan',[DetilPenyelesaianController::class,'index'])->name('detilpenyelesaiantagihan')->middleware('cekadminikpa');
Route::get('getdetilpenyelesaian',[DetilPenyelesaianController::class,'getdetilpenyelesaian'])->name('getdetilpenyelesaian')->middleware('cekadminikpa');
Route::post('importdetilpenyelesaian',[DetilPenyelesaianController::class,'importdata'])->name('importdetilpenyelesaian')->middleware('cekadminikpa');

Route::get('ikpapenyelesaiantagihan',[IKPAPenyelesaianTagihanController::class,'index'])->name('ikpapenyelesaiantagihan')->middleware('cekadminikpa');
Route::get('getdatapenyelesaiantagihan/{idbagian?}',[IKPAPenyelesaianTagihanController::class,'getdataikpapenyelesaian'])->name('getdatapenyelesaiantagihan')->middleware('cekadminikpa');
Route::get('hitungikpapenyelesaianbagian',[IKPAPenyelesaianTagihanController::class,'hitungikpapenyelesaianbagian'])->name('hitungikpapenyelesaianbagian')->middleware('cekadminikpa');
Route::get('exportikpapenyelesaianbagian',[IKPAPenyelesaianTagihanController::class,'exportikpapenyelesaianbagian'])->name('exportikpapenyelesaianbagian')->middleware('cekadminikpa');

Route::get('ikpapenyelesaiantagihanbiro',[IKPAPenyelesaianTagihanController::class,'indexbiro'])->name('ikpapenyelesaiantagihanbiro')->middleware('cekadminikpa');
Route::get('getdatapenyelesaiantagihanbiro/{idbiro?}',[IKPAPenyelesaianTagihanController::class,'getdataikpapenyelesaianbiro'])->name('getdatapenyelesaiantagihanbiro')->middleware('cekadminikpa');
Route::get('hitungikpapenyelesaianbiro',[IKPAPenyelesaianTagihanController::class,'hitungikpapenyelesaianbiro'])->name('hitungikpapenyelesaianbiro')->middleware('cekadminikpa');
Route::get('exportikpapenyelesaianbiro',[IKPAPenyelesaianTagihanController::class,'exportikpapenyelesaianbiro'])->name('exportikpapenyelesaianbiro')->middleware('cekadminikpa');


//IKPA MODUL KONTRAKTUAL
Route::get('detilikpakontraktual',[DetilIKPAKontraktualController::class,'index'])->name('ikpadetilkontraktual')->middleware('cekadminikpa');
Route::get('getdetilkontraktual',[DetilIKPAKontraktualController::class,'getdetilkontraktual'])->name('getdetilkontraktual')->middleware('cekadminikpa');
Route::post('importdetilkontraktual',[DetilIKPAKontraktualController::class,'importdata'])->name('importdetilkontraktual')->middleware('cekadminikpa');
Route::get('importkontrakheaderjob',[DetilIKPAKontraktualController::class,'importkontrakcoa'])->name('importkontrakheaderjob')->middleware('cekadminikpa');
Route::get('ikpakontraktual',[IKPAKontraktualController::class,'index'])->name('ikpakontraktual')->middleware('cekadminikpa');
Route::get('getdataikpakontraktual/{idbagian?}',[IKPAKontraktualController::class,'getdataikpakontraktualbagian'])->name('getdataikpakontraktual')->middleware('cekadminikpa');
Route::get('hitungikpakontraktualbagian',[IKPAKontraktualController::class,'hitungikpakontraktualbagian'])->name('hitungikpakontraktualbagian')->middleware('cekadminikpa');
Route::get('exportikpakontraktualbagian',[IKPAKontraktualController::class,'exportikpakontraktualbagian'])->name('exportikpakontraktualbagian')->middleware('cekadminikpa');

Route::get('ikpakontraktualbiro',[IKPAKontraktualController::class,'indexbiro'])->name('ikpakontraktualbiro')->middleware('cekadminikpa');
Route::get('getdataikpakontraktualbiro/{idbiro?}',[IKPAKontraktualController::class,'getdataikpakontraktualbiro'])->name('getdataikpakontraktualbiro')->middleware('cekadminikpa');
Route::get('hitungikpakontraktualbiro',[IKPAKontraktualController::class,'hitungikpakontraktualbiro'])->name('hitungikpakontraktualbiro')->middleware('cekadminikpa');
Route::get('exportikpakontraktualbiro',[IKPAKontraktualController::class,'exportikpakontraktualbiro'])->name('exportikpakontraktualbiro')->middleware('cekadminikpa');


//IKPA CAPUT BAGIAN
Route::get('monitoringikpacaputbagian',[IKPACaputController::class,'index'])->name('monitoringikpacaputbagian')->middleware('cekadminikpa');
Route::get('getdatamonitoringikpacaput/{idbagian?}',[IKPACaputController::class,'getdataikpacaput'])->name('getdatamonitoringikpacaput')->middleware('cekadminikpa');
Route::get('hitungikpacaputbagian',[IKPACaputController::class,'hitungikpacaputbagian'])->name('hitungikpacaputbagian')->middleware('cekadminikpa');
Route::get('exportikpacaputbagian',[IKPACaputController::class,'exportikpacaputbagian'])->name('exportikpacaputbagian')->middleware('cekadminikpa');

//IKPA CAPUT BIRO
Route::get('monitoringikpacaputbiro',[IKPACaputBiroController::class,'index'])->name('monitoringikpacaputbiro')->middleware('cekadminikpa');
Route::get('getdatamonitoringikpacaputbiro/{idbiro?}',[IKPACaputBiroController::class,'getdataikpacaput'])->name('getdatamonitoringikpacaputbiro')->middleware('cekadminikpa');
Route::get('hitungikpacaputbiro',[IKPACaputBiroController::class,'hitungikpacaputbiro'])->name('hitungikpacaputbiro')->middleware('cekadminikpa');
Route::get('exportikpacaputbiro',[IKPACaputBiroController::class,'exportikpacaputbiro'])->name('exportikpacaputbiro')->middleware('cekadminikpa');

//IKPA REVISI
Route::get('detilikparevisi',[DetilIKPARevisiController::class,'index'])->name('detilikparevisi')->middleware('cekadminikpa');
Route::get('getdetilrevisi',[DetilIKPARevisiController::class,'getdetilrevisi'])->name('getdetilrevisi')->middleware('cekadminikpa');
Route::post('importdetilrevisi',[DetilIKPARevisiController::class,'importdata'])->name('importdetilrevisi')->middleware('cekadminikpa');

Route::get('ikparevisibagian',[IKPARevisiController::class,'index'])->name('ikparevisibagian')->middleware('cekadminikpa');
Route::get('getdataikparevisibagian/{idbagian?}',[IKPARevisiController::class,'getdataikparevisibagian'])->name('getdataikparevisibagian')->middleware('cekadminikpa');
Route::get('hitungikparevisibagian',[IKPARevisiController::class,'hitungikparevisibagian'])->name('hitungikparevisibagian')->middleware('cekadminikpa');
Route::get('exportikparevisibagian',[IKPARevisiController::class,'exportikparevisibagian'])->name('exportikparevisibagian')->middleware('cekadminikpa');

Route::get('ikparevisibiro',[IKPARevisiController::class,'indexbiro'])->name('ikparevisibiro')->middleware('cekadminikpa');
Route::get('getdataikparevisibiro/{idbiro?}',[IKPARevisiController::class,'getdataikparevisibiro'])->name('getdataikparevisibiro')->middleware('cekadminikpa');
Route::get('hitungikparevisibiro',[IKPARevisiController::class,'hitungikparevisibiro'])->name('hitungikparevisibiro')->middleware('cekadminikpa');
Route::get('exportikparevisibiro',[IKPARevisiController::class,'exportikparevisibiro'])->name('exportikparevisibiro')->middleware('cekadminikpa');


//IKPA REKAP
Route::get('rekapikpabagian',[RekapIKPABagianController::class,'index'])->name('rekapikpabagian')->middleware('cekadminikpa');
Route::get('getdatarekapikpabagian/{idbagian?}',[RekapIKPABagianController::class,'getdatarekapikpabagian'])->name('getdatarekapikpabagian')->middleware('cekadminikpa');
Route::get('hitungrekapikpabagian',[RekapIKPABagianController::class,'hitungrekapikpabagian'])->name('hitungrekapikpabagian')->middleware('cekadminikpa');
Route::get('exportrekapikpabagian',[RekapIKPABagianController::class,'exportrekapikpabagian'])->name('exportrekapikpabagian')->middleware('cekadminikpa');

Route::get('rekapikpabiro',[RekapIKPABagianController::class,'indexbiro'])->name('rekapikpabiro')->middleware('cekadminikpa');
Route::get('getdatarekapikpabiro/{idbagian?}',[RekapIKPABagianController::class,'getdatarekapikpabiro'])->name('getdatarekapikpabiro')->middleware('cekadminikpa');
Route::get('hitungrekapikpabiro',[RekapIKPABagianController::class,'hitungrekapikpabiro'])->name('hitungrekapikpabiro')->middleware('cekadminikpa');
Route::get('exportrekapikpabiro',[RekapIKPABagianController::class,'exportrekapikpabiro'])->name('exportrekapikpabiro')->middleware('cekadminikpa');


//MODUL IKPA BAGIAN
//PENYERAPAN
Route::get('ikpapenyerapanbagian',[IKPAPenyerapanBagianController::class,'index'])->name('ikpapenyerapanbagian')->middleware('cekoperatorbagian');
Route::get('getdatakinerjapenyerapanbagian',[IKPAPenyerapanBagianController::class,'getdataikpapenyerapanbagian'])->name('getdatakinerjapenyerapanbagian')->middleware('cekoperatorbagian');

//PENYELESAIAN
Route::get('ikpapenyelesaianbagian',[IKPAPenyelesaianTagihanBagianController::class,'index'])->name('ikpapenyelesaianbagian')->middleware('cekoperatorbagian');
Route::get('getdataikpapenyelesaianbagian',[IKPAPenyelesaianTagihanBagianController::class,'getdataikpapenyelesaian'])->name('getdataikpapenyelesaianbagian')->middleware('cekoperatorbagian');

//DEVIASI
Route::get('ikpadeviasibagian',[IKPADeviasiBagianController::class,'index'])->name('ikpadeviasibagian')->middleware('cekoperatorbagian');
Route::get('getdatadeviasibagian',[IKPADeviasiBagianController::class,'getdataikpadeviasi'])->name('getdatadeviasibagian')->middleware('cekoperatorbagian');

//KONTRAKTUAL
Route::get('ikpakontraktualbagian',[IKPAKontraktualBagianController::class,'index'])->name('ikpadeviasibagian')->middleware('cekoperatorbagian');
Route::get('getdatakontraktualbagian',[IKPAKontraktualBagianController::class,'getdataikpakontraktualbagian'])->name('getdatakontraktualbagian')->middleware('cekoperatorbagian');

//REKAP IKPA BAGIAN
Route::get('rekapikpaaksesbagian',[RekapIKPAAksesBagianController::class,'index'])->name('rekapikpaaksesbagian')->middleware('cekoperatorbagian');
Route::get('getdatarekapikpaaksesbagian/{idbagian?}',[RekapIKPAAksesBagianController::class,'getdatarekapikpabagian'])->name('getdatarekapikpaaksesbagian')->middleware('cekoperatorbagian');




//MODUL IKPA BIRO
//PENYERAPAN
Route::get('ikpapenyerapanksesbiro',[IKPAPenyerapanAksesBiroController::class,'index'])->name('ikpapenyerapanksesbiro')->middleware('cekoperatorbiro');
Route::get('getdatakinerjapenyerapanaksesbiro',[IKPAPenyerapanAksesBiroController::class,'getdataikpapenyerapanbagian'])->name('getdatakinerjapenyerapanaksesbiro')->middleware('cekoperatorbiro');

//PENYELESAIAN
Route::get('ikpapenyelesaianaksesbiro',[IKPAPenyelesaianTagihanAksesBiroController::class,'index'])->name('ikpapenyelesaianaksesbiro')->middleware('cekoperatorbiro');
Route::get('getdataikpapenyelesaianaksesbiro',[IKPAPenyelesaianTagihanAksesBiroController::class,'getdataikpapenyelesaian'])->name('getdataikpapenyelesaianaksesbiro')->middleware('cekoperatorbiro');

//DEVIASI
Route::get('ikpadeviasiaksesbiro',[IKPADeviasiAksesBiroController::class,'index'])->name('ikpadeviasiaksesbiro')->middleware('cekoperatorbiro');
Route::get('getdatadeviasiaksesbiro',[IKPADeviasiAksesBiroController::class,'getdataikpadeviasi'])->name('getdatadeviasiaksesbiro')->middleware('cekoperatorbiro');

//KONTRAKTUAL
Route::get('ikpakontraktualaksesbiro',[IKPAKontraktualAksesBiroController::class,'index'])->name('ikpakontraktualaksesbiro')->middleware('cekoperatorbiro');
Route::get('getdatakontraktualaksesbiro',[IKPAKontraktualAksesBiroController::class,'getdataikpakontraktualbagian'])->name('getdatakontraktualaksesbiro')->middleware('cekoperatorbiro');

//REKAP IKPA BIRO
Route::get('rekapikpabagianaksesbiro',[RekapIKPABagianAksesBiroController::class,'index'])->name('rekapikpabagianaksesbiro')->middleware('cekoperatorbiro');
Route::get('getdatarekapikpabagianaksesbiro/{idbagian?}',[RekapIKPABagianAksesBiroController::class,'getdatarekapikpabagian'])->name('getdatarekapikpabagianaksesbiro')->middleware('cekoperatorbiro');

//REKAP IKPA BAGIAN
Route::get('rekapikpabiroaksesbiro',[RekapIKPABiroAksesBiroController::class,'index'])->name('rekapikpabiroaksesbiro')->middleware('cekoperatorbiro');
Route::get('getdatarekapikpabiroaksesbiro',[RekapIKPABiroAksesBiroController::class,'getdatarekapikpabagian'])->name('getdatarekapikpabiroaksesbiro')->middleware('cekoperatorbiro');





//MODUL RENCANA KAS ADMIN
Route::get('rencanakegiatan',[RencanaKegiatanController::class,'index'])->name('rencanakegiatan')->middleware('cekadmin');
Route::get('getdatarencanakegiatan/{idbagian?}',[RencanaKegiatanController::class,'getdatarencana'])->name('getdatarencanakegiatan')->middleware('cekadmin');
Route::get('exportrencanapenarikan',[RencanaKegiatanController::class,'exportrencanapenarikan'])->name('exportrencanapenarikan')->middleware('cekadmin');
Route::get('tutupperioderencana',[RencanaKegiatanController::class,'tutupperioderencana'])->name('tutupperioderencana')->middleware('cekadmin');
Route::get('bukaperioderencana',[RencanaKegiatanController::class,'bukaperioderencana'])->name('bukaperioderencana')->middleware('cekadmin');
Route::get('rekaprealisasirencana',[RencanaKegiatanController::class,'rekaprealisasiseluruh'])->name('rekaprealisasirencana')->middleware('cekadmin');


//MODUL RENCANA KAS BAGIAN
//Route::resource('rencanakegiatanbagian',RencanaKegiatanBagianController::class)->middleware('cekoperatorbagian');
//Route::get('getdatarencanakegiatanbagian',[RencanaKegiatanBagianController::class,'getdatarencanakegiatanbagian'])->name('getdatarencanakegiatanbagian')->middleware('cekoperatorbagian');
//Route::get('ajukanrencanakeppk/{id}',[RencanaKegiatanBagianController::class,'pengajuanrencanakeppk'])->name('ajukanrencanakeppk')->middleware('cekoperatorbagian');
//Route::resource('rencanakegiatanbagiandetil',RencanaKegiatanBagianDetilController::class)->middleware('cekoperatorbagian')->except(['index','edit']);
//Route::get('rencanakegiatanbagiandetil/{idrencanakegiatan}',[RencanaKegiatanBagianDetilController::class,'index'])->middleware('cekoperatorbagian');
//Route::get('editrencanakegiatanbagiandetil/{idrencanakegiatan}',[RencanaKegiatanBagianDetilController::class,'edit'])->name('editrencanakegiatanbagiandetil')->middleware('cekoperatorbagian');
//Route::get('getrencanakegiatanbagiandetil/{idrencanakegiatan}',[RencanaKegiatanBagianDetilController::class,'getrencanakegiatanbagiandetil'])->name('getrencanakegiatanbagiandetil')->middleware('cekoperatorbagian');
//Route::post('ambildatapengenal',[RencanaKegiatanBagianDetilController::class,'ambildatapengenal'])->name('ambildatapengenal');
//Route::get('monitoringrencanakegiatan',[Monitoringrencanakegiatan::class,'index'])->name('monitoringrencanakegiatan')->middleware('cekoperatorbagian');
//Route::get('getmonitoringrencanakegiatan/{idbagian}',[Monitoringrencanakegiatan::class,'getmonitoringrencanakegiatan'])->name('getmonitoringrencanakegiatan')->middleware('cekoperatorbagian');

//MODUL RENCANA KAS VERSI 2
Route::resource('rencanakegiatanbagian',RencanaKegiatanIndukBagianController::class)->middleware('cekoperatorbagian');
Route::get('getdatarencanakegiatanindukbagian',[RencanaKegiatanIndukBagianController::class,'getdatarencanakegiatanbagian'])->name('getdatarencanakegiatanindukbagian')->middleware('cekoperatorbagian');
Route::get('detilrencanakegiatanbagian/{idrencanakegiatan}',[RencanaKegiatanIndukBagianController::class,'tampildetil'])->middleware('cekoperatorbagian');
Route::get('getdetilrencanakegiatanindukbagian',[RencanaKegiatanIndukBagianController::class,'getdetilrencanakegiatanbagian'])->name('getdetilrencanakegiatanindukbagian')->middleware('cekoperatorbagian');
Route::post('ambildatapengenalsatker',[RencanaKegiatanIndukBagianController::class,'ambildatapengenal'])->name('ambildatapengenalsatker')->middleware('cekoperatorbagian');
Route::post('simpandetilrencana',[RencanaKegiatanIndukBagianController::class,'simpandetilrencana'])->name('simpandetilrencana')->middleware('cekoperatorbagian');
Route::get('editdetilrencana/{iddetil}',[RencanaKegiatanIndukBagianController::class,'editdetilrencana'])->name('editdetilrencana')->middleware('cekoperatorbagian');
Route::put('updatedetilrencana',[RencanaKegiatanIndukBagianController::class,'updatedetilrencana'])->name('updatedetilrencana')->middleware('cekoperatorbagian');
Route::delete('deletedetilrencana/{iddetil}',[RencanaKegiatanIndukBagianController::class,'hapusdetilrencana'])->name('deletedetilrencana')->middleware('cekoperatorbagian');
Route::post('ambildatapengenal',[RencanaKegiatanIndukBagianController::class,'ambildatapengenaldetil'])->name('ambildatapengenal')->middleware('cekoperatorbagian');
Route::get('monitoringrencanakegiatan',[RencanaKegiatanIndukBagianController::class,'tampilmonitoring'])->name('monitoringrencanakegiatan')->middleware('cekoperatorbagian');
Route::get('getmonitoringrencanakegiatanbagian',[RencanaKegiatanIndukBagianController::class,'getdatamonitoring'])->name('getmonitoringrencanakegiatanbagian')->middleware('cekoperatorbagian');
Route::get('exportrencanapenarikanbagian',[RencanaKegiatanIndukBagianController::class,'exportrencanapenarikanbagian'])->name('exportrencanapenarikanbagian')->middleware('cekoperatorbagian');
Route::get('rekaprealisasiberjalan',[RencanaKegiatanIndukBagianController::class,'rekaprealisasiberjalan'])->name('rekaprealisasiberjalan')->middleware('cekoperatorbagian');
Route::get('setrencanaterlaksana/{idrencanakegiatan}',[RencanaKegiatanIndukBagianController::class,'setrencanaterlaksana'])->name('setrencanaterlaksana')->middleware('cekoperatorbagian');
Route::get('setrencanaterjadwal/{idrencanakegiatan}',[RencanaKegiatanIndukBagianController::class,'setrencanaterjadwal'])->name('setrencanaterjadwal')->middleware('cekoperatorbagian');


// rencana kegiatan biro
Route::resource('rencanakegiatanbiro',RencanaKegiatanBiroController::class)->middleware('cekoperatorbiro');
Route::get('getdatarencanakegiatanbiro/{idbagian?}',[RencanaKegiatanBiroController::class,'getdatarencanakegiatanbagian'])->name('getdatarencanakegiatanbiro')->middleware('cekoperatorbiro');

//admin realisasi untuk referensi PPK
Route::resource('ppksatker',PPKSatkerController::class)->middleware('cekadmin');
Route::resource('kewenanganppk',KewenanganPPKController::class)->middleware('cekadmin');
Route::resource('penetapanppk',PenetapanPPKController::class)->middleware('cekadmin');
Route::resource('penetapanbendahara',PenetapanBendaharaController::class)->middleware('cekadmin');
Route::resource('penetapankasir',PenetapanKasirController::class)->middleware('cekadmin');
Route::post('ambillistppk',[KewenanganPPKController::class,'ambillistppk'])->name('ambillistppk')->middleware('cekadmin');
