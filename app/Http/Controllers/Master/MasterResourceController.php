<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Association;
use App\Models\Master\BalanceItem;
use App\Models\Master\BgEquipment;
use App\Models\Master\BsEquipment;
use App\Models\Master\Lsbu;
use App\Models\Master\MasterReference;
use App\Models\Master\Qualification;
use App\Models\Master\ScienceField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Helpers\SimpleXlsxReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

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

    public function downloadTemplate(Request $request)
    {
        $resource = $this->resource($request);
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_' . str_replace('-', '_', $resource['key']) . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'Kode ' . $resource['title'],
            'Nama ' . $resource['title'],
            'Status (Aktif/Nonaktif)',
            'Urutan',
            'Keterangan'
        ];

        $examples = match ($resource['key']) {
            'science-fields' => [
                ['SIPIL', 'Teknik Sipil', 'Aktif', '10', 'Rumpun keilmuan teknik sipil'],
                ['ARS', 'Arsitektur', 'Aktif', '20', 'Rumpun keilmuan arsitektur']
            ],
            'bg-equipment' => [
                ['BG-ALAT-01', 'Concrete Mixer', 'Aktif', '10', 'Alat pengaduk semen'],
                ['BG-ALAT-02', 'Scaffolding', 'Aktif', '20', 'Perancah bangunan']
            ],
            'bs-equipment' => [
                ['BS-ALAT-01', 'Excavator', 'Aktif', '10', 'Alat berat pengeruk'],
                ['BS-ALAT-02', 'Vibro Roller', 'Aktif', '20', 'Alat pemadat tanah']
            ],
            default => [
                ['KODE01', 'Contoh Nama 1', 'Aktif', '10', 'Contoh Keterangan 1'],
                ['KODE02', 'Contoh Nama 2', 'Aktif', '20', 'Contoh Keterangan 2']
            ]
        };

        $callback = function () use ($columns, $examples) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            foreach ($examples as $row) {
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importForm(Request $request): View
    {
        $resource = $this->resource($request);
        return view('master.import', compact('resource'));
    }

    public function import(Request $request): RedirectResponse
    {
        $resource = $this->resource($request);

        $request->validate([
            'file' => ['required', 'file', 'max:5120'], // Max 5MB
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['xlsx', 'csv'])) {
            return redirect()->back()->withErrors(['file' => 'Hanya file dengan ekstensi .xlsx atau .csv yang diperbolehkan.']);
        }

        $rows = [];
        try {
            if ($extension === 'xlsx') {
                $rows = SimpleXlsxReader::read($path);
            } else {
                if (($handle = fopen($path, 'r')) !== false) {
                    $bom = fread($handle, 3);
                    if ($bom !== "\xEF\xBB\xBF") {
                        rewind($handle);
                    }
                    while (($data = fgetcsv($handle, 0, ';')) !== false) {
                        if (count($data) === 1 && strpos($data[0], ',') !== false) {
                            $data = str_getcsv($data[0], ',');
                        }
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            }
        } catch (Exception $e) {
            Log::error('Error parsing file for ' . $resource['key'] . ': ' . $e->getMessage());
            return redirect()->back()->withErrors(['file' => 'Gagal membaca isi berkas: ' . $e->getMessage()]);
        }

        if (count($rows) <= 1) {
            return redirect()->back()->withErrors(['file' => 'Berkas kosong atau hanya berisi baris header.']);
        }

        // Remove header row
        $header = array_shift($rows);

        $errors = [];
        $records = [];
        $existingCodes = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $nonEmptyCells = array_filter($row, fn($val) => trim((string)$val) !== '');
            if (empty($nonEmptyCells)) {
                continue;
            }

            $code = trim((string)($row[0] ?? ''));
            $name = trim((string)($row[1] ?? ''));
            $statusStr = trim((string)($row[2] ?? ''));
            $sortOrderStr = trim((string)($row[3] ?? ''));
            $description = trim((string)($row[4] ?? ''));

            if ($code === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode wajib diisi.";
            }

            if ($name === '') {
                $errors[] = "Baris {$rowNum}: Kolom Nama wajib diisi.";
            }

            if (in_array($code, $existingCodes)) {
                $errors[] = "Baris {$rowNum}: Kode '{$code}' terduplikasi dalam file.";
            } else {
                $existingCodes[] = $code;
            }

            $is_active = true;
            if (strtolower($statusStr) === 'nonaktif' || strtolower($statusStr) === 'tidak' || $statusStr === '0' || strtolower($statusStr) === 'inactive') {
                $is_active = false;
            }

            $sort_order = 0;
            if ($sortOrderStr !== '') {
                if (!is_numeric($sortOrderStr)) {
                    $errors[] = "Baris {$rowNum}: Urutan harus berupa angka.";
                } else {
                    $sort_order = (int)$sortOrderStr;
                }
            }

            $records[] = [
                'code' => $code,
                'name' => $name,
                'is_active' => $is_active,
                'sort_order' => $sort_order,
                'description' => $description ?: null,
            ];
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->with('import_errors', $errors);
        }

        DB::beginTransaction();
        try {
            $inserted = 0;
            $updated = 0;

            foreach ($records as $record) {
                $existing = $resource['model']::where('code', $record['code'])->first();
                if ($existing) {
                    $existing->update($record);
                    $updated++;
                } else {
                    $resource['model']::create($record);
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->route($resource['route'].'.index')
                ->with('status', "Impor berhasil! {$inserted} data {$resource['title']} baru ditambahkan dan {$updated} data diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($resource['key'] . ' import save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
        }
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
            'qualifications' => ['title' => 'Kualifikasi', 'route' => 'master.qualifications', 'model' => Qualification::class],
            'lsbu' => ['title' => 'LSBU', 'route' => 'master.lsbu', 'model' => Lsbu::class],
            'associations' => ['title' => 'Asosiasi', 'route' => 'master.associations', 'model' => Association::class],
            'science-fields' => ['title' => 'Bidang Keilmuan', 'route' => 'master.science-fields', 'model' => ScienceField::class],
            'bg-equipment' => ['title' => 'Peralatan BG', 'route' => 'master.bg-equipment', 'model' => BgEquipment::class],
            'bs-equipment' => ['title' => 'Peralatan BS', 'route' => 'master.bs-equipment', 'model' => BsEquipment::class],
            'balance-items' => ['title' => 'Item Neraca', 'route' => 'master.balance-items', 'model' => BalanceItem::class],
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
