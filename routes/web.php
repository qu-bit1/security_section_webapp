<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AttachmentController;
use \App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RemarkController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix("manage")->group(function (){
    Route::resource('roles', RoleController::class);
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post("reports/approve/{report}", [ReportController::class, 'approveOne'])->name('reports.approveOne');
    Route::post("reports/mass-approve", [ReportController::class, 'massApprove'])->name('reports.massApprove');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::delete('reports/mass-destroy', [ReportController::class, 'massDestroy'])->name('reports.massDestroy');
    Route::resource('reports', ReportController::class);
    Route::resource('reports.remarks', RemarkController::class)->shallow();
    Route::resource('reports.comments', CommentController::class)->shallow();

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::patch('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');

    Route::resource('attachments', AttachmentController::class);
});

require __DIR__.'/auth.php';
