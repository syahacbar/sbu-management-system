<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            ['label' => 'Total Perusahaan', 'value' => '0', 'note' => 'Data dummy tahap awal'],
            ['label' => 'Pengajuan Aktif', 'value' => '0', 'note' => 'Belum ada data pengajuan'],
            ['label' => 'Dokumen Siap Generate', 'value' => '0', 'note' => 'Menunggu modul PDF'],
            ['label' => 'Arsip Pengajuan', 'value' => '0', 'note' => 'Belum ada arsip'],
        ];

        return view('dashboard', compact('stats'));
    }
}
