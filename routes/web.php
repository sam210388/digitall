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




Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('kewenangan',KewenanganController::class);
Route::any('/tampillistmenu',[MenuController::class,'tampillistmenu'])->name('tampillistmenu');
Route::resource('menu',MenuController::class);
Route::resource('submenu',SubMenuController::class);
Route::resource('kewenanganmenu',KewenanganMenuController::class);
Route::resource('kewenanganuser',KewenanganUserController::class);
Route::any('/editpassword/{id}',[AdministrasiUserController::class,'editpassword']);
Route::resource('kelolauser',AdministrasiUserController::class);
Route::resource('deputi',DeputiController::class);
Route::resource('biro',BiroController::class);
Route::post('/ambildatabiro',[BagianController::class,'dapatkandatabiro'])->name('ambildatabiro');
Route::post('/ambildatabagian',[BagianController::class,'dapatkandatabagian'])->name('ambildatabagian');
Route::get('/kirimtemuankeunit/{id}',[RekomendasiController::class,'kirimtemuankeunit'])->name('kirimtemuankeunit')->middleware(['auth','cekadminbpk']);
Route::get('/kirimtemuankebpk/{id}',[RekomendasiController::class,'kirimtemuankebpk'])->name('kirimtemuankebpk')->middleware(['auth','cekadminbpk']);
Route::resource('bagian',BagianController::class)->middleware(['auth']);
Route::resource('updateunitkerja',UserBiroBagianController::class);
Route::resource('temuan',RekomendasiController::class)->middleware('auth');
Route::resource('temuanbpkbagian',RekomendasiBagianController::class)->middleware('cekoperatorbagian');
Route::get('tindaklanjutbagian/{idtemuan}',[TindakLanjutBagianController::class,'tampiltindaklanjut'])->name('tindaklanjutbagian')->middleware(['cekpemiliktemuan']);
Route::post('getdatatindaklanjut', [TindakLanjutBagianController::class,'getdatatindaklanjut'])->name('getdatatindaklanjut');
Route::resource('kelolatindaklanjut',TindakLanjutBagianController::class)->middleware(['cekoperatorbagian','cekpemiliktemuan']);
Route::get('/ajukankeirtama/{idtindaklanjut}',[TindakLanjutBagianController::class,'ajukankeirtama'])->name('ajukankeirtama')->middleware(['cekpemiliktemuan']);
Route::get('/statustemuanselesai/{id}',[RekomendasiController::class,'statustemuanselesai'])->name('statustemuanselesai')->middleware(['auth','cekadminbpk']);
Route::get('/statustemuantddl/{id}',[RekomendasiController::class,'statustemuantddl'])->name('statustemuantddl')->middleware(['auth','cekadminbpk']);
Route::get('lihattindaklanjutbagian/{idtemuan}',[TindakLanjutAdminController::class,'tampiltindaklanjut'])->name('lihattindaklanjutbagian')->middleware(['cekadminbpk']);
Route::get('/ajukankebpk/{idtindaklanjut}',[TindakLanjutAdminController::class,'ajukankebpk'])->name('ajukankebpk')->middleware(['cekadminbpk']);
Route::get('/tindaklanjutselesai/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjutselesai'])->name('tindaklanjutselesai')->middleware(['cekadminbpk']);
Route::post('getdatatindaklanjutbagian', [TindakLanjutAdminController::class,'getdatatindaklanjutbagian'])->name('getdatatindaklanjutbagian');
Route::post('simpanpenjelasan', [TindakLanjutAdminController::class,'simpanpenjelasan'])->name('simpanpenjelasan')->middleware('cekadminbpk');
Route::get('/tindaklanjuttddl/{idtindaklanjut}',[TindakLanjutAdminController::class,'tindaklanjuttddl'])->name('tindaklanjuttddl')->middleware(['cekadminbpk']);
Route::post('simpantanggapan', [TindakLanjutBagianController::class,'simpantanggapan'])->name('simpanpenjelasan')->middleware('cekpemiliktemuan');
Route::get('/lihattanggapan/{idtindaklanjut}',[TindakLanjutAdminController::class,'lihattanggapan'])->name('lihattanggapan')->middleware(['cekadminbpk']);






