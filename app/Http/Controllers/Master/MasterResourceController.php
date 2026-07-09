<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Association;
use App\Models\Master\BalanceItem;
use App\Models\Master\BgEquipment;
use App\Models\Master\BsEquipment;
use App\Models\Master\Classification;
use App\Models\Master\DocumentTemplate;
use App\Models\Master\Kbli;
use App\Models\Master\Lsbu;
use App\Models\Master\MasterReference;
use App\Models\Master\Qualification;
use App\Models\Master\Scheme;
use App\Models\Master\ScienceField;
use App\Models\Master\Subclassification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MasterResourceController extends Controller
{
    public function index(Request $request): View
    {
        $resource = $this->resource($request);
        $search = $request->string('search')->toString();

        $items = $resource['model']::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.index', compact('items', 'resource', 'search'));
    }

    public function create(Request $request): View
    {
        return view('master.form', [
            'item' => null,
            'resource' => $this->resource($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $resource = $this->resource($request);
        $resource['model']::create($this->validated($request, $resource));

        return redirect()
            ->route($resource['route'].'.index')
            ->with('status', "{$resource['title']} berhasil ditambahkan.");
    }

    public function edit(Request $request, int $item): View
    {
        $resource = $this->resource($request);

        return view('master.form', [
            'item' => $this->findItem($resource, $item),
            'resource' => $resource,
        ]);
    }

    public function update(Request $request, int $item): RedirectResponse
    {
        $resource = $this->resource($request);
        $model = $this->findItem($resource, $item);
        $model->update($this->validated($request, $resource, $model));

        return redirect()
            ->route($resource['route'].'.index')
            ->with('status', "{$resource['title']} berhasil diperbarui.");
    }

    public function destroy(Request $request, int $item): RedirectResponse
    {
        $resource = $this->resource($request);
        $this->findItem($resource, $item)->delete();

        return redirect()
            ->route($resource['route'].'.index')
            ->with('status', "{$resource['title']} berhasil dihapus.");
    }

    /**
     * @return array{key: string, title: string, route: string, model: class-string<MasterReference>}
     */
    private function resource(Request $request): array
    {
        $key = (string) $request->route('master_resource');
        $resources = $this->resources();

        abort_unless(array_key_exists($key, $resources), 404);

        return ['key' => $key, ...$resources[$key]];
    }

    /**
     * @return array<string, array{title: string, route: string, model: class-string<MasterReference>}>
     */
    private function resources(): array
    {
        return [
            'kbli' => ['title' => 'KBLI', 'route' => 'master.kbli', 'model' => Kbli::class],
            'classifications' => ['title' => 'Klasifikasi', 'route' => 'master.classifications', 'model' => Classification::class],
            'subclassifications' => ['title' => 'Subklasifikasi', 'route' => 'master.subclassifications', 'model' => Subclassification::class],
            'schemes' => ['title' => 'Skema', 'route' => 'master.schemes', 'model' => Scheme::class],
            'qualifications' => ['title' => 'Kualifikasi', 'route' => 'master.qualifications', 'model' => Qualification::class],
            'lsbu' => ['title' => 'LSBU', 'route' => 'master.lsbu', 'model' => Lsbu::class],
            'associations' => ['title' => 'Asosiasi', 'route' => 'master.associations', 'model' => Association::class],
            'science-fields' => ['title' => 'Bidang Keilmuan', 'route' => 'master.science-fields', 'model' => ScienceField::class],
            'bg-equipment' => ['title' => 'Peralatan BG', 'route' => 'master.bg-equipment', 'model' => BgEquipment::class],
            'bs-equipment' => ['title' => 'Peralatan BS', 'route' => 'master.bs-equipment', 'model' => BsEquipment::class],
            'balance-items' => ['title' => 'Item Neraca', 'route' => 'master.balance-items', 'model' => BalanceItem::class],
            'document-templates' => ['title' => 'Template Dokumen', 'route' => 'master.document-templates', 'model' => DocumentTemplate::class],
        ];
    }

    /**
     * @param array{model: class-string<MasterReference>} $resource
     */
    private function findItem(array $resource, int $id): MasterReference
    {
        return $resource['model']::query()->findOrFail($id);
    }

    /**
     * @param array{model: class-string<MasterReference>} $resource
     * @return array<string, mixed>
     */
    private function validated(Request $request, array $resource, ?MasterReference $model = null): array
    {
        $table = (new $resource['model'])->getTable();

        return $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique($table, 'code')->ignore($model?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
