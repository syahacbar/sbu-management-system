<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Master\DocumentTemplate;
use App\Models\Workspace\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class GenerateController extends Controller
{
    public function index(Company $company): View
    {
        $applications = $company->applications()
            ->with(['kbli', 'classification', 'subclassification', 'scheme'])
            ->orderBy('id', 'desc')
            ->get();

        $templates = DocumentTemplate::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('workspace.generate', compact('company', 'applications', 'templates'));
    }

    public function preview(Request $request, Company $company): View
    {
        $request->validate([
            'application_id' => ['required', 'exists:company_applications,id'],
            'template_id' => ['required', 'exists:master_document_templates,id'],
        ]);

        $application = $company->applications()->findOrFail($request->input('application_id'));
        $template = DocumentTemplate::findOrFail($request->input('template_id'));

        // Substitusi data dinamis
        $directorName = $company->directors()->first()?->name ?? '-';

        $tokens = [
            'company_name' => $company->name,
            'company_address' => $company->address ?? '-',
            'company_npwp' => $company->npwp ?? '-',
            'director_name' => $directorName,
            'kbli_code' => $application->kbli?->code ?? '-',
            'kbli_name' => $application->kbli?->name ?? '-',
            'classification_code' => $application->classification?->code ?? '-',
            'classification_name' => $application->classification?->name ?? '-',
            'subclassification_code' => $application->subclassification?->code ?? '-',
            'subclassification_name' => $application->subclassification?->name ?? '-',
            'scheme_code' => $application->scheme?->scheme_code ?? '-',
            'scheme_name' => $application->scheme?->scheme_name ?? '-',
            'qualification' => $application->scheme?->qualification ?? '-',
            'application_code' => $application->code ?: '-',
            'application_date' => $application->record_date?->format('d F Y') ?: '-',
            'current_date' => now()->format('d F Y'),
        ];

        // Substitusi image tokens
        $tokens['logo_img'] = $template->logo_path ? '<img src="' . asset('storage/' . $template->logo_path) . '" style="max-height: 80px; object-fit: contain;" alt="Logo">' : '';
        $tokens['signature_img'] = $template->signature_path ? '<img src="' . asset('storage/' . $template->signature_path) . '" style="max-height: 80px; object-fit: contain;" alt="TTE">' : '';
        $tokens['stamp_img'] = $template->stamp_path ? '<img src="' . asset('storage/' . $template->stamp_path) . '" style="max-height: 80px; object-fit: contain;" alt="Stempel">' : '';

        // Render template body
        $renderedBody = $template->render($tokens);

        return view('workspace.generate_preview', compact(
            'company',
            'application',
            'template',
            'renderedBody'
        ));
    }

    public function saveArchive(Request $request, Company $company): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'code' => ['nullable', 'string', 'max:100'],
                'html' => ['required', 'string'],
            ]);

            $company->archives()->create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'status' => 'Arsip Resmi',
                'record_date' => now(),
                'description' => $validated['html'], // Simpan full HTML yang ter-render
                'is_active' => true,
                'sort_order' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil disimpan ke dalam menu Arsip Perusahaan.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan arsip: ' . $e->getMessage(),
            ], 500);
        }
    }
}
