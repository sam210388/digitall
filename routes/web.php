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

//suboutput
Route::get('suboutput',[SubOutputController::class,'suboutput'])->name('suboutput')->middleware('cekadminanggaran');
Route::get('getlistsuboutput',[SubOutputController::class,'getListSubOutput'])->name('getlistsuboutput')->middleware('cekadminanggaran');
Route::get('importsuboutput',[SubOutputController::class,'importsuboutput'])->name('importsuboutput')->middleware('cekadminanggaran');

//komponen
Route::get('komponen',[KomponenController::class,'komponen'])->name('komponen')->middleware('cekadminanggaran');
Route::get('getlistkomponen',[KomponenController::class,'getListKomponen'])->name('getlistkomponen')->middleware('cekadminanggaran');
Route::get('importkomponen',[KomponenController::class,'importkomponen'])->name('importkomponen')->middleware('cekadminanggaran');


//admin anggaran
//refstatus
Route::get('refstatus',[RefstatusController::class,'refstatus'])->name('refstatus')->middleware('cekadminanggaran');
Route::get('getlistrefstatus',[RefstatusController::class,'getListRefstatus'])->name('getlistrefstatus')->middleware('cekadminanggaran');
Route::get('importrefstatus',[RefstatusController::class,'importrefstatus'])->name('importrefstatus')->middleware('cekadminanggaran');
Route::get('importanggaran/{kdsatker}/{kdstshistory}',[DataAngController::class,'importdataang'])->name('importanggaran')->middleware('cekadminanggaran');
Route::post('checkdataang',[DataAngController::class,'checkdata'])->name('checkdataang')->middleware('cekadminanggaran');



