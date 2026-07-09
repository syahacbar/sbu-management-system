<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\Workspace\BalanceEntry;
use App\Models\Workspace\Director;
use App\Models\Workspace\Document;
use App\Models\Workspace\Equipment;
use App\Models\Workspace\Expert;
use App\Models\Workspace\Pjbu;
use App\Models\Workspace\Pjskbu;
use App\Models\Workspace\Pjtbu;
use App\Models\Workspace\WorkspaceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceResourceController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $resource = $this->resource($request);
        $search = $request->string('search')->toString();
        $relation = $resource['relation'];

        $items = $company->{$relation}()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('record_date')
            ->paginate(10)
            ->withQueryString();

        return view('workspace.resources.index', compact('company', 'items', 'resource', 'search'));
    }

    public function create(Request $request, Company $company): View
    {
        return view('workspace.resources.form', [
            'company' => $company,
            'item' => null,
            'resource' => $this->resource($request),
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $resource = $this->resource($request);
        $relation = $resource['relation'];
        $company->{$relation}()->create($this->validated($request));

        $redirectRoute = in_array($resource['key'], ['directors', 'pjbus']) 
            ? 'companies.workspace.directors_pjbus' 
            : $resource['route'].'.index';

        return redirect()
            ->route($redirectRoute, $company)
            ->with('status', "{$resource['title']} berhasil ditambahkan.");
    }

    public function edit(Request $request, Company $company, int $item): View
    {
        $resource = $this->resource($request);

        return view('workspace.resources.form', [
            'company' => $company,
            'item' => $this->findItem($company, $resource, $item),
            'resource' => $resource,
        ]);
    }

    public function update(Request $request, Company $company, int $item): RedirectResponse
    {
        $resource = $this->resource($request);
        $model = $this->findItem($company, $resource, $item);
        $model->update($this->validated($request));

        $redirectRoute = in_array($resource['key'], ['directors', 'pjbus']) 
            ? 'companies.workspace.directors_pjbus' 
            : $resource['route'].'.index';

        return redirect()
            ->route($redirectRoute, $company)
            ->with('status', "{$resource['title']} berhasil diperbarui.");
    }

    public function destroy(Request $request, Company $company, int $item): RedirectResponse
    {
        $resource = $this->resource($request);
        $this->findItem($company, $resource, $item)->delete();

        $redirectRoute = in_array($resource['key'], ['directors', 'pjbus']) 
            ? 'companies.workspace.directors_pjbus' 
            : $resource['route'].'.index';

        return redirect()
            ->route($redirectRoute, $company)
            ->with('status', "{$resource['title']} berhasil dihapus.");
    }

    public function directorsPjbu(Request $request, Company $company): View
    {
        $directors = $company->directors()->orderByDesc('is_main')->orderBy('name')->get();
        $pjbus = $company->pjbus()->orderByDesc('is_main')->orderBy('name')->get();

        return view('workspace.directors_pjbu', compact('company', 'directors', 'pjbus'));
    }

    /**
     * @return array{key: string, title: string, route: string, relation: string, model: class-string<WorkspaceRecord>}
     */
    private function resource(Request $request): array
    {
        $key = (string) $request->route('workspace_resource');
        $resources = $this->resources();

        abort_unless(array_key_exists($key, $resources), 404);

        return ['key' => $key, ...$resources[$key]];
    }

    /**
     * @return array<string, array{title: string, route: string, relation: string, model: class-string<WorkspaceRecord>}>
     */
    private function resources(): array
    {
        return [
            'directors' => ['title' => 'Direktur', 'route' => 'companies.workspace.directors', 'relation' => 'directors', 'model' => Director::class],
            'pjbus' => ['title' => 'PJBU', 'route' => 'companies.workspace.pjbus', 'relation' => 'pjbus', 'model' => Pjbu::class],
            'pjtbus' => ['title' => 'PJTBU', 'route' => 'companies.workspace.pjtbus', 'relation' => 'pjtbus', 'model' => Pjtbu::class],
            'pjskbus' => ['title' => 'PJSKBU', 'route' => 'companies.workspace.pjskbus', 'relation' => 'pjskbus', 'model' => Pjskbu::class],
            'experts' => ['title' => 'Tenaga Ahli', 'route' => 'companies.workspace.experts', 'relation' => 'experts', 'model' => Expert::class],
            'equipment' => ['title' => 'Peralatan', 'route' => 'companies.workspace.equipment', 'relation' => 'equipment', 'model' => Equipment::class],
            'balance' => ['title' => 'Neraca', 'route' => 'companies.workspace.balance', 'relation' => 'balanceEntries', 'model' => BalanceEntry::class],
            'documents' => ['title' => 'Dokumen', 'route' => 'companies.workspace.documents', 'relation' => 'documents', 'model' => Document::class],
        ];
    }

    /**
     * @param array{relation: string} $resource
     */
    private function findItem(Company $company, array $resource, int $id): WorkspaceRecord
    {
        $relation = $resource['relation'];

        return $company->{$relation}()->whereKey($id)->firstOrFail();
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'code' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:100'],
            'record_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
