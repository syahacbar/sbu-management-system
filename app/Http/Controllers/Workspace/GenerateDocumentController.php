<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Master\DocumentTemplate;
use App\Models\Workspace\Application;
use App\Services\PdfDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerateDocumentController extends Controller
{
    protected PdfDocumentService $pdfService;

    public function __construct(PdfDocumentService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Preview document in browser (HTML view)
     */
    public function preview(Company $company, Application $application, DocumentTemplate $template): Response
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $html = $this->pdfService->renderHtml($template, $application);

        // Render in browser with preview controls
        return response($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    /**
     * Generate PDF and trigger browser download
     */
    public function download(Company $company, Application $application, DocumentTemplate $template)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        // Generate and save to archives
        $filePath = $this->pdfService->generateAndSave($template, $application);

        // Download directly from public storage
        $absolutePath = storage_path('app/public/' . $filePath);

        return response()->download($absolutePath, $template->name . '_' . $application->application_number . '.pdf');
    }
}
