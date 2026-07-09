<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Master\DocumentTemplate;
use App\Models\Workspace\Application;
use App\Models\Workspace\GeneratedDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Exception;

class GenerateController extends Controller
{
    /**
     * @return array<string, array<string, string>>
     */
    private function documentDefinitions(): array
    {
        return [
            'sptjm' => [
                'label' => 'SPTJM',
                'view' => 'pdf.sptjm',
                'paper' => 'portrait',
                'template' => 'SPTJM',
                'filename' => 'SPTJM',
                'archive_type' => 'SPTJM (PDF)',
            ],
            'experts_annex' => [
                'label' => 'Lampiran PJTBU/PJSKBU',
                'view' => 'pdf.lampiran-tenaga-ahli',
                'paper' => 'portrait',
                'template' => 'lampiran',
                'filename' => 'Lampiran_PJTBU_PJSKBU',
                'archive_type' => 'Lampiran PJTBU/PJSKBU (PDF)',
            ],
            'balance' => [
                'label' => 'Neraca',
                'view' => 'pdf.neraca',
                'paper' => 'landscape',
                'template' => 'neraca',
                'filename' => 'Neraca',
                'archive_type' => 'Neraca Keuangan (PDF)',
            ],
            'equip_bg' => [
                'label' => 'Surat Pernyataan Alat BG',
                'view' => 'pdf.surat-alat-bg',
                'paper' => 'portrait',
                'template' => 'peralatan',
                'filename' => 'Surat_Alat_BG',
                'archive_type' => 'Surat Pernyataan Alat BG (PDF)',
            ],
            'equip_bs' => [
                'label' => 'Surat Pernyataan Alat BS',
                'view' => 'pdf.surat-alat-bs',
                'paper' => 'portrait',
                'template' => 'peralatan',
                'filename' => 'Surat_Alat_BS',
                'archive_type' => 'Surat Pernyataan Alat BS (PDF)',
            ],
            'smap' => [
                'label' => 'SMAP',
                'view' => 'pdf.smap',
                'paper' => 'portrait',
                'template' => 'SMAP',
                'filename' => 'SMAP',
                'archive_type' => 'SMAP (PDF)',
            ],
        ];
    }

    public function index(Request $request, Company $company): View
    {
        $applications = $company->applications()
            ->with(['kbli', 'classification', 'subclassification', 'scheme'])
            ->orderBy('id', 'desc')
            ->get();

        $templates = DocumentTemplate::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $requestedApplicationId = $request->integer('application_id');
        $selectedApplication = $requestedApplicationId > 0
            ? $applications->firstWhere('id', $requestedApplicationId)
            : null;
        $selectedApplication ??= $applications->firstWhere('is_active', true) ?: $applications->first();
        $documentStatuses = $selectedApplication
            ? $this->documentStatuses($company, $selectedApplication)
            : collect($this->documentDefinitions())->mapWithKeys(fn ($definition, $key) => [$key => [
                'key' => $key,
                'label' => $definition['label'],
                'is_valid' => false,
                'warnings' => ['Belum ada pengajuan SBU yang dapat dipilih.'],
            ]])->all();

        return view('workspace.generate', compact('company', 'applications', 'templates', 'selectedApplication', 'documentStatuses'));
    }

    public function processDocuments(Request $request, Company $company): View
    {
        $definitions = $this->documentDefinitions();
        $validated = $request->validate([
            'application_id' => ['required', 'exists:sbu_applications,id'],
            'action' => ['required', Rule::in(['preview', 'download_selected', 'generate_all'])],
            'document_keys' => ['nullable', 'array'],
            'document_keys.*' => ['string', Rule::in(array_keys($definitions))],
        ]);

        $application = $company->applications()
            ->with(['kbli', 'classification', 'subclassification', 'scheme'])
            ->findOrFail($validated['application_id']);

        $requestedKeys = $validated['action'] === 'generate_all'
            ? array_keys($definitions)
            : ($validated['document_keys'] ?? []);

        $statuses = $this->documentStatuses($company, $application);
        $selectedStatuses = array_intersect_key($statuses, array_flip($requestedKeys));
        $generatedArchives = [];

        if (empty($requestedKeys)) {
            $selectedStatuses = [];
        }

        if (in_array($validated['action'], ['download_selected', 'generate_all'], true)) {
            foreach ($selectedStatuses as $key => $status) {
                if (! $status['is_valid']) {
                    continue;
                }

                $generatedArchives[$key] = $this->generateArchive($company, $application, $key);
            }
        }

        return view('workspace.generate_result', [
            'company' => $company,
            'application' => $application,
            'action' => $validated['action'],
            'documentStatuses' => $selectedStatuses,
            'generatedArchives' => $generatedArchives,
        ]);
    }

    public function previewDocument(Company $company, Application $application, string $document)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        if (! array_key_exists($document, $this->documentDefinitions())) {
            abort(404);
        }

        $status = $this->documentStatuses($company, $application)[$document];
        if (! $status['is_valid']) {
            return redirect()
                ->route('companies.workspace.generate.index', $company)
                ->with('error', $status['label'] . ' belum memenuhi syarat: ' . implode(' ', $status['warnings']));
        }

        return response($this->renderDocumentHtml($company, $application, $document), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function preview(Request $request, Company $company): View
    {
        $request->validate([
            'application_id' => ['required', 'exists:sbu_applications,id'],
            'template_id' => ['required', 'exists:master_document_templates,id'],
        ]);

        $application = $company->applications()->findOrFail($request->input('application_id'));
        $template = DocumentTemplate::findOrFail($request->input('template_id'));

        // Substitusi data dinamis
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
                'template_id' => ['required', 'exists:master_document_templates,id'],
                'application_id' => ['required', 'exists:sbu_applications,id'],
                'html' => ['required', 'string'],
            ]);

            $template = DocumentTemplate::findOrFail($validated['template_id']);
            $application = $company->applications()->findOrFail($validated['application_id']);

            // Save the HTML file in the public storage
            $fileName = 'document_' . time() . '_' . uniqid() . '.html';
            $filePath = "generated-documents/{$company->id}/" . $fileName;
            Storage::disk('public')->put($filePath, $validated['html']);

            $company->archives()->create([
                'sbu_application_id' => $application->id,
                'document_template_id' => $template->id,
                'document_type' => $template->name,
                'file_path' => $filePath,
                'original_filename' => $template->name . '_' . $application->application_number . '.html',
                'generated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diarsipkan ke dalam menu Arsip Perusahaan.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengarsipkan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return array<string, array{key: string, label: string, is_valid: bool, warnings: array<int, string>}>
     */
    private function documentStatuses(Company $company, Application $application): array
    {
        $pjbu = $this->mainPjbu($company);
        $statement = $this->statement($company, $application);
        $pjtbuCount = $application->experts()->where('expert_type', 'pjtbu')->count();
        $pjskbuCount = $application->experts()->where('expert_type', 'pjskbu')->count();
        $bgEquipmentCount = $this->equipmentQuery($company, $application, 'bg')->count();
        $bsEquipmentCount = $this->equipmentQuery($company, $application, 'bs')->count();

        $statuses = [];
        foreach ($this->documentDefinitions() as $key => $definition) {
            $warnings = [];

            if (! $pjbu) {
                $warnings[] = 'Belum ada PJBU utama/terdaftar untuk penandatangan.';
            }

            if ($key === 'experts_annex') {
                if ($pjtbuCount === 0) {
                    $warnings[] = 'Belum ada tenaga ahli PJTBU pada pengajuan ini.';
                }
                if ($pjskbuCount === 0) {
                    $warnings[] = 'Belum ada tenaga ahli PJSKBU pada pengajuan ini.';
                }
            }

            if ($key === 'balance' && ! $statement) {
                $warnings[] = 'Belum ada data neraca keuangan untuk pengajuan ini.';
            }

            if ($key === 'equip_bg' && $bgEquipmentCount === 0) {
                $warnings[] = 'Belum ada peralatan kategori BG untuk pengajuan ini.';
            }

            if ($key === 'equip_bs' && $bsEquipmentCount === 0) {
                $warnings[] = 'Belum ada peralatan kategori BS untuk pengajuan ini.';
            }

            $statuses[$key] = [
                'key' => $key,
                'label' => $definition['label'],
                'is_valid' => empty($warnings),
                'warnings' => $warnings,
            ];
        }

        return $statuses;
    }

    private function renderDocumentHtml(Company $company, Application $application, string $document): string
    {
        $definition = $this->documentDefinitions()[$document];
        $application->loadMissing(['kbli', 'classification', 'subclassification', 'scheme']);

        $pjbu = $this->mainPjbu($company);
        $formattedDate = $this->formatIndonesianDate(now());
        [$stampBase64, $signatureBase64] = $this->templateImages($definition['template']);

        $data = compact('company', 'application', 'pjbu', 'formattedDate', 'stampBase64', 'signatureBase64');

        if ($document === 'experts_annex') {
            $data['pjtbuList'] = $application->experts()->where('expert_type', 'pjtbu')->get();
            $data['pjskbuList'] = $application->experts()->where('expert_type', 'pjskbu')->get();
        }

        if ($document === 'balance') {
            $statement = $this->statement($company, $application);
            $values = $statement->values()->with('masterItem')->get();

            $data['statement'] = $statement;
            $data['formattedDate'] = $this->formatIndonesianDate($statement->statement_date ?: now());
            $data['aktivaLancar'] = $values->filter(fn ($value) => $value->masterItem->section === 'aktiva' && $value->masterItem->group_name === 'lancar');
            $data['aktivaTetap'] = $values->filter(fn ($value) => $value->masterItem->section === 'aktiva' && $value->masterItem->group_name === 'tetap');
            $data['kewajiban'] = $values->filter(fn ($value) => $value->masterItem->section === 'pasiva' && $value->masterItem->group_name === 'kewajiban');
            $data['ekuitas'] = $values->filter(fn ($value) => $value->masterItem->section === 'pasiva' && $value->masterItem->group_name === 'ekuitas');
        }

        if ($document === 'equip_bg') {
            $data['equipments'] = $this->equipmentQuery($company, $application, 'bg')->get();
        }

        if ($document === 'equip_bs') {
            $data['equipments'] = $this->equipmentQuery($company, $application, 'bs')->get();
        }

        if ($document === 'smap') {
            $data['sbu_application'] = $application;
        }

        return view($definition['view'], $data)->render();
    }

    private function generateArchive(Company $company, Application $application, string $document): GeneratedDocument
    {
        $definition = $this->documentDefinitions()[$document];
        $template = $this->findDocumentTemplate($definition['template']);
        $html = $this->renderDocumentHtml($company, $application, $document);
        $pdf = Pdf::loadHTML($html)->setPaper('a4', $definition['paper']);

        $fileName = $definition['filename'] . '_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        return $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => $definition['archive_type'],
            'file_path' => $filePath,
            'original_filename' => $definition['filename'] . '_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);
    }

    private function mainPjbu(Company $company)
    {
        return $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
    }

    private function statement(Company $company, Application $application)
    {
        return $company->balanceEntries()->where('sbu_application_id', $application->id)->first();
    }

    private function equipmentQuery(Company $company, Application $application, string $category)
    {
        return $company->equipment()
            ->where('sbu_application_id', $application->id)
            ->whereRaw('LOWER(category) = ?', [$category]);
    }

    /**
     * @return array{0: string|null, 1: string|null}
     */
    private function templateImages(string $templateKeyword): array
    {
        $template = $this->findDocumentTemplate($templateKeyword);
        $stampBase64 = null;
        $signatureBase64 = null;

        if (! $template) {
            return [$stampBase64, $signatureBase64];
        }

        if ($template->stamp_path) {
            $stampPath = storage_path('app/public/' . $template->stamp_path);
            if (file_exists($stampPath)) {
                $stampBase64 = 'data:image/' . pathinfo($stampPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($stampPath));
            }
        }

        if ($template->signature_path) {
            $signaturePath = storage_path('app/public/' . $template->signature_path);
            if (file_exists($signaturePath)) {
                $signatureBase64 = 'data:image/' . pathinfo($signaturePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($signaturePath));
            }
        }

        return [$stampBase64, $signatureBase64];
    }

    private function findDocumentTemplate(string $keyword): ?DocumentTemplate
    {
        return DocumentTemplate::where('is_active', true)
            ->where(function ($query) use ($keyword): void {
                $query->where('code', $keyword)
                    ->orWhere('name', 'like', "%{$keyword}%");
            })
            ->first();
    }

    private function formatIndonesianDate($date): string
    {
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];
        $timestamp = strtotime($date);

        return date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }
}
