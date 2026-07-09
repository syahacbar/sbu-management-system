<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class WorkspaceDashboardController extends Controller
{
    public function __invoke(Company $company): View
    {
        $stats = [
            ['label' => 'Pengajuan', 'value' => $company->applications()->count()],
            ['label' => 'Tenaga Ahli', 'value' => $company->experts()->count()],
            ['label' => 'Dokumen', 'value' => $company->documents()->count()],
            ['label' => 'Arsip', 'value' => $company->archives()->count()],
        ];

        return view('workspace.dashboard', compact('company', 'stats'));
    }
}
