<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyDocumentController extends Controller
{
    private array $documentTypes = [
        'NIB',
        'NPWP',
        'KTP Direktur',
        'Akta',
        'SK Kemenkumham',
        'SKK Tenaga Ahli',
        'KTA Asosiasi',
        'Neraca',
        'SPT Tahunan',
        'Dokumen OSS',
        'Lainnya',
    ];

    public function index(Request $request, Company $company): View
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();
        $documents = null;

        if ($activeApplication) {
            $documents = $company->documents()
                ->where('sbu_application_id', $activeApplication->id)
                ->orderBy('document_type')
                ->paginate(15);
        }

        return view('workspace.documents.index', compact('company', 'activeApplication', 'documents'));
    }

    public function create(Company $company): View|RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.documents.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif. Silakan aktifkan pengajuan terlebih dahulu.');
        }

        return view('workspace.documents.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => null,
            'documentTypes' => $this->documentTypes,
            'statuses' => $this->documentStatuses(),
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $activeApplication = $company->applications()->where('is_active', true)->first();

        if (!$activeApplication) {
            return redirect()
                ->route('companies.workspace.documents.index', $company)
                ->with('error', 'Perusahaan ini tidak memiliki pengajuan SBU yang aktif.');
        }

        $request->validate([
            'document_type' => ['required', 'string', Rule::in($this->documentTypes)],
            'file' => ['required', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:5120'], // Max 5MB
            'document_date' => ['nullable', 'date'],
            'expired_at' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(['ada', 'belum_ada', 'revisi'])],
            'notes' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        
        // Save to public disk: application-documents/{company_id}
        $path = $file->store("application-documents/{$company->id}", 'public');

        $company->documents()->create([
            'sbu_application_id' => $activeApplication->id,
            'document_type' => $request->input('document_type'),
            'file_path' => $path,
            'original_filename' => $originalName,
            'document_date' => $request->input('document_date'),
            'expired_at' => $request->input('expired_at'),
            'status' => $request->input('status'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()
            ->route('companies.workspace.documents.index', $company)
            ->with('status', 'Dokumen pendukung berhasil diunggah.');
    }

    public function edit(Company $company, ApplicationDocument $document): View|RedirectResponse
    {
        if ((int) $document->company_id !== (int) $company->id) {
            abort(403);
        }

        $activeApplication = $company->applications()->where('is_active', true)->first();

        return view('workspace.documents.form', [
            'company' => $company,
            'activeApplication' => $activeApplication,
            'item' => $document,
            'documentTypes' => $this->documentTypes,
            'statuses' => $this->documentStatuses(),
        ]);
    }

    public function show(Company $company, ApplicationDocument $document): RedirectResponse
    {
        if ((int) $document->company_id !== (int) $company->id) {
            abort(403);
        }

        return redirect()->route('companies.workspace.documents.edit', [$company, $document]);
    }

    public function update(Request $request, Company $company, ApplicationDocument $document): RedirectResponse
    {
        if ((int) $document->company_id !== (int) $company->id) {
            abort(403);
        }

        $request->validate([
            'document_type' => ['required', 'string', Rule::in($this->documentTypes)],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:5120'], // Max 5MB
            'document_date' => ['nullable', 'date'],
            'expired_at' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(['ada', 'belum_ada', 'revisi'])],
            'notes' => ['nullable', 'string'],
        ]);

        $updateData = [
            'document_type' => $request->input('document_type'),
            'document_date' => $request->input('document_date'),
            'expired_at' => $request->input('expired_at'),
            'status' => $request->input('status'),
            'notes' => $request->input('notes'),
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store("application-documents/{$company->id}", 'public');

            $updateData['file_path'] = $path;
            $updateData['original_filename'] = $originalName;
        }

        $document->update($updateData);

        return redirect()
            ->route('companies.workspace.documents.index', $company)
            ->with('status', 'Dokumen pendukung berhasil diperbarui.');
    }

    public function destroy(Company $company, ApplicationDocument $document): RedirectResponse
    {
        if ((int) $document->company_id !== (int) $company->id) {
            abort(403);
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('companies.workspace.documents.index', $company)
            ->with('status', 'Dokumen pendukung berhasil dihapus.');
    }

    public function download(Company $company, ApplicationDocument $document): StreamedResponse
    {
        if ((int) $document->company_id !== (int) $company->id) {
            abort(403);
        }

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_filename);
    }

    /**
     * @return array<string, string>
     */
    private function documentStatuses(): array
    {
        return [
            'ada' => 'Ada',
            'belum_ada' => 'Belum Ada',
            'revisi' => 'Revisi',
        ];
    }
}
