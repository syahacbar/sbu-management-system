<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\CompanyPerson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyPersonController extends Controller
{
    public function create(Request $request, Company $company): View
    {
        $type = $request->route()->defaults['type'] ?? 'direktur';
        
        return view('workspace.persons.form', [
            'company' => $company,
            'person' => null,
            'type' => $type,
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $type = $request->route()->defaults['type'] ?? 'direktur';
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'size:16'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'is_main' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['type'] = $type;
        $validated['nik'] ??= str_pad((string) $company->id, 16, '0', STR_PAD_LEFT);
        $validated['position'] ??= $type === 'direktur' ? 'Direktur' : 'PJBU';
        $validated['is_main'] = $request->boolean('is_main') || $request->boolean('is_active');
        unset($validated['is_active']);

        // Rule: Jika is_main dicentang, maka person lain dengan type yang sama pada perusahaan tersebut menjadi false.
        if ($validated['is_main']) {
            $company->persons()->where('type', $type)->update(['is_main' => false]);
        }

        $company->persons()->create($validated);

        return redirect()
            ->route($type === 'direktur' ? 'companies.workspace.directors.index' : 'companies.workspace.directors_pjbus', $company)
            ->with('status', ($type === 'direktur' ? 'Direktur' : 'PJBU') . ' berhasil ditambahkan.');
    }

    public function edit(Company $company, CompanyPerson $person): View
    {
        if ((int) $person->company_id !== (int) $company->id) {
            abort(404);
        }

        return view('workspace.persons.form', [
            'company' => $company,
            'person' => $person,
            'type' => $person->type,
        ]);
    }

    public function update(Request $request, Company $company, CompanyPerson $person): RedirectResponse
    {
        if ((int) $person->company_id !== (int) $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'size:16'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'is_main' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['nik'] ??= $person->nik;
        $validated['position'] ??= $person->position;
        $validated['is_main'] = $request->boolean('is_main') || $request->boolean('is_active');
        unset($validated['is_active']);

        // Rule: Jika is_main dicentang, maka person lain dengan type yang sama pada perusahaan tersebut menjadi false.
        if ($validated['is_main']) {
            $company->persons()
                ->where('type', $person->type)
                ->where('id', '!=', $person->id)
                ->update(['is_main' => false]);
        }

        $person->update($validated);

        return redirect()
            ->route('companies.workspace.directors_pjbus', $company)
            ->with('status', ($person->type === 'direktur' ? 'Direktur' : 'PJBU') . ' berhasil diperbarui.');
    }

    public function destroy(Company $company, CompanyPerson $person): RedirectResponse
    {
        if ((int) $person->company_id !== (int) $company->id) {
            abort(403);
        }

        $type = $person->type;
        $person->delete();

        return redirect()
            ->route('companies.workspace.directors_pjbus', $company)
            ->with('status', ($type === 'direktur' ? 'Direktur' : 'PJBU') . ' berhasil dihapus.');
    }
}
