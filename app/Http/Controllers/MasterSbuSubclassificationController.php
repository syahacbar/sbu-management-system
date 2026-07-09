<?php

namespace App\Http\Controllers;

use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Helpers\SimpleXlsxReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Exception;

class MasterSbuSubclassificationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();
        $classificationId = $request->integer('classification_id') ?: null;

        $subclassifications = MasterSbuSubclassification::query()
            ->with('classification')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when($classificationId, fn ($query) => $query->where('master_sbu_classification_id', $classificationId))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('sort_order')
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('master.sbu-subclassifications.index', [
            'subclassifications' => $subclassifications,
            'classifications' => $this->classifications(),
            'search' => $search,
            'status' => $status,
            'classificationId' => $classificationId,
        ]);
    }

    public function create(): View
    {
        return view('master.sbu-subclassifications.create', [
            'subclassification' => null,
            'classifications' => $this->classifications(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $subclassification = MasterSbuSubclassification::create($this->validated($request));

        return redirect()
            ->route('master.subclassifications.show', $subclassification)
            ->with('status', 'Data Subklasifikasi SBU berhasil ditambahkan.');
    }

    public function show(MasterSbuSubclassification $subclassification): View
    {
        $subclassification->load('classification');

        return view('master.sbu-subclassifications.show', compact('subclassification'));
    }

    public function edit(MasterSbuSubclassification $subclassification): View
    {
        return view('master.sbu-subclassifications.edit', [
            'subclassification' => $subclassification,
            'classifications' => $this->classifications(),
        ]);
    }

    public function update(Request $request, MasterSbuSubclassification $subclassification): RedirectResponse
    {
        $subclassification->update($this->validated($request, $subclassification));

        return redirect()
            ->route('master.subclassifications.show', $subclassification)
            ->with('status', 'Data Subklasifikasi SBU berhasil diperbarui.');
    }

    public function destroy(MasterSbuSubclassification $subclassification): RedirectResponse
    {
        $subclassification->delete();

        return redirect()
            ->route('master.subclassifications.index')
            ->with('status', 'Data Subklasifikasi SBU berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('master.sbu-subclassifications.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_subklasifikasi.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['Kode Klasifikasi', 'Kode Subklasifikasi', 'Nama Subklasifikasi', 'Status (Aktif/Nonaktif)', 'Urutan', 'Keterangan'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            // Add examples
            fputcsv($file, ['BG', 'BG001', 'Konstruksi Gedung Hunian', 'Aktif', '10', 'Subklasifikasi Gedung Hunian'], ';');
            fputcsv($file, ['BG', 'BG002', 'Konstruksi Gedung Perkantoran', 'Aktif', '20', 'Subklasifikasi Gedung Kantor'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request): RedirectResponse
    {
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
                        // Try comma if semicolon resulted in 1 column
                        if (count($data) === 1 && strpos($data[0], ',') !== false) {
                            $data = str_getcsv($data[0], ',');
                        }
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            }
        } catch (Exception $e) {
            Log::error('Error parsing Subklasifikasi SBU file: ' . $e->getMessage());
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

        // Cache classifications to speed up lookup and check existence
        $classificationsMap = MasterSbuClassification::pluck('id', 'code')->toArray();

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            // Skip completely empty rows
            $nonEmptyCells = array_filter($row, fn($val) => trim((string)$val) !== '');
            if (empty($nonEmptyCells)) {
                continue;
            }

            $classificationCode = trim((string)($row[0] ?? ''));
            $code = trim((string)($row[1] ?? ''));
            $name = trim((string)($row[2] ?? ''));
            $statusStr = trim((string)($row[3] ?? ''));
            $sortOrderStr = trim((string)($row[4] ?? ''));
            $description = trim((string)($row[5] ?? ''));

            if ($classificationCode === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode Klasifikasi wajib diisi.";
            } elseif (!isset($classificationsMap[$classificationCode])) {
                $errors[] = "Baris {$rowNum}: Kode Klasifikasi SBU '{$classificationCode}' tidak terdaftar di database.";
            }

            if ($code === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode Subklasifikasi wajib diisi.";
            }

            if ($name === '') {
                $errors[] = "Baris {$rowNum}: Kolom Nama Subklasifikasi wajib diisi.";
            }

            if (in_array($code, $existingCodes)) {
                $errors[] = "Baris {$rowNum}: Kode Subklasifikasi '{$code}' terduplikasi dalam file.";
            } else {
                $existingCodes[] = $code;
            }

            // Convert status
            $is_active = true;
            if (strtolower($statusStr) === 'nonaktif' || strtolower($statusStr) === 'tidak' || $statusStr === '0' || strtolower($statusStr) === 'inactive') {
                $is_active = false;
            }

            // Validate and parse sort_order
            $sort_order = 0;
            if ($sortOrderStr !== '') {
                if (!is_numeric($sortOrderStr)) {
                    $errors[] = "Baris {$rowNum}: Urutan harus berupa angka.";
                } else {
                    $sort_order = (int)$sortOrderStr;
                }
            }

            if (isset($classificationsMap[$classificationCode])) {
                $records[] = [
                    'master_sbu_classification_id' => $classificationsMap[$classificationCode],
                    'code' => $code,
                    'name' => $name,
                    'is_active' => $is_active,
                    'sort_order' => $sort_order,
                    'description' => $description ?: null,
                ];
            }
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
                $existing = MasterSbuSubclassification::where('code', $record['code'])->first();
                if ($existing) {
                    $existing->update($record);
                    $updated++;
                } else {
                    MasterSbuSubclassification::create($record);
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->route('master.subclassifications.index')
                ->with('status', "Impor berhasil! {$inserted} data Subklasifikasi SBU baru ditambahkan dan {$updated} data diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Subklasifikasi SBU import save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?MasterSbuSubclassification $subclassification = null): array
    {
        return $request->validate([
            'master_sbu_classification_id' => ['required', 'exists:master_sbu_classifications,id'],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('master_sbu_subclassifications', 'code')->ignore($subclassification?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function classifications()
    {
        return MasterSbuClassification::query()
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get();
    }
}
