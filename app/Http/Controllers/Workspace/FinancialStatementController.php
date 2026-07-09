<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Master\MasterFinancialItem;
use App\Models\Workspace\FinancialStatement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialStatementController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();
        $statements = null;

        if ($activeApplication) {
            $statements = $company->balanceEntries() // balanceEntries references FinancialStatement::class
                ->where('sbu_application_id', $activeApplication->id)
                ->orderByDesc('year_two')
                ->paginate(10);
        }

        return view('workspace.balance.index', compact('company', 'activeApplication', 'statements'));
    }

    public function create(Company $company): View|RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.balance.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif. Silakan aktifkan pengajuan terlebih dahulu.');
        }

        $masterItems = MasterFinancialItem::where('is_active', true)
            ->orderBy('section')
            ->orderBy('group_name')
            ->orderBy('sort_order')
            ->get();

        return view('workspace.balance.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => null,
            'masterItems' => $masterItems,
            'values' => collect(),
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.balance.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif.');
        }

        $request->validate([
            'year_two' => ['required', 'integer', 'min:2000', 'max:2100'],
            'year_one' => ['required', 'integer', 'min:2000', 'max:2100'],
            'statement_date' => ['required', 'date'],
            'items' => ['required', 'array'],
        ]);

        $statement = $company->balanceEntries()->create([
            'sbu_application_id' => $activeApplication->id,
            'year_one' => $request->integer('year_one'),
            'year_two' => $request->integer('year_two'),
            'statement_date' => $request->date('statement_date'),
        ]);

        $inputableItems = MasterFinancialItem::where('is_calculated', false)
            ->where('is_active', true)
            ->get();

        foreach ($inputableItems as $item) {
            $valOne = $request->input("items.{$item->id}.year_one_amount", 0);
            $valTwo = $request->input("items.{$item->id}.year_two_amount", 0);

            $statement->values()->create([
                'master_financial_item_id' => $item->id,
                'year_one_amount' => $this->cleanAmount($valOne),
                'year_two_amount' => $this->cleanAmount($valTwo),
            ]);
        }

        return redirect()
            ->route('companies.workspace.balance.index', $company)
            ->with('status', 'Laporan Neraca Keuangan berhasil ditambahkan.');
    }

    public function edit(Company $company, FinancialStatement $statement): View|RedirectResponse
    {
        if ((int) $statement->company_id !== (int) $company->id) {
            abort(403);
        }

        $activeApplication = $company->applications()->where('is_active', true)->first();

        $masterItems = MasterFinancialItem::where('is_active', true)
            ->orderBy('section')
            ->orderBy('group_name')
            ->orderBy('sort_order')
            ->get();

        $values = $statement->values->keyBy('master_financial_item_id');

        return view('workspace.balance.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => $statement,
            'masterItems' => $masterItems,
            'values' => $values,
        ]);
    }

    public function show(Company $company, FinancialStatement $statement): RedirectResponse
    {
        if ((int) $statement->company_id !== (int) $company->id) {
            abort(403);
        }

        return redirect()->route('companies.workspace.balance.edit', [$company, $statement]);
    }

    public function update(Request $request, Company $company, FinancialStatement $statement): RedirectResponse
    {
        if ((int) $statement->company_id !== (int) $company->id) {
            abort(403);
        }

        $request->validate([
            'year_two' => ['required', 'integer', 'min:2000', 'max:2100'],
            'year_one' => ['required', 'integer', 'min:2000', 'max:2100'],
            'statement_date' => ['required', 'date'],
            'items' => ['required', 'array'],
        ]);

        $statement->update([
            'year_one' => $request->integer('year_one'),
            'year_two' => $request->integer('year_two'),
            'statement_date' => $request->date('statement_date'),
        ]);

        $inputableItems = MasterFinancialItem::where('is_calculated', false)
            ->where('is_active', true)
            ->get();

        foreach ($inputableItems as $item) {
            $valOne = $request->input("items.{$item->id}.year_one_amount", 0);
            $valTwo = $request->input("items.{$item->id}.year_two_amount", 0);

            $statement->values()->updateOrCreate(
                ['master_financial_item_id' => $item->id],
                [
                    'year_one_amount' => $this->cleanAmount($valOne),
                    'year_two_amount' => $this->cleanAmount($valTwo),
                ]
            );
        }

        return redirect()
            ->route('companies.workspace.balance.index', $company)
            ->with('status', 'Laporan Neraca Keuangan berhasil diperbarui.');
    }

    public function destroy(Company $company, FinancialStatement $statement): RedirectResponse
    {
        if ((int) $statement->company_id !== (int) $company->id) {
            abort(403);
        }

        $statement->delete();

        return redirect()
            ->route('companies.workspace.balance.index', $company)
            ->with('status', 'Laporan Neraca Keuangan berhasil dihapus.');
    }

    /**
     * Clean formatting characters from amount input (if any)
     * @param mixed $value
     */
    private function cleanAmount($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        if (is_string($value)) {
            // Strip Rp, dots, space, commas
            $clean = str_replace(['Rp', '.', ' '], '', $value);
            $clean = str_replace(',', '.', $clean);
            return (float) $clean;
        }

        return 0;
    }
}
