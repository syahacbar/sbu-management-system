<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $companies = Company::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('nib', 'like', "%{$search}%")
                        ->orWhere('npwp', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('companies.index', compact('companies', 'search'));
    }

    public function create(): View
    {
        return view('companies.form', [
            'company' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $company = Company::create($this->validated($request));

        return redirect()
            ->route('companies.workspace.dashboard', $company)
            ->with('status', 'Perusahaan berhasil dibuat.');
    }

    public function show(Company $company): RedirectResponse
    {
        return redirect()->route('companies.workspace.dashboard', $company);
    }

    public function edit(Company $company): View
    {
        return view('companies.form', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $company->update($this->validated($request));

        return redirect()
            ->route('companies.workspace.dashboard', $company)
            ->with('status', 'Profil perusahaan berhasil diperbarui.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('status', 'Perusahaan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nib' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
