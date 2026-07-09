<?php

namespace App\Http\Controllers;

use App\Models\MasterKbli;
use App\Helpers\SimpleXlsxReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Exception;

class MasterKbliController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $kblis = MasterKbli::query()
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

        return view('master.kbli.index', compact('kblis', 'search', 'status'));
    }

    public function create(): View
    {
        return view('master.kbli.create', [
            'kbli' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $kbli = MasterKbli::create($this->validated($request));

        return redirect()
            ->route('master.kbli.show', $kbli)
            ->with('status', 'Data KBLI berhasil ditambahkan.');
    }

    public function show(MasterKbli $kbli): View
    {
        return view('master.kbli.show', compact('kbli'));
    }

    public function edit(MasterKbli $kbli): View
    {
        return view('master.kbli.edit', compact('kbli'));
    }

    public function update(Request $request, MasterKbli $kbli): RedirectResponse
    {
        $kbli->update($this->validated($request, $kbli));

        return redirect()
            ->route('master.kbli.show', $kbli)
            ->with('status', 'Data KBLI berhasil diperbarui.');
    }

    public function destroy(MasterKbli $kbli): RedirectResponse
    {
        $kbli->delete();

        return redirect()
            ->route('master.kbli.index')
            ->with('status', 'Data KBLI berhasil dihapus.');
    }

    public function importForm(): View
    {
        return view('master.kbli.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_kbli.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['Kode KBLI', 'Nama KBLI', 'Status (Aktif/Nonaktif)', 'Urutan', 'Keterangan'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');
            // Add examples
            fputcsv($file, ['41011', 'Konstruksi Gedung Hunian', 'Aktif', '10', 'Data dummy hunian'], ';');
            fputcsv($file, ['41012', 'Konstruksi Gedung Perkantoran', 'Aktif', '20', 'Data dummy perkantoran'], ';');
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
                        // Semicolon is default, but try comma if semicolon resulted in 1 column
                        if (count($data) === 1 && strpos($data[0], ',') !== false) {
                            $data = str_getcsv($data[0], ',');
                        }
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            }
        } catch (Exception $e) {
            Log::error('Error parsing KBLI file: ' . $e->getMessage());
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
            $rowNum = $index + 2; // Row number 1-indexed, starting after header (row 1)

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
                $errors[] = "Baris {$rowNum}: Kolom Kode KBLI wajib diisi.";
            }

            if ($name === '') {
                $errors[] = "Baris {$rowNum}: Kolom Nama KBLI wajib diisi.";
            }

            if (in_array($code, $existingCodes)) {
                $errors[] = "Baris {$rowNum}: Kode KBLI '{$code}' terduplikasi dalam file.";
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
                $existing = MasterKbli::where('code', $record['code'])->first();
                if ($existing) {
                    $existing->update($record);
                    $updated++;
                } else {
                    MasterKbli::create($record);
                    $inserted++;
                }
            }

            DB::commit();

            return redirect()
                ->route('master.kbli.index')
                ->with('status', "Impor berhasil! {$inserted} data KBLI baru ditambahkan dan {$updated} data diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('KBLI import save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'Gagal menyimpan data ke database: ' . $e->getMessage()]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?MasterKbli $kbli = null): array
    {
        return $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('master_kblis', 'code')->ignore($kbli?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
