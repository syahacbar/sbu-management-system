<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\GeneratedDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompanyArchiveController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $archives = $company->archives()
            ->with('application')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('workspace.archives.index', compact('company', 'archives'));
    }

    public function globalIndex(Request $request): View
    {
        $companyId = $request->integer('company_id');
        $search = $request->string('search')->toString();

        $companies = Company::orderBy('name')->get();

        $archives = GeneratedDocument::query()
            ->with(['company', 'application'])
            ->when($companyId > 0, fn ($query) => $query->where('company_id', $companyId))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('document_type', 'like', "%{$search}%")
                        ->orWhere('original_filename', 'like', "%{$search}%")
                        ->orWhereHas('company', fn($qc) => $qc->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('application', fn($qa) => $qa->where('application_number', 'like', "%{$search}%"));
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('workspace.archives.global', compact('companies', 'archives', 'companyId', 'search'));
    }

    public function destroy(Company $company, GeneratedDocument $archive): RedirectResponse
    {
        if ((int) $archive->company_id !== (int) $company->id) {
            abort(403);
        }

        if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
            Storage::disk('public')->delete($archive->file_path);
        }

        $archive->delete();

        return redirect()
            ->route('companies.workspace.archives.index', $company)
            ->with('status', 'Arsip dokumen berhasil dihapus.');
    }

    public function download(GeneratedDocument $archive): StreamedResponse
    {
        if (!$archive->file_path || !Storage::disk('public')->exists($archive->file_path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return Storage::disk('public')->download($archive->file_path, $archive->original_filename);
    }

    public function view(GeneratedDocument $archive): Response
    {
        if (!$archive->file_path || !Storage::disk('public')->exists($archive->file_path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $content = Storage::disk('public')->get($archive->file_path);
        $contentType = str_ends_with(strtolower($archive->file_path), '.pdf')
            ? 'application/pdf'
            : 'text/html';

        return response($content, 200, [
            'Content-Type' => $contentType,
        ]);
    }

    public function print(GeneratedDocument $archive): Response
    {
        if (!$archive->file_path || !Storage::disk('public')->exists($archive->file_path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $content = Storage::disk('public')->get($archive->file_path);

        if (str_ends_with(strtolower($archive->file_path), '.pdf')) {
            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . ($archive->original_filename ?: 'document.pdf') . '"',
            ]);
        }
        
        // Inject window.print script
        $printScript = '<script>window.onload = function() { window.print(); }</script>';
        if (str_contains($content, '</body>')) {
            $content = str_replace('</body>', $printScript . '</body>', $content);
        } else {
            $content .= $printScript;
        }

        return response($content, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
