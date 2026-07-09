<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Models\MasterSbuScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $items = $company->applications()
            ->with(['kbli', 'classification', 'subclassification', 'scheme'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderBy('sort_order')
            ->orderByDesc('record_date')
            ->paginate(10)
            ->withQueryString();

        return view('workspace.applications.index', compact('company', 'items', 'search', 'status'));
    }

    public function create(Company $company): View
    {
        return view('workspace.applications.form', [
            'company' => $company,
            'item' => null,
            ...$this->formOptions()
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $validated = $this->validateAndCheckRelations($request);
        $company->applications()->create($validated);

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil dibuat.');
    }

    public function show(Company $company, Application $application): View
    {
        $application->load(['kbli', 'classification', 'subclassification', 'scheme']);
        return view('workspace.applications.show', compact('company', 'application'));
    }

    public function edit(Company $company, Application $application): View
    {
        return view('workspace.applications.form', [
            'company' => $company,
            'item' => $application,
            ...$this->formOptions()
        ]);
    }

    public function update(Request $request, Company $company, Application $application): RedirectResponse
    {
        $validated = $this->validateAndCheckRelations($request, $application);
        $application->update($validated);

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil diperbarui.');
    }

    public function destroy(Company $company, Application $application): RedirectResponse
    {
        $application->delete();

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'kblis' => MasterKbli::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'classifications' => MasterSbuClassification::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'subclassifications' => MasterSbuSubclassification::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'schemes' => MasterSbuScheme::where('is_active', true)->orderBy('sort_order')->orderBy('scheme_code')->get(),
            'statuses' => ['draft' => 'Draft', 'review' => 'Review', 'approved' => 'Disetujui', 'rejected' => 'Ditolak']
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateAndCheckRelations(Request $request, ?Application $application = null): array
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'master_kbli_id' => ['required', 'exists:master_kblis,id'],
            'master_sbu_classification_id' => ['required', 'exists:master_sbu_classifications,id'],
            'master_sbu_subclassification_id' => ['required', 'exists:master_sbu_subclassifications,id'],
            'master_sbu_scheme_id' => ['required', 'exists:master_sbu_schemes,id'],
            'status' => ['required', 'string', Rule::in(['draft', 'review', 'approved', 'rejected'])],
            'record_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        // Cross-check relation Classification <-> Subclassification
        $subMatches = MasterSbuSubclassification::query()
            ->whereKey($validated['master_sbu_subclassification_id'])
            ->where('master_sbu_classification_id', $validated['master_sbu_classification_id'])
            ->exists();

        if (!$subMatches) {
            throw ValidationException::withMessages([
                'master_sbu_subclassification_id' => 'Subklasifikasi yang dipilih tidak sesuai dengan Klasifikasi SBU.',
            ]);
        }

        // Cross-check relation Scheme <-> KBLI, Classification, Subclassification
        $schemeMatches = MasterSbuScheme::query()
            ->whereKey($validated['master_sbu_scheme_id'])
            ->where('master_kbli_id', $validated['master_kbli_id'])
            ->where('master_sbu_classification_id', $validated['master_sbu_classification_id'])
            ->where('master_sbu_subclassification_id', $validated['master_sbu_subclassification_id'])
            ->exists();

        if (!$schemeMatches) {
            throw ValidationException::withMessages([
                'master_sbu_scheme_id' => 'Skema SBU yang dipilih tidak sesuai dengan KBLI, Klasifikasi, dan Subklasifikasi.',
            ]);
        }

        return $validated;
    }
}
