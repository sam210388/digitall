<?php

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
    return view('auth.login');
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

//REFERENSI UNIT KERJA
Route::resource('deputi',DeputiController::class);
Route::resource('biro',BiroController::class);
Route::post('/ambildatabiro',[BagianController::class,'dapatkandatabiro'])->name('ambildatabiro');
Route::post('/ambildatabagian',[BagianController::class,'dapatkandatabagian'])->name('ambildatabagian');
Route::resource('bagian',BagianController::class)->middleware(['auth']);
Route::resource('updateunitkerja',UserBiroBagianController::class);

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

//BAGIAN
//BPK
Route::resource('rekomendasibpkbagian',RekomendasiBagianController::class)->middleware('cekoperatorbagian');
Route::get('tindaklanjutbagian/{idrekomendasi}',[TindakLanjutBagianController::class,'tampiltindaklanjut'])->name('tindaklanjutbagian')->middleware(['cekpemilikrekomendasi']);
Route::post('getdatatindaklanjut', [TindakLanjutBagianController::class,'getdatatindaklanjut'])->name('getdatatindaklanjut')->middleware(['cekpemilikrekomendasi']);
Route::resource('kelolatindaklanjut',TindakLanjutBagianController::class)->middleware(['cekoperatorbagian','cekpemilikrekomendasi']);
Route::get('/ajukankeirtama/{idtindaklanjut}',[TindakLanjutBagianController::class,'ajukankeirtama'])->name('ajukankeirtama')->middleware(['cekpemilikrekomendasi']);
Route::post('simpantanggapan', [TindakLanjutBagianController::class,'simpantanggapan'])->name('simpanpenjelasan')->middleware('cekpemilikrekomendasi');
Route::get('getdetiltemuan/{idrekomendasi}',[RekomendasiBagianController::class,'getdetiltemuan'])->name('getdetiltemuan')->middleware(['cekpemilikrekomendasi']);



