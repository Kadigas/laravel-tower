<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PeopleCounterController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

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

Route::get('/list-reservasi',[ReservasiController::class, 'listReservasi'])->name('listReservasi');

/*User*/
Route::controller(UserController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/status', 'status')->name('status');
    Route::get('/panduan', 'panduan')->name('panduanReservasi');
    Route::get('/jadwal', 'jadwal')->name('jadwal');
    Route::get('/ruangan', 'ruanganView')->name('ruanganView');
    Route::post('api/user/fetch-ruangan','fetchruanganUser')->name('fetchruanganUser');
    Route::post('/fetch-jadwal','jadwalAjax')->name('jadwalAjax');
    Route::get('/lantai4', 'lantai4')->name('lantai4');
    Route::get('/lantai5', 'lantai5')->name('lantai5');
    Route::get('/lantai6', 'lantai6')->name('lantai6');
    Route::get('/lantai7', 'lantai7')->name('lantai7');
    Route::get('/lantai8', 'lantai8')->name('lantai8');
});

Route::controller(StaffController::class)->group(function () {
    Route::get('/staff', 'index')->name('staffDisplay');
});

/*Make Reservation*/
Route::controller(ReservasiController::class)->group(function () {
    Route::get('/reservasi','stepOne')->name('reservasi');
    Route::post('/reservasipost', 'createOne')->name('postCreateStepOne');
    Route::get('/reservasi/InformasiPJ', 'stepTwo')->name('penanggungJawab');
    Route::post('/reservasi/InformasiPJpost', 'createTwo')->name('postCreateStepTwo');
    Route::get('/reservasi/detailPeminjaman', 'stepThree')->name('detailPeminjaman');
    Route::post('/fetch-Ruangan','detailPeminjamanAjax')->name('detailPeminjamanAjax');
    Route::post('/yeybisa','selectroom')->name('selectRoom');
    Route::post('/reservasi/detailPeminjamanpost', 'createThree')->name('postCreateStepThree');
    Route::get('/reservasi/detailKegiatan', 'stepFour')->name('detailKegiatan');
    Route::post('/reservasi/detailKegiatanpost', 'createFour')->name('postCreateStepFour');
    Route::get('/confirmed', 'confirm')->name('confirmed');
});

/* admin */
Route::controller(RuanganController::class)->group(function () {
    Route::get('/viewClass','index')->name('viewClass');
    Route::get('/viewClass/Lantai4','lantai4')->name('lantai4View');
    Route::get('/viewClass/Lantai5','lantai5')->name('lantai5View');
    Route::get('/viewClass/Lantai6','lantai6')->name('lantai6View');
    Route::get('/viewClass/Lantai7','lantai7')->name('lantai7View');
    Route::get('/viewClass/Lantai8','lantai8')->name('lantai8View');
});

/*Report Page*/
Route::controller(ReportController::class)->group(function () {
    Route::get('/viewReport','index')->name('viewReport');
    Route::get('/viewReport/semester','semester')->name('viewSemester');
    Route::get('/viewReport/month','month')->name('viewMonth');
    Route::get('/viewReport/week','week')->name('viewWeek');
});

/*Login*/
Route::get('/login',[AdminController::class, 'loginpage'])->name('login');
Route::post('/loginuser',[AdminController::class, 'login'])->name('login.post');
Route::get('/admin',[AdminController::class, 'index'])->name('dashboardAdmin');
Route::post('/logout',[AdminController::class, 'logout'])->name('logout.post');

/*Dashboard*/
Route::get('/view', [AdminController::class, 'testingMap'])->name('testingMap');

/*Calendar*/
Route::get('full-calendar', [CalendarController::class, 'index'])->name('full-calendar');
Route::get('full-calendar/Lantai1', [CalendarController::class, 'lantaiSatu'])->name('lantaiSatu');
Route::post('full-calendar/action', [CalendarController::class, 'action']);
Route::post('api/fetch-ruangan', [CalendarController::class, 'fetchruangan']);
Route::post('api/fetch-calendar', [CalendarController::class, 'fetchcalendar'])->name('fetchcalendar');

/*List Reservasi*/
Route::get('/list-reservasi',[ReservasiController::class, 'listReservasi'])->name('listReservasi');
Route::get('/detailreservasi/{id}',[ReservasiController::class, 'detailReservasi'])->name('detail-reservasi');
Route::post('/detailreservasi/{id}/terima',[ReservasiController::class, 'terima'])->name('terimaReservasi');

/*Upload*/
Route::get('/upload-petunjuk',[AdminController::class, 'uploadpetunjuk'])->name('uploadPetunjuk');
Route::post('/upload-petunjuk/post',[AdminController::class, 'uploadpdf'])->name('uploadPDF');
Route::get('/upload-jadwal',[AdminController::class, 'uploadJadwal'])->name('uploadJadwal');

/*Export Import*/
Route::get('file-import-export', [AdminController::class, 'fileImportExport']);
Route::post('file-import', [AdminController::class, 'fileImport'])->name('file-import');
Route::get('file-export', [AdminController::class, 'fileExport'])->name('file-export');

/*Predict People*/
Route::controller(PeopleCounterController::class)->group(function () {
    Route::get('/fastapi', 'fetchAPI');
    Route::post('/uploadImage', 'uploadImage')->name('upload');
    Route::get('/predict/{path}', 'countPeople')->where('path', '.*');
});