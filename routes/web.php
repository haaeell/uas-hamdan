<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ClassDistributionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamMonitoringController;
use App\Http\Controllers\Admin\OwnerApprovalController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PsychologyQuestionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TestSessionController;
use App\Http\Controllers\Admin\TestResultController;
use App\Http\Controllers\Admin\AdminViolationController;
use App\Http\Controllers\RedirectController;

use App\Http\Controllers\Siswa\AnnouncementController as SiswaAnnouncementController;
use App\Http\Controllers\Siswa\PsychologyTestController;
use App\Http\Controllers\Siswa\ViolationController;
use App\Http\Controllers\Siswa\WizardController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('redirect.after.login');
    }

    return redirect()->route('login');
});

Auth::routes([
    'register' => true,
    'reset' => false,
    'verify' => false,
]);

Route::get('/uji/{token}', function (string $token) {
    $owner = \App\Models\User::where('role', 'owner')
        ->where('is_active', true)
        ->where('exam_token', $token)
        ->firstOrFail();

    session(['exam_owner_id' => $owner->id]);

    return redirect()->route('login')->with('success', 'Link ujian aktif untuk ' . $owner->name . '.');
})->name('owner.exam-link');

Route::get('/redirect-after-login', RedirectController::class)
    ->middleware('auth')
    ->name('redirect.after.login');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,owner'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/dashboard/reset-data', [DashboardController::class, 'resetData'])
            ->name('dashboard.reset-data');

        Route::middleware('role:admin')->group(function () {
            Route::get('/owner-approvals', [OwnerApprovalController::class, 'index'])
                ->name('owner-approvals.index');

            Route::post('/owner-approvals/{owner}/approve', [OwnerApprovalController::class, 'approve'])
                ->name('owner-approvals.approve');

            Route::post('/owner-approvals/{owner}/activate', [OwnerApprovalController::class, 'activate'])
                ->name('owner-approvals.activate');

            Route::post('/owner-approvals/{owner}/deactivate', [OwnerApprovalController::class, 'deactivate'])
                ->name('owner-approvals.deactivate');
        });

        Route::middleware('role:owner')->group(function () {
            Route::get('/exam-monitoring', [ExamMonitoringController::class, 'index'])
                ->name('exam-monitoring.index');

            /*
            |--------------------------------------------------------------------------
            | MASTER SISWA
            |--------------------------------------------------------------------------
            */
            Route::get('/students/data', [StudentController::class, 'data'])->name('students.data');
            Route::resource('/students', StudentController::class);
            Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
            Route::get('/students/export/excel', [StudentController::class, 'export'])->name('students.export');
            Route::get('/students/template/download', [StudentController::class, 'downloadTemplate'])->name('students.template');
            Route::post('/students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');

            Route::post('/students/bulk-activate', [StudentController::class, 'bulkActivate'])
                ->name('students.bulk-activate');

            Route::post('/students/bulk-deactivate', [StudentController::class, 'bulkDeactivate'])
                ->name('students.bulk-deactivate');

            /*
            |--------------------------------------------------------------------------
            | MASTER JURUSAN
            |--------------------------------------------------------------------------
            */
            Route::resource('/packages', PackageController::class);

            Route::post('/packages/{package}/subjects', [PackageController::class, 'storeSubject'])
                ->name('packages.subjects.store');

            Route::delete('/packages/{package}/subjects/{subject}', [PackageController::class, 'destroySubject'])
                ->name('packages.subjects.destroy');

            /*
            |--------------------------------------------------------------------------
            | SESI TES
            |--------------------------------------------------------------------------
            */
            Route::resource('/test-sessions', TestSessionController::class);

            Route::post('/test-sessions/{testSession}/classes', [TestSessionController::class, 'storeClass'])
                ->name('test-sessions.classes.store');

            Route::delete('/test-sessions/{testSession}/classes/{classId}', [TestSessionController::class, 'destroyClass'])
                ->name('test-sessions.classes.destroy');

            /*
            |--------------------------------------------------------------------------
            | SOAL INSTRUMEN PEMINATAN
            |--------------------------------------------------------------------------
            */
            Route::delete('/psychology-questions/destroy-all', [PsychologyQuestionController::class, 'destroyAll'])
                ->name('psychology-questions.destroy-all');

            Route::resource('/psychology-questions', PsychologyQuestionController::class);

            Route::post('/psychology-questions/import', [PsychologyQuestionController::class, 'import'])
                ->name('psychology-questions.import');

            Route::get('/psychology-questions/export/excel', [PsychologyQuestionController::class, 'export'])
                ->name('psychology-questions.export');

            Route::get('/psychology-questions/template/download', [PsychologyQuestionController::class, 'downloadTemplate'])
                ->name('psychology-questions.template');

            Route::post('/psychology-questions/bulk-delete', [PsychologyQuestionController::class, 'bulkDelete'])
                ->name('psychology-questions.bulk-delete');

            /*
            |--------------------------------------------------------------------------
            | DISTRIBUSI KELAS
            |--------------------------------------------------------------------------
            */
            Route::get('/class-distribution', [ClassDistributionController::class, 'index'])
                ->name('class-distribution.index');

            Route::post('/class-distribution/classes', [ClassDistributionController::class, 'store'])
                ->name('class-distribution.classes.store');

            Route::put('/class-distribution/classes/{classGroup}', [ClassDistributionController::class, 'update'])
                ->name('class-distribution.classes.update');

            Route::delete('/class-distribution/classes/{classGroup}', [ClassDistributionController::class, 'destroy'])
                ->name('class-distribution.classes.destroy');

            Route::post('/class-distribution/run', [ClassDistributionController::class, 'run'])
                ->name('class-distribution.run');

            Route::post('/class-distribution/manual-move', [ClassDistributionController::class, 'manualMove'])
                ->name('class-distribution.manual-move');

            Route::post('/class-distribution/lock', [ClassDistributionController::class, 'lock'])
                ->name('class-distribution.lock');

            /*
            |--------------------------------------------------------------------------
            | PENGUMUMAN
            |--------------------------------------------------------------------------
            */
            Route::resource('/announcements', AnnouncementController::class);

            Route::post('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])
                ->name('announcements.publish');

            Route::post('/announcements/{announcement}/lock-final', [AnnouncementController::class, 'lockFinal'])
                ->name('announcements.lock-final');

            Route::get('/test-results', [TestResultController::class, 'index'])->name('test-results.index');
            Route::get('/test-results/data', [TestResultController::class, 'data'])->name('test-results.data');
            Route::post('/test-results/manual-update', [TestResultController::class, 'manualUpdate'])->name('test-results.manual-update');
            Route::get('/test-results/export', [TestResultController::class, 'export'])->name('test-results.export');

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('reports.index');

            Route::get('/reports/{type}/excel', [ReportController::class, 'exportExcel'])
                ->name('reports.excel');

            Route::get('/reports/{type}/pdf', [ReportController::class, 'exportPdf'])
                ->name('reports.pdf');

            Route::get('/violations', [AdminViolationController::class, 'index'])
                ->name('violations.index');
        });

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | WIZARD
        |--------------------------------------------------------------------------
        */
        Route::get('/wizard', [WizardController::class, 'index'])
            ->name('wizard.index');

        Route::post('/wizard/biodata', [WizardController::class, 'saveBiodata'])
            ->name('wizard.biodata');

        Route::post('/wizard/package-choice', [WizardController::class, 'savePackageChoice'])
            ->name('wizard.package-choice');

        Route::get('/waiting-session', [WizardController::class, 'waitingSession'])
            ->name('waiting-session');

        /*
        |--------------------------------------------------------------------------
        | CBT PSIKOLOGI
        |--------------------------------------------------------------------------
        */
        Route::middleware('test.session.open')->group(function () {
            Route::get('/psychology-test', [PsychologyTestController::class, 'index'])
                ->name('psychology.index');

            Route::post('/psychology-test/autosave', [PsychologyTestController::class, 'autosave'])
                ->name('psychology.autosave');

            Route::post('/psychology-test/submit', [PsychologyTestController::class, 'submit'])
                ->name('psychology.submit');

            Route::post('/violations', [ViolationController::class, 'store'])
                ->name('violations.store');
        });

        /*
        |--------------------------------------------------------------------------
        | PENGUMUMAN SISWA
        |--------------------------------------------------------------------------
        */
        Route::get('/announcements', [SiswaAnnouncementController::class, 'index'])
            ->name('announcements.index');

        Route::post('/announcements/{announcement}/accept', [SiswaAnnouncementController::class, 'accept'])
            ->name('announcements.accept');

    });
