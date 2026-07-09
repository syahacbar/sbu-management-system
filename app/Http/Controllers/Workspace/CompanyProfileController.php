<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(Company $company): View
    {
        return view('workspace.profile', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $company->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nib' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ]));

        return redirect()
            ->route('companies.workspace.profile.edit', $company)
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }
}
