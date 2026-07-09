<?php

namespace App\Http\Controllers;

use App\Models\MasterSbuClassification;
use App\Helpers\SimpleXlsxReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Exception;

class MasterSbuClassificationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $classifications = MasterSbuClassification::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('sort_order')
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('master.sbu-classifications.index', compact('classifications', 'search', 'status'));
    }

    public function create(): View
    {
        return view('master.sbu-classifications.create', [
            'classification' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $classification = MasterSbuClassification::create($this->validated($request));

        return redirect()
            ->route('master.classifications.show', $classification)
            ->with('status', 'Data Klasifikasi SBU berhasil ditambahkan.');
    }

    public function show(MasterSbuClassification $classification): View
    {
        return view('master.sbu-classifications.show', compact('classification'));
    }

    public function edit(MasterSbuClassification $classification): View
    {
        return view('master.sbu-classifications.edit', compact('classification'));
    }

    public function update(Request $request, MasterSbuClassification $classification): RedirectResponse
    {
        $classification->update($this->validated($request, $classification));

        return redirect()
            ->route('master.classifications.show', $classification)
            ->with('status', 'Data Klasifikasi SBU berhasil diperbarui.');
    }

    public function destroy(MasterSbuClassification $classification): RedirectResponse
    {
        $classification->delete();

        return redirect()
            ->route('master.classifications.index')
            ->with('status', 'Data Klasifikasi SBU berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('master.sbu-classifications.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_klasifikasi.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['Kode Klasifikasi', 'Nama Klasifikasi', 'Status (Aktif/Nonaktif)', 'Urutan', 'Keterangan'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            // Add examples
            fputcsv($file, ['BG', 'Bangunan Gedung', 'Aktif', '10', 'Klasifikasi Bangunan Gedung'], ';');
            fputcsv($file, ['BS', 'Bangunan Sipil', 'Aktif', '20', 'Klasifikasi Bangunan Sipil'], ';');
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
            Log::error('Error parsing Klasifikasi SBU file: ' . $e->getMessage());
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

            // Skip completely empty rows
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
                $errors[] = "Baris {$rowNum}: Kolom Kode Klasifikasi wajib diisi.";
            }

            if ($name === '') {
                $errors[] = "Baris {$rowNum}: Kolom Nama Klasifikasi wajib diisi.";
            }

            if (in_array($code, $existingCodes)) {
                $errors[] = "Baris {$rowNum}: Kode Klasifikasi '{$code}' terduplikasi dalam file.";
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
                $existing = MasterSbuClassification::where('code', $record['code'])->first();
                if ($existing) {
                    $existing->update($record);
                    $updated++;
                } else {
                    MasterSbuClassification::create($record);
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->route('master.classifications.index')
                ->with('status', "Impor berhasil! {$inserted} data Klasifikasi SBU baru ditambahkan dan {$updated} data diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Klasifikasi SBU import save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?MasterSbuClassification $classification = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('master_sbu_classifications', 'code')->ignore($classification?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
