<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class GenerateController extends Controller
{
    public function __invoke(Company $company): View
    {
        return view('workspace.generate', compact('company'));
    }
}
