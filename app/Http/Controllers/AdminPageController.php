<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminPageController extends Controller
{
    public function __invoke(string $page): View
    {
        $titles = [
            'master.kbli' => 'KBLI',
            'master.classifications' => 'Klasifikasi',
            'master.subclassifications' => 'Subklasifikasi',
            'master.schemes' => 'Skema',
            'master.qualifications' => 'Kualifikasi',
            'master.lsbu' => 'LSBU',
            'master.associations' => 'Asosiasi',
            'master.science-fields' => 'Bidang Keilmuan',
            'master.bg-equipment' => 'Peralatan BG',
            'master.bs-equipment' => 'Peralatan BS',
            'master.balance-items' => 'Item Neraca',
            'master.document-templates' => 'Template Dokumen',
            'companies' => 'Perusahaan',
            'settings' => 'Pengaturan',
        ];

        abort_unless(array_key_exists($page, $titles), 404);

        return view('admin.placeholder', [
            'title' => $titles[$page],
        ]);
    }
}
