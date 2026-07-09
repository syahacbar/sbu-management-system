<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\Workspace\GeneratedDocument;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            ['label' => 'Total Perusahaan', 'value' => Company::count(), 'note' => 'Badan usaha terdaftar'],
            ['label' => 'Total Pengajuan', 'value' => Application::count(), 'note' => 'Semua status pengajuan'],
            ['label' => 'Pengajuan Draft', 'value' => Application::where('status', 'draft')->count(), 'note' => 'Masih dalam penyusunan'],
            ['label' => 'Pengajuan Selesai', 'value' => Application::where('status', 'selesai')->count(), 'note' => 'Sudah selesai diproses'],
            ['label' => 'Dokumen Generated', 'value' => GeneratedDocument::count(), 'note' => 'Tersimpan di arsip'],
        ];

        $latestCompanies = Company::query()
            ->withCount(['applications', 'archives'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'latestCompanies'));
    }
}
