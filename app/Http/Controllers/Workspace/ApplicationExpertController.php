<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\ApplicationExpert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationExpertController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();
        
        $type = $request->string('type')->toString();
        $experts = null;

        if ($activeApplication) {
            $experts = $activeApplication->experts()
                ->when($type !== '', fn ($query) => $query->where('expert_type', $type))
                ->orderBy('expert_type')
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString();
        }

        return view('workspace.experts.index', compact('company', 'activeApplication', 'experts', 'type'));
    }

    public function create(Company $company): View|RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.experts.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif. Silakan aktifkan pengajuan terlebih dahulu.');
        }

        return view('workspace.experts.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => null,
            'types' => $this->expertTypes(),
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.experts.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif.');
        }

        $validated = $request->validate([
            'expert_type' => ['required', 'string', Rule::in(['pjtbu', 'pjskbu', 'tenaga_ahli'])],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'skk_registration_number' => ['nullable', 'string', 'max:100'],
            'skk_classification' => ['nullable', 'string', 'max:255'],
            'skk_subclassification' => ['nullable', 'string', 'max:255'],
            'skk_qualification' => ['nullable', 'string', 'max:255'],
            'skk_level' => ['nullable', 'string', 'max:100'],
            'skk_issued_at' => ['nullable', 'date'],
            'skk_expired_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $activeApplication->experts()->create($validated);

        return redirect()
            ->route('companies.workspace.experts.index', $company)
            ->with('status', 'Tenaga Ahli berhasil ditambahkan ke pengajuan aktif.');
    }

    public function edit(Company $company, ApplicationExpert $expert): View|RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication || (int) $expert->sbu_application_id !== (int) $activeApplication->id) {
            abort(403);
        }

        return view('workspace.experts.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => $expert,
            'types' => $this->expertTypes(),
        ]);
    }

    public function show(Company $company, ApplicationExpert $expert): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication || (int) $expert->sbu_application_id !== (int) $activeApplication->id) {
            abort(403);
        }

        return redirect()->route('companies.workspace.experts.edit', [$company, $expert]);
    }

    public function update(Request $request, Company $company, ApplicationExpert $expert): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication || (int) $expert->sbu_application_id !== (int) $activeApplication->id) {
            abort(403);
        }

        $validated = $request->validate([
            'expert_type' => ['required', 'string', Rule::in(['pjtbu', 'pjskbu', 'tenaga_ahli'])],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'skk_registration_number' => ['nullable', 'string', 'max:100'],
            'skk_classification' => ['nullable', 'string', 'max:255'],
            'skk_subclassification' => ['nullable', 'string', 'max:255'],
            'skk_qualification' => ['nullable', 'string', 'max:255'],
            'skk_level' => ['nullable', 'string', 'max:100'],
            'skk_issued_at' => ['nullable', 'date'],
            'skk_expired_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $expert->update($validated);

        return redirect()
            ->route('companies.workspace.experts.index', $company)
            ->with('status', 'Tenaga Ahli berhasil diperbarui.');
    }

    public function destroy(Company $company, ApplicationExpert $expert): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication || (int) $expert->sbu_application_id !== (int) $activeApplication->id) {
            abort(403);
        }

        $expert->delete();

        return redirect()
            ->route('companies.workspace.experts.index', $company)
            ->with('status', 'Tenaga Ahli berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function expertTypes(): array
    {
        return [
            'pjtbu' => 'PJTBU (Penanggung Jawab Teknis Badan Usaha)',
            'pjskbu' => 'PJSKBU (Penanggung Jawab Subklasifikasi Badan Usaha)',
            'tenaga_ahli' => 'Tenaga Ahli / Ahli Muda/Madya/Utama',
        ];
    }
}
