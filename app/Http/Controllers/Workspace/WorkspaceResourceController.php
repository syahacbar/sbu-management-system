<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceResourceController extends Controller
{
    public function directorsPjbu(Request $request, Company $company): View
    {
        $directors = $company->directors()->orderByDesc('is_main')->orderBy('name')->get();
        $pjbus = $company->pjbus()->orderByDesc('is_main')->orderBy('name')->get();

        return view('workspace.directors_pjbu', compact('company', 'directors', 'pjbus'));
    }
}
