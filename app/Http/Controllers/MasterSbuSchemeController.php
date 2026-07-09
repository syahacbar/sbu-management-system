<?php

namespace App\Http\Controllers;

use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuScheme;
use App\Models\MasterSbuSubclassification;
use App\Helpers\SimpleXlsxReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Exception;

class MasterSbuSchemeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $qualification = $request->string('qualification')->toString();
        $status = $request->string('status')->toString();

        $schemes = MasterSbuScheme::query()
            ->with(['kbli', 'classification', 'subclassification'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('scheme_code', 'like', "%{$search}%")
                        ->orWhere('scheme_name', 'like', "%{$search}%")
                        ->orWhereHas('kbli', fn ($query) => $query->where('code', 'like', "%{$search}%"))
                        ->orWhereHas('subclassification', fn ($query) => $query->where('code', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($qualification, MasterSbuScheme::QUALIFICATIONS, true), fn ($query) => $query->where('qualification', $qualification))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('sort_order')
            ->orderBy('scheme_code')
            ->paginate(10)
            ->withQueryString();

        return view('master.sbu-schemes.index', compact('schemes', 'search', 'qualification', 'status'));
    }

    public function create(): View
    {
        return view('master.sbu-schemes.create', [
            'scheme' => null,
            ...$this->formOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $scheme = MasterSbuScheme::create($this->validated($request));

        return redirect()
            ->route('master.schemes.show', $scheme)
            ->with('status', 'Data Skema SBU berhasil ditambahkan.');
    }

    public function show(MasterSbuScheme $scheme): View
    {
        $scheme->load(['kbli', 'classification', 'subclassification']);

        return view('master.sbu-schemes.show', compact('scheme'));
    }

    public function edit(MasterSbuScheme $scheme): View
    {
        return view('master.sbu-schemes.edit', [
            'scheme' => $scheme,
            ...$this->formOptions(),
        ]);
    }

    public function update(Request $request, MasterSbuScheme $scheme): RedirectResponse
    {
        $scheme->update($this->validated($request, $scheme));

        return redirect()
            ->route('master.schemes.show', $scheme)
            ->with('status', 'Data Skema SBU berhasil diperbarui.');
    }

    public function destroy(MasterSbuScheme $scheme): RedirectResponse
    {
        $scheme->delete();

        return redirect()
            ->route('master.schemes.index')
            ->with('status', 'Data Skema SBU berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('master.sbu-schemes.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_skema.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['Kode KBLI', 'Kode Klasifikasi', 'Kode Subklasifikasi', 'Kode Skema', 'Nama Skema', 'Kualifikasi (Kecil/Menengah/Besar)', 'Status (Aktif/Nonaktif)', 'Urutan', 'Keterangan'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            // Add examples
            fputcsv($file, ['41011', 'BG', 'BG001', 'SK-BG001-K', 'Skema Konstruksi Gedung Hunian Kecil', 'Kecil', 'Aktif', '10', 'Skema hunian kualifikasi kecil'], ';');
            fputcsv($file, ['41012', 'BG', 'BG002', 'SK-BG002-M', 'Skema Konstruksi Gedung Perkantoran Menengah', 'Menengah', 'Aktif', '20', 'Skema gedung kantor kualifikasi menengah'], ';');
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
            Log::error('Error parsing Skema SBU file: ' . $e->getMessage());
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

        // Pre-load reference maps for validation and id lookups
        $kblisMap = MasterKbli::pluck('id', 'code')->toArray();
        $classificationsMap = MasterSbuClassification::pluck('id', 'code')->toArray();
        
        $subclassifications = MasterSbuSubclassification::all();
        $subclassificationsMap = [];
        foreach ($subclassifications as $sub) {
            $subclassificationsMap[$sub->code] = [
                'id' => $sub->id,
                'classification_id' => $sub->master_sbu_classification_id
            ];
        }

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            // Skip completely empty rows
            $nonEmptyCells = array_filter($row, fn($val) => trim((string)$val) !== '');
            if (empty($nonEmptyCells)) {
                continue;
            }

            $kbliCode = trim((string)($row[0] ?? ''));
            $classificationCode = trim((string)($row[1] ?? ''));
            $subclassificationCode = trim((string)($row[2] ?? ''));
            $schemeCode = trim((string)($row[3] ?? ''));
            $schemeName = trim((string)($row[4] ?? ''));
            $qualification = trim((string)($row[5] ?? ''));
            $statusStr = trim((string)($row[6] ?? ''));
            $sortOrderStr = trim((string)($row[7] ?? ''));
            $description = trim((string)($row[8] ?? ''));

            // 1. KBLI check
            if ($kbliCode === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode KBLI wajib diisi.";
            } elseif (!isset($kblisMap[$kbliCode])) {
                $errors[] = "Baris {$rowNum}: Kode KBLI '{$kbliCode}' tidak terdaftar.";
            }

            // 2. Classification check
            if ($classificationCode === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode Klasifikasi wajib diisi.";
            } elseif (!isset($classificationsMap[$classificationCode])) {
                $errors[] = "Baris {$rowNum}: Kode Klasifikasi SBU '{$classificationCode}' tidak terdaftar.";
            }

            // 3. Subclassification check
            if ($subclassificationCode === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode Subklasifikasi wajib diisi.";
            } elseif (!isset($subclassificationsMap[$subclassificationCode])) {
                $errors[] = "Baris {$rowNum}: Kode Subklasifikasi '{$subclassificationCode}' tidak terdaftar.";
            }

            // 4. Cross check relation Classification <-> Subclassification
            if ($classificationCode !== '' && $subclassificationCode !== '') {
                if (isset($classificationsMap[$classificationCode]) && isset($subclassificationsMap[$subclassificationCode])) {
                    $actualClassId = $classificationsMap[$classificationCode];
                    $expectedClassId = $subclassificationsMap[$subclassificationCode]['classification_id'];
                    if ($actualClassId !== $expectedClassId) {
                        $errors[] = "Baris {$rowNum}: Subklasifikasi '{$subclassificationCode}' tidak berada di bawah Klasifikasi '{$classificationCode}'.";
                    }
                }
            }

            // 5. Scheme code checks
            if ($schemeCode === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kode Skema wajib diisi.";
            }

            if ($schemeName === '') {
                $errors[] = "Baris {$rowNum}: Kolom Nama Skema wajib diisi.";
            }

            if (in_array($schemeCode, $existingCodes)) {
                $errors[] = "Baris {$rowNum}: Kode Skema '{$schemeCode}' terduplikasi dalam file.";
            } else {
                $existingCodes[] = $schemeCode;
            }

            // 6. Qualification check
            $validQualification = ucfirst(strtolower($qualification));
            if ($qualification === '') {
                $errors[] = "Baris {$rowNum}: Kolom Kualifikasi wajib diisi.";
            } elseif (!in_array($validQualification, MasterSbuScheme::QUALIFICATIONS)) {
                $errors[] = "Baris {$rowNum}: Kualifikasi '{$qualification}' tidak valid (Gunakan: Kecil, Menengah, atau Besar).";
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

            if (empty($errors)) {
                $records[] = [
                    'master_kbli_id' => $kblisMap[$kbliCode],
                    'master_sbu_classification_id' => $classificationsMap[$classificationCode],
                    'master_sbu_subclassification_id' => $subclassificationsMap[$subclassificationCode]['id'],
                    'scheme_code' => $schemeCode,
                    'scheme_name' => $schemeName,
                    'qualification' => $validQualification,
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
                $existing = MasterSbuScheme::where('scheme_code', $record['scheme_code'])->first();
                if ($existing) {
                    $existing->update($record);
                    $updated++;
                } else {
                    MasterSbuScheme::create($record);
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->route('master.schemes.index')
                ->with('status', "Impor berhasil! {$inserted} data Skema SBU baru ditambahkan dan {$updated} data diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Skema SBU import save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'kblis' => MasterKbli::query()->orderBy('sort_order')->orderBy('code')->get(),
            'classifications' => MasterSbuClassification::query()->orderBy('sort_order')->orderBy('code')->get(),
            'subclassifications' => MasterSbuSubclassification::query()
                ->with('classification')
                ->orderBy('sort_order')
                ->orderBy('code')
                ->get(),
            'qualifications' => MasterSbuScheme::QUALIFICATIONS,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?MasterSbuScheme $scheme = null): array
    {
        $validated = $request->validate([
            'master_kbli_id' => ['required', 'exists:master_kblis,id'],
            'master_sbu_classification_id' => ['required', 'exists:master_sbu_classifications,id'],
            'master_sbu_subclassification_id' => ['required', 'exists:master_sbu_subclassifications,id'],
            'scheme_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('master_sbu_schemes', 'scheme_code')->ignore($scheme?->id),
            ],
            'scheme_name' => ['required', 'string', 'max:255'],
            'qualification' => ['required', Rule::in(MasterSbuScheme::QUALIFICATIONS)],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $subclassificationMatches = MasterSbuSubclassification::query()
            ->whereKey($validated['master_sbu_subclassification_id'])
            ->where('master_sbu_classification_id', $validated['master_sbu_classification_id'])
            ->exists();

        if (! $subclassificationMatches) {
            throw ValidationException::withMessages([
                'master_sbu_subclassification_id' => 'Subklasifikasi harus sesuai dengan klasifikasi yang dipilih.',
            ]);
        }

        return $validated;
    }
}
