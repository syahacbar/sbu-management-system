<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class GuideController extends Controller
{
    public function __invoke()
    {
        return view('panduan.index', [
            'appName' => Setting::get('app_name', 'Sistem Manajemen Pengajuan SBU'),
            'companyName' => Setting::get('app_company_name', 'Internal Admin'),
        ]);
    }
}
