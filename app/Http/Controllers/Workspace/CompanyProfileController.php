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
            'npwp' => ['nullable', 'string', 'max:100'],
            'nib' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'rt_rw' => ['nullable', 'string', 'max:100'],
            'street' => ['nullable', 'string', 'max:255'],
            'signing_place' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]));

        return redirect()
            ->route('companies.workspace.profile.edit', $company)
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }
}
