<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterKbliController;
use App\Http\Controllers\MasterSbuClassificationController;
use App\Http\Controllers\MasterSbuSchemeController;
use App\Http\Controllers\MasterSbuSubclassificationController;
use App\Http\Controllers\Master\MasterResourceController;
use App\Http\Controllers\Master\MasterDocumentTemplateController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Workspace\CompanyProfileController;
use App\Http\Controllers\Workspace\ApplicationController;
use App\Http\Controllers\Workspace\ApplicationDocumentController;
use App\Http\Controllers\Workspace\GenerateController;
use App\Http\Controllers\Workspace\WorkspaceDashboardController;
use App\Http\Controllers\Workspace\WorkspaceResourceController;
use App\Http\Controllers\Workspace\CompanyPersonController;
use App\Http\Controllers\Workspace\ApplicationExpertController;
use App\Http\Controllers\Workspace\CompanyEquipmentController;
use App\Http\Controllers\Workspace\FinancialStatementController;
use App\Http\Controllers\Workspace\CompanyDocumentController;
use App\Http\Controllers\Workspace\CompanyArchiveController;
use App\Http\Controllers\Workspace\GenerateDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/panduan', GuideController::class)->name('panduan');

    Route::get('/arsip', [CompanyArchiveController::class, 'globalIndex'])->name('archives.global');
    Route::get('/arsip-dokumen/{archive}/view', [CompanyArchiveController::class, 'view'])->name('archives.view');
    Route::get('/arsip-dokumen/{archive}/download', [CompanyArchiveController::class, 'download'])->name('archives.download');
    Route::get('/arsip-dokumen/{archive}/print', [CompanyArchiveController::class, 'print'])->name('archives.print');

    Route::prefix('master')->name('master.')->group(function (): void {
        Route::get('kbli/download-template', [MasterKbliController::class, 'downloadTemplate'])->name('kbli.download-template');
        Route::get('kbli/import', [MasterKbliController::class, 'importForm'])->name('kbli.import-form');
        Route::post('kbli/import', [MasterKbliController::class, 'import'])->name('kbli.import');

        Route::resource('kbli', MasterKbliController::class)
            ->parameters(['kbli' => 'kbli']);
        Route::get('klasifikasi/download-template', [MasterSbuClassificationController::class, 'downloadTemplate'])->name('classifications.download-template');
        Route::get('klasifikasi/import', [MasterSbuClassificationController::class, 'importForm'])->name('classifications.import-form');
        Route::post('klasifikasi/import', [MasterSbuClassificationController::class, 'import'])->name('classifications.import');

        Route::resource('klasifikasi', MasterSbuClassificationController::class)
            ->parameters(['klasifikasi' => 'classification'])
            ->names('classifications');
        Route::get('subklasifikasi/download-template', [MasterSbuSubclassificationController::class, 'downloadTemplate'])->name('subclassifications.download-template');
        Route::get('subklasifikasi/import', [MasterSbuSubclassificationController::class, 'importForm'])->name('subclassifications.import-form');
        Route::post('subklasifikasi/import', [MasterSbuSubclassificationController::class, 'import'])->name('subclassifications.import');

        Route::resource('subklasifikasi', MasterSbuSubclassificationController::class)
            ->parameters(['subklasifikasi' => 'subclassification'])
            ->names('subclassifications');
        Route::get('skema/download-template', [MasterSbuSchemeController::class, 'downloadTemplate'])->name('schemes.download-template');
        Route::get('skema/import', [MasterSbuSchemeController::class, 'importForm'])->name('schemes.import-form');
        Route::post('skema/import', [MasterSbuSchemeController::class, 'import'])->name('schemes.import');

        Route::resource('skema', MasterSbuSchemeController::class)
            ->parameters(['skema' => 'scheme'])
            ->names('schemes');

        $masterRoutes = function (string $uri, string $name): void {
            if (in_array($name, ['science-fields', 'bg-equipment', 'bs-equipment'])) {
                Route::get($uri.'/download-template', [MasterResourceController::class, 'downloadTemplate'])
                    ->defaults('master_resource', $name)
                    ->name($name.'.download-template');
                Route::get($uri.'/import', [MasterResourceController::class, 'importForm'])
                    ->defaults('master_resource', $name)
                    ->name($name.'.import-form');
                Route::post($uri.'/import', [MasterResourceController::class, 'import'])
                    ->defaults('master_resource', $name)
                    ->name($name.'.import');
            }

            Route::get($uri, [MasterResourceController::class, 'index'])
                ->defaults('master_resource', $name)
                ->name($name.'.index');
            Route::get($uri.'/create', [MasterResourceController::class, 'create'])
                ->defaults('master_resource', $name)
                ->name($name.'.create');
            Route::post($uri, [MasterResourceController::class, 'store'])
                ->defaults('master_resource', $name)
                ->name($name.'.store');
            Route::get($uri.'/{item}/edit', [MasterResourceController::class, 'edit'])
                ->defaults('master_resource', $name)
                ->name($name.'.edit');
            Route::put($uri.'/{item}', [MasterResourceController::class, 'update'])
                ->defaults('master_resource', $name)
                ->name($name.'.update');
            Route::delete($uri.'/{item}', [MasterResourceController::class, 'destroy'])
                ->defaults('master_resource', $name)
                ->name($name.'.destroy');
        };

        $masterRoutes('kualifikasi', 'qualifications');
        $masterRoutes('lsbu', 'lsbu');
        $masterRoutes('asosiasi', 'associations');
        $masterRoutes('bidang-keilmuan', 'science-fields');
        $masterRoutes('peralatan-bg', 'bg-equipment');
        $masterRoutes('peralatan-bs', 'bs-equipment');
        $masterRoutes('item-neraca', 'balance-items');

        Route::resource('template-dokumen', MasterDocumentTemplateController::class)
            ->parameters(['template-dokumen' => 'document_template'])
            ->names('document-templates');
    });

    Route::resource('perusahaan', CompanyController::class)
        ->parameters(['perusahaan' => 'company'])
        ->names('companies');

    Route::prefix('perusahaan/{company}/workspace')
        ->name('companies.workspace.')
        ->group(function (): void {
            Route::get('/', WorkspaceDashboardController::class)->name('dashboard');
            Route::get('/profil', [CompanyProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profil', [CompanyProfileController::class, 'update'])->name('profile.update');

            $workspaceRoutes = function (string $uri, string $name): void {
                Route::get($uri, [WorkspaceResourceController::class, 'index'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.index');
                Route::get($uri.'/create', [WorkspaceResourceController::class, 'create'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.create');
                Route::post($uri, [WorkspaceResourceController::class, 'store'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.store');
                Route::get($uri.'/{item}/edit', [WorkspaceResourceController::class, 'edit'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.edit');
                Route::put($uri.'/{item}', [WorkspaceResourceController::class, 'update'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.update');
                Route::delete($uri.'/{item}', [WorkspaceResourceController::class, 'destroy'])
                    ->defaults('workspace_resource', $name)
                    ->name($name.'.destroy');
            };

            Route::get('direktur/create', [CompanyPersonController::class, 'create'])->defaults('type', 'direktur')->name('directors.create');
            Route::post('direktur', [CompanyPersonController::class, 'store'])->defaults('type', 'direktur')->name('directors.store');
            Route::get('direktur/{person}/edit', [CompanyPersonController::class, 'edit'])->name('directors.edit');
            Route::put('direktur/{person}', [CompanyPersonController::class, 'update'])->name('directors.update');
            Route::delete('direktur/{person}', [CompanyPersonController::class, 'destroy'])->name('directors.destroy');

            Route::get('pjbu/create', [CompanyPersonController::class, 'create'])->defaults('type', 'pjbu')->name('pjbus.create');
            Route::post('pjbu', [CompanyPersonController::class, 'store'])->defaults('type', 'pjbu')->name('pjbus.store');
            Route::get('pjbu/{person}/edit', [CompanyPersonController::class, 'edit'])->name('pjbus.edit');
            Route::put('pjbu/{person}', [CompanyPersonController::class, 'update'])->name('pjbus.update');
            Route::delete('pjbu/{person}', [CompanyPersonController::class, 'destroy'])->name('pjbus.destroy');

            Route::get('direktur-pjbu', [WorkspaceResourceController::class, 'directorsPjbu'])->name('directors_pjbus');
            Route::get('direktur', [WorkspaceResourceController::class, 'directorsPjbu'])->name('directors.index');

            Route::resource('pengajuan', ApplicationController::class)
                ->parameters(['pengajuan' => 'application'])
                ->names('applications');

            Route::post('pengajuan/{application}/activate', [ApplicationController::class, 'activate'])->name('applications.activate');
            Route::post('pengajuan/{application}/update-status', [ApplicationController::class, 'updateStatus'])->name('applications.update_status');
            Route::get('pengajuan/{application}/smap/preview', [ApplicationController::class, 'previewSmap'])->name('applications.smap.preview');
            Route::get('pengajuan/{application}/smap/download', [ApplicationController::class, 'downloadSmap'])->name('applications.smap.download');
            Route::get('pengajuan/{application}/sptjm/preview', [ApplicationController::class, 'previewSptjm'])->name('applications.sptjm.preview');
            Route::get('pengajuan/{application}/sptjm/download', [ApplicationController::class, 'downloadSptjm'])->name('applications.sptjm.download');
            Route::get('pengajuan/{application}/lampiran-tenaga-ahli/preview', [ApplicationController::class, 'previewTenagaAhli'])->name('applications.experts_annex.preview');
            Route::get('pengajuan/{application}/lampiran-tenaga-ahli/download', [ApplicationController::class, 'downloadTenagaAhli'])->name('applications.experts_annex.download');
            Route::get('pengajuan/{application}/neraca/preview', [ApplicationController::class, 'previewNeraca'])->name('applications.balance.preview');
            Route::get('pengajuan/{application}/neraca/download', [ApplicationController::class, 'downloadNeraca'])->name('applications.balance.download');
            Route::get('pengajuan/{application}/surat-alat-bg/preview', [ApplicationController::class, 'previewAlatBg'])->name('applications.equip_bg.preview');
            Route::get('pengajuan/{application}/surat-alat-bg/download', [ApplicationController::class, 'downloadAlatBg'])->name('applications.equip_bg.download');
            Route::get('pengajuan/{application}/surat-alat-bs/preview', [ApplicationController::class, 'previewAlatBs'])->name('applications.equip_bs.preview');
            Route::get('pengajuan/{application}/surat-alat-bs/download', [ApplicationController::class, 'downloadAlatBs'])->name('applications.equip_bs.download');

            Route::post('pengajuan/{application}/documents', [ApplicationDocumentController::class, 'upload'])->name('applications.documents.upload');
            Route::delete('pengajuan/{application}/documents/{document}', [ApplicationDocumentController::class, 'destroy'])->name('applications.documents.destroy');
            Route::get('pengajuan/{application}/documents/{document}/download', [ApplicationDocumentController::class, 'download'])->name('applications.documents.download');

            Route::resource('tenaga-ahli', ApplicationExpertController::class)
                ->parameters(['tenaga-ahli' => 'expert'])
                ->names('experts');
            Route::resource('peralatan', CompanyEquipmentController::class)
                ->parameters(['peralatan' => 'equipment'])
                ->names('equipment');
            Route::resource('neraca', FinancialStatementController::class)
                ->parameters(['neraca' => 'statement'])
                ->names('balance');
            Route::resource('dokumen', CompanyDocumentController::class)
                ->parameters(['dokumen' => 'document'])
                ->names('documents');
            Route::get('dokumen/{document}/download', [CompanyDocumentController::class, 'download'])
                ->name('documents.download');

            Route::resource('arsip', CompanyArchiveController::class)
                ->only(['index', 'destroy'])
                ->parameters(['arsip' => 'archive'])
                ->names('archives');

            Route::get('generate', [GenerateController::class, 'index'])->name('generate.index');
            Route::get('generate/preview', [GenerateController::class, 'preview'])->name('generate.preview');
            Route::post('generate/documents', [GenerateController::class, 'processDocuments'])->name('generate.documents.process');
            Route::get('generate/documents/{application}/{document}/preview', [GenerateController::class, 'previewDocument'])->name('generate.documents.preview');
            Route::post('generate/archive', [GenerateController::class, 'saveArchive'])->name('generate.save-archive');
            Route::get('generate/pdf/{application}/{template}/preview', [GenerateDocumentController::class, 'preview'])->name('generate.pdf.preview');
            Route::get('generate/pdf/{application}/{template}/download', [GenerateDocumentController::class, 'download'])->name('generate.pdf.download');
        });

    Route::get('/pengaturan', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/pengaturan', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/pengaturan/profil', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/pengaturan/backup-database', [SettingsController::class, 'backupDatabase'])->name('settings.backup.database');
    Route::post('/pengaturan/backup-storage', [SettingsController::class, 'backupStorage'])->name('settings.backup.storage');
});
