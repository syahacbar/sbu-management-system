<?php

namespace App\Services;

use App\Models\Master\DocumentTemplate;
use App\Models\Workspace\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfDocumentService
{
    /**
     * Render the template with dynamic tokens
     */
    public function renderHtml(DocumentTemplate $template, Application $application): string
    {
        $company = $application->company;
        $mainDirector = $company->directors()->where('is_main', true)->first() ?: $company->directors()->first();
        $directorName = $mainDirector?->name ?? '-';

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
            'qualification' => $application->qualification ?: ($application->scheme?->qualification ?? '-'),
            'application_code' => $application->application_number ?: '-',
            'application_date' => $application->submission_date?->format('d F Y') ?: '-',
            'current_date' => now()->format('d F Y'),
        ];

        // Replace asset paths to absolute paths or base64 for DomPDF compatibility
        $logoHtml = '';
        if ($template->logo_path) {
            $logoPath = storage_path('app/public/' . $template->logo_path);
            if (file_exists($logoPath)) {
                $logoHtml = '<img src="data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) . '" style="max-height: 80px; width: auto; object-fit: contain;" alt="Logo">';
            }
        }
        $tokens['logo_img'] = $logoHtml;

        $signatureHtml = '';
        if ($template->signature_path) {
            $signaturePath = storage_path('app/public/' . $template->signature_path);
            if (file_exists($signaturePath)) {
                $signatureHtml = '<img src="data:image/' . pathinfo($signaturePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($signaturePath)) . '" style="max-height: 80px; width: auto; object-fit: contain;" alt="TTE">';
            }
        }
        $tokens['signature_img'] = $signatureHtml;

        $stampHtml = '';
        if ($template->stamp_path) {
            $stampPath = storage_path('app/public/' . $template->stamp_path);
            if (file_exists($stampPath)) {
                $stampHtml = '<img src="data:image/' . pathinfo($stampPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stampPath)) . '" style="max-height: 80px; width: auto; object-fit: contain;" alt="Stempel">';
            }
        }
        $tokens['stamp_img'] = $stampHtml;

        $body = $template->render($tokens);

        // Build HTML template layout for DomPDF rendering
        $html = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: a4;
                    margin: 15mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    color: #0f172a;
                    margin: 0;
                    padding: 0;
                }
                .letterhead {
                    border-bottom: 2px double #000;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                    display: block;
                    width: 100%;
                }
                .letterhead-logo {
                    float: left;
                    max-height: 60px;
                    margin-right: 15px;
                }
                .letterhead-text {
                    text-align: center;
                }
                .letterhead-title {
                    font-size: 14px;
                    font-weight: bold;
                    text-transform: uppercase;
                    margin: 0;
                }
                .letterhead-subtitle {
                    font-size: 9px;
                    margin: 2px 0 0 0;
                    color: #475569;
                }
                .document-body {
                    margin-top: 15px;
                }
                .document-footer {
                    margin-top: 25px;
                    text-align: center;
                    font-size: 8px;
                    color: #64748b;
                }
                .clear {
                    clear: both;
                }
            </style>
        </head>
        <body>
            <div class="letterhead">';
            if ($template->logo_path) {
                $html .= $logoHtml;
            }
            $html .= '
                <div class="letterhead-text">
                    <div class="letterhead-title">' . ($template->header_text ?: 'Pemerintah Republik Indonesia') . '</div>
                    <div class="letterhead-subtitle">Sistem Informasi Manajemen Sertifikat SBU Elektronik Terintegrasi</div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="document-body">
                ' . $body . '
            </div>';
            if ($template->footer_text) {
                $html .= '<div class="document-footer">' . $template->footer_text . '</div>';
            }
            $html .= '
        </body>
        </html>';

        return $html;
    }

    /**
     * Generate PDF binary using DomPDF
     */
    public function generatePdf(DocumentTemplate $template, Application $application): \Barryvdh\DomPDF\PDF
    {
        $html = $this->renderHtml($template, $application);
        return Pdf::loadHTML($html)->setPaper('a4', 'portrait');
    }

    /**
     * Generate PDF and save copy to generated_documents
     */
    public function generateAndSave(DocumentTemplate $template, Application $application): string
    {
        $company = $application->company;
        $pdf = $this->generatePdf($template, $application);
        
        $fileName = 'document_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        // Store file in public storage
        Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template->id,
            'document_type' => $template->name . ' (PDF)',
            'file_path' => $filePath,
            'original_filename' => $template->name . '_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $filePath;
    }
}
