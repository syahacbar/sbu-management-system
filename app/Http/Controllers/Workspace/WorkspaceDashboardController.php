<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class WorkspaceDashboardController extends Controller
{
    public function __invoke(Company $company): View
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        // 1. Profil Perusahaan Lengkap
        $requiredFields = ['name', 'npwp', 'nib', 'email', 'phone', 'business_type', 'qualification', 'province', 'city', 'district', 'village', 'rt_rw', 'street', 'signing_place'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (empty($company->{$field})) {
                $missingFields[] = str_replace('_', ' ', $field);
            }
        }
        $profileComplete = count($missingFields) === 0;

        // 2. Direktur tersedia
        $hasDirector = $company->directors()->exists();

        // 3. PJBU tersedia
        $hasPjbu = $company->pjbus()->exists();

        // 4. Pengajuan aktif tersedia
        $hasActiveApp = $activeApplication !== null;

        // 5. PJTBU tersedia
        $hasPjtbu = $activeApplication && $activeApplication->experts()->where('expert_type', 'pjtbu')->exists();

        // 6. PJSKBU tersedia
        $hasPjskbu = $activeApplication && $activeApplication->experts()->where('expert_type', 'pjskbu')->exists();

        // 7. Peralatan tersedia
        $hasEquipment = $activeApplication && $company->equipment()->where('sbu_application_id', $activeApplication->id)->exists();

        // 8. Neraca tersedia
        $hasBalance = $activeApplication && $company->balanceEntries()->where('sbu_application_id', $activeApplication->id)->exists();

        // 9. Dokumen pendukung tersedia
        $hasDocuments = $activeApplication && $company->documents()->where('sbu_application_id', $activeApplication->id)->exists();

        // Group checklist items
        $checklist = [
            [
                'key' => 'profile',
                'label' => 'Profil Perusahaan Lengkap',
                'is_complete' => $profileComplete,
                'desc' => $profileComplete ? 'Seluruh identitas dan alamat kantor terisi.' : 'Kolom kosong: ' . implode(', ', $missingFields),
            ],
            [
                'key' => 'director',
                'label' => 'Direktur Tersedia',
                'is_complete' => $hasDirector,
                'desc' => $hasDirector ? 'Minimal 1 Direktur utama/anggota terdaftar.' : 'Belum ada data Direktur utama atau anggota.',
            ],
            [
                'key' => 'pjbu',
                'label' => 'PJBU Tersedia',
                'is_complete' => $hasPjbu,
                'desc' => $hasPjbu ? 'Minimal 1 Penanggung Jawab Badan Usaha terdaftar.' : 'Belum ada data PJBU terdaftar.',
            ],
            [
                'key' => 'active_app',
                'label' => 'Pengajuan Aktif Tersedia',
                'is_complete' => $hasActiveApp,
                'desc' => $hasActiveApp ? 'Terdapat 1 pengajuan SBU aktif saat ini.' : 'Belum ada pengajuan SBU yang berstatus Aktif.',
            ],
            [
                'key' => 'pjtbu',
                'label' => 'Tenaga Ahli PJTBU Tersedia',
                'is_complete' => $hasPjtbu,
                'desc' => $hasPjtbu ? 'Minimal 1 PJTBU terdaftar pada pengajuan aktif.' : 'Belum ada Tenaga Ahli tipe PJTBU pada pengajuan aktif.',
            ],
            [
                'key' => 'pjskbu',
                'label' => 'Tenaga Ahli PJSKBU Tersedia',
                'is_complete' => $hasPjskbu,
                'desc' => $hasPjskbu ? 'Minimal 1 PJSKBU terdaftar pada pengajuan aktif.' : 'Belum ada Tenaga Ahli tipe PJSKBU pada pengajuan aktif.',
            ],
            [
                'key' => 'equipment',
                'label' => 'Peralatan Tersedia',
                'is_complete' => $hasEquipment,
                'desc' => $hasEquipment ? 'Minimal 1 peralatan terdaftar pada pengajuan aktif.' : 'Belum ada peralatan (BG/BS) pada pengajuan aktif.',
            ],
            [
                'key' => 'balance',
                'label' => 'Neraca Keuangan Tersedia',
                'is_complete' => $hasBalance,
                'desc' => $hasBalance ? 'Minimal 1 laporan neraca keuangan terdaftar.' : 'Belum ada laporan neraca pada pengajuan aktif.',
            ],
            [
                'key' => 'documents',
                'label' => 'Dokumen Pendukung Tersedia',
                'is_complete' => $hasDocuments,
                'desc' => $hasDocuments ? 'Minimal 1 dokumen pendukung berhasil diunggah.' : 'Belum ada berkas dokumen pendukung terunggah pada pengajuan aktif.',
            ],
        ];

        $allComplete = $profileComplete && $hasDirector && $hasPjbu && $hasActiveApp && $hasPjtbu && $hasPjskbu && $hasEquipment && $hasBalance && $hasDocuments;

        $mainDirector = $company->directors()->where('is_main', true)->first();
        $mainPjbu = $company->pjbus()->where('is_main', true)->first();
        $uploadedDocumentsCount = $activeApplication
            ? $company->documents()->where('sbu_application_id', $activeApplication->id)->count()
            : 0;
        $generatedDocumentsCount = $activeApplication
            ? $company->archives()->where('sbu_application_id', $activeApplication->id)->count()
            : $company->archives()->count();
        $completedChecklistCount = collect($checklist)->where('is_complete', true)->count();
        $totalChecklistCount = count($checklist);

        return view('workspace.dashboard', compact(
            'company',
            'activeApplication',
            'checklist',
            'allComplete',
            'mainDirector',
            'mainPjbu',
            'uploadedDocumentsCount',
            'generatedDocumentsCount',
            'completedChecklistCount',
            'totalChecklistCount'
        ));
    }
}
