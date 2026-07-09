<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Master\MasterEquipment;
use App\Models\Workspace\CompanyEquipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyEquipmentController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();
        
        $category = $request->string('category')->toString();
        $equipments = null;

        if ($activeApplication) {
            $equipments = $company->equipment()
                ->where('sbu_application_id', $activeApplication->id)
                ->when($category !== '', fn ($query) => $query->where('category', $category))
                ->orderBy('category')
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString();
        }

        return view('workspace.equipment.index', compact('company', 'activeApplication', 'equipments', 'category'));
    }

    public function create(Company $company): View|RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.equipment.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif. Silakan aktifkan pengajuan terlebih dahulu.');
        }

        $masterEquipments = MasterEquipment::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('workspace.equipment.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => null,
            'masterEquipments' => $masterEquipments,
            'ownershipStatuses' => $this->ownershipStatuses(),
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.equipment.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif.');
        }

        $validated = $request->validate([
            'master_equipment_id' => ['nullable', 'exists:master_equipments,id'],
            'category' => ['required', 'string', Rule::in(['bg', 'bs'])],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string', 'max:50'],
            'ownership_status' => ['required', 'string', Rule::in(['milik_sendiri', 'sewa', 'pinjam'])],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['company_id'] = $company->id;
        $validated['sbu_application_id'] = $activeApplication->id;

        $company->equipment()->create($validated);

        return redirect()
            ->route('companies.workspace.equipment.index', $company)
            ->with('status', 'Peralatan berhasil ditambahkan ke pengajuan aktif.');
    }

    public function edit(Company $company, CompanyEquipment $equipment): View|RedirectResponse
    {
        if ((int) $equipment->company_id !== (int) $company->id) {
            abort(403);
        }

        $activeApplication = $company->applications()->where('is_active', true)->first();

        $masterEquipments = MasterEquipment::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('workspace.equipment.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => $equipment,
            'masterEquipments' => $masterEquipments,
            'ownershipStatuses' => $this->ownershipStatuses(),
        ]);
    }

    public function show(Company $company, CompanyEquipment $equipment): RedirectResponse
    {
        if ((int) $equipment->company_id !== (int) $company->id) {
            abort(403);
        }

        return redirect()->route('companies.workspace.equipment.edit', [$company, $equipment]);
    }

    public function update(Request $request, Company $company, CompanyEquipment $equipment): RedirectResponse
    {
        if ((int) $equipment->company_id !== (int) $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'master_equipment_id' => ['nullable', 'exists:master_equipments,id'],
            'category' => ['required', 'string', Rule::in(['bg', 'bs'])],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string', 'max:50'],
            'ownership_status' => ['required', 'string', Rule::in(['milik_sendiri', 'sewa', 'pinjam'])],
            'notes' => ['nullable', 'string'],
        ]);

        $equipment->update($validated);

        return redirect()
            ->route('companies.workspace.equipment.index', $company)
            ->with('status', 'Peralatan berhasil diperbarui.');
    }

    public function destroy(Company $company, CompanyEquipment $equipment): RedirectResponse
    {
        if ((int) $equipment->company_id !== (int) $company->id) {
            abort(403);
        }

        $equipment->delete();

        return redirect()
            ->route('companies.workspace.equipment.index', $company)
            ->with('status', 'Peralatan berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function ownershipStatuses(): array
    {
        return [
            'milik_sendiri' => 'Milik Sendiri',
            'sewa' => 'Sewa',
            'pinjam' => 'Pinjam',
        ];
    }
}
