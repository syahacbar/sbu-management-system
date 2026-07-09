<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Models\MasterSbuScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $items = $company->applications()
            ->with(['kbli', 'classification', 'subclassification', 'scheme'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('application_number', 'like', "%{$search}%")
                        ->orWhere('application_type', 'like', "%{$search}%")
                        ->orWhere('lsbu_name', 'like', "%{$search}%")
                        ->orWhere('association_name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('workspace.applications.index', compact('company', 'items', 'search', 'status'));
    }

    public function create(Company $company): View
    {
        return view('workspace.applications.form', [
            'company' => $company,
            'item' => null,
            ...$this->formOptions()
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $validated = $this->validateAndCheckRelations($request);

        $year = (int) $validated['application_year'];
        $count = $company->applications()->where('application_year', $year)->count();
        $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        $validated['application_number'] = "PNJ-{$year}-{$seq}";

        // Jika ini pengajuan pertama, otomatis jadikan aktif
        $hasAny = $company->applications()->exists();
        $validated['is_active'] = !$hasAny;

        $company->applications()->create($validated);

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil dibuat.');
    }

    public function show(Company $company, Application $application): View
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $application->load(['kbli', 'classification', 'subclassification', 'scheme']);
        return view('workspace.applications.show', compact('company', 'application'));
    }

    public function edit(Company $company, Application $application): View
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        return view('workspace.applications.form', [
            'company' => $company,
            'item' => $application,
            ...$this->formOptions()
        ]);
    }

    public function update(Request $request, Company $company, Application $application): RedirectResponse
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $validated = $this->validateAndCheckRelations($request, $application);
        $application->update($validated);

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil diperbarui.');
    }

    public function destroy(Company $company, Application $application): RedirectResponse
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $application->delete();

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', 'Pengajuan SBU berhasil dihapus.');
    }

    public function activate(Company $company, Application $application): RedirectResponse
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        // Set all other applications of this company to is_active = false
        $company->applications()->update(['is_active' => false]);
        
        // Set this application to is_active = true
        $application->update(['is_active' => true]);

        return redirect()
            ->route('companies.workspace.applications.index', $company)
            ->with('status', "Pengajuan SBU {$application->application_number} telah dijadikan aktif.");
    }

    public function updateStatus(Company $company, Application $application): RedirectResponse
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        // Re-evaluate the checklist
        $requiredFields = ['name', 'npwp', 'nib', 'email', 'phone', 'business_type', 'qualification', 'province', 'city', 'district', 'village', 'rt_rw', 'street', 'signing_place'];
        $profileComplete = true;
        foreach ($requiredFields as $field) {
            if (empty($company->{$field})) {
                $profileComplete = false;
                break;
            }
        }

        $hasDirector = $company->directors()->exists();
        $hasPjbu = $company->pjbus()->exists();
        $hasPjtbu = $application->experts()->where('expert_type', 'pjtbu')->exists();
        $hasPjskbu = $application->experts()->where('expert_type', 'pjskbu')->exists();
        $hasEquipment = $company->equipment()->where('sbu_application_id', $application->id)->exists();
        $hasBalance = $company->balanceEntries()->where('sbu_application_id', $application->id)->exists();
        $hasDocuments = $company->documents()->where('sbu_application_id', $application->id)->exists();

        $allComplete = $profileComplete && $hasDirector && $hasPjbu && $hasPjtbu && $hasPjskbu && $hasEquipment && $hasBalance && $hasDocuments;

        $newStatus = $allComplete ? 'berkas_lengkap' : 'berkas_belum_lengkap';
        $application->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'berkas_lengkap' ? 'Berkas Lengkap' : 'Berkas Belum Lengkap';
        return redirect()->back()->with('status', "Status pengajuan berhasil diperbarui menjadi: {$statusLabel}");
    }

    public function previewSmap(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu sebelum membuat dokumen SMAP.');
        }

        $sbu_application = $application->load(['classification', 'subclassification', 'scheme']);
        $formattedDate = $this->formatIndonesianDate(now());

        return response(view('pdf.smap', compact('company', 'pjbu', 'sbu_application', 'formattedDate'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadSmap(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu sebelum membuat dokumen SMAP.');
        }

        $sbu_application = $application->load(['classification', 'subclassification', 'scheme']);
        $formattedDate = $this->formatIndonesianDate(now());
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)
            ->where(function ($query): void {
                $query->where('code', 'SMAP')
                    ->orWhere('name', 'like', '%SMAP%');
            })
            ->first();

        $html = view('pdf.smap', compact('company', 'pjbu', 'sbu_application', 'formattedDate'))->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'SMAP_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'SMAP (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'SMAP_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('SMAP_' . $application->application_number . '.pdf');
    }

    public function previewSptjm(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu sebelum membuat SPTJM.');
        }

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%SPTJM%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        return response(view('pdf.sptjm', compact('company', 'application', 'pjbu', 'formattedDate', 'stampBase64', 'signatureBase64'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadSptjm(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu sebelum membuat SPTJM.');
        }

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%SPTJM%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        $html = view('pdf.sptjm', compact('company', 'application', 'pjbu', 'formattedDate', 'stampBase64', 'signatureBase64'))->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'SPTJM_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        // Store file in public storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'SPTJM (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'SPTJM_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('SPTJM_' . $application->application_number . '.pdf');
    }

    public function previewTenagaAhli(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $pjtbuList = $application->experts()->where('expert_type', 'pjtbu')->get();
        $pjskbuList = $application->experts()->where('expert_type', 'pjskbu')->get();

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%lampiran%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        return response(view('pdf.lampiran-tenaga-ahli', compact('company', 'application', 'pjbu', 'pjtbuList', 'pjskbuList', 'formattedDate', 'stampBase64', 'signatureBase64'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadTenagaAhli(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $pjtbuList = $application->experts()->where('expert_type', 'pjtbu')->get();
        $pjskbuList = $application->experts()->where('expert_type', 'pjskbu')->get();

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%lampiran%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        $html = view('pdf.lampiran-tenaga-ahli', compact('company', 'application', 'pjbu', 'pjtbuList', 'pjskbuList', 'formattedDate', 'stampBase64', 'signatureBase64'))->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'Lampiran_TA_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        // Store file in public storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'Lampiran Tenaga Ahli (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'Lampiran_TA_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('Lampiran_TA_' . $application->application_number . '.pdf');
    }

    public function previewNeraca(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $statement = $company->balanceEntries()->where('sbu_application_id', $application->id)->first();
        if (!$statement) {
            return redirect()->back()->with('error', 'Belum ada data Neraca Keuangan terdaftar untuk pengajuan ini. Silakan buat laporan neraca terlebih dahulu.');
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $formattedDate = $this->formatIndonesianDate($statement->statement_date ?: now());

        $values = $statement->values()->with('masterItem')->get();
        $aktivaLancar = $values->filter(fn($v) => $v->masterItem->section === 'aktiva' && $v->masterItem->group_name === 'lancar');
        $aktivaTetap = $values->filter(fn($v) => $v->masterItem->section === 'aktiva' && $v->masterItem->group_name === 'tetap');
        $kewajiban = $values->filter(fn($v) => $v->masterItem->section === 'pasiva' && $v->masterItem->group_name === 'kewajiban');
        $ekuitas = $values->filter(fn($v) => $v->masterItem->section === 'pasiva' && $v->masterItem->group_name === 'ekuitas');

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%neraca%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        return response(view('pdf.neraca', compact('company', 'application', 'pjbu', 'statement', 'aktivaLancar', 'aktivaTetap', 'kewajiban', 'ekuitas', 'formattedDate', 'stampBase64', 'signatureBase64'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadNeraca(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $statement = $company->balanceEntries()->where('sbu_application_id', $application->id)->first();
        if (!$statement) {
            return redirect()->back()->with('error', 'Belum ada data Neraca Keuangan terdaftar untuk pengajuan ini. Silakan buat laporan neraca terlebih dahulu.');
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $formattedDate = $this->formatIndonesianDate($statement->statement_date ?: now());

        $values = $statement->values()->with('masterItem')->get();
        $aktivaLancar = $values->filter(fn($v) => $v->masterItem->section === 'aktiva' && $v->masterItem->group_name === 'lancar');
        $aktivaTetap = $values->filter(fn($v) => $v->masterItem->section === 'aktiva' && $v->masterItem->group_name === 'tetap');
        $kewajiban = $values->filter(fn($v) => $v->masterItem->section === 'pasiva' && $v->masterItem->group_name === 'kewajiban');
        $ekuitas = $values->filter(fn($v) => $v->masterItem->section === 'pasiva' && $v->masterItem->group_name === 'ekuitas');

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%neraca%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        $html = view('pdf.neraca', compact('company', 'application', 'pjbu', 'statement', 'aktivaLancar', 'aktivaTetap', 'kewajiban', 'ekuitas', 'formattedDate', 'stampBase64', 'signatureBase64'))->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        $fileName = 'Neraca_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        // Store file in public storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'Neraca Keuangan (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'Neraca_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('Neraca_' . $application->application_number . '.pdf');
    }

    public function previewAlatBg(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $equipments = $company->equipment()->where('sbu_application_id', $application->id)->whereRaw('LOWER(category) = ?', ['bg'])->get();

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%peralatan%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        return response(view('pdf.surat-alat-bg', compact('company', 'application', 'pjbu', 'equipments', 'formattedDate', 'stampBase64', 'signatureBase64'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadAlatBg(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $equipments = $company->equipment()->where('sbu_application_id', $application->id)->whereRaw('LOWER(category) = ?', ['bg'])->get();

        $formattedDate = $this->formatIndonesianDate(now());

        // Get stamp/TTE if template is available
        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%peralatan%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        $html = view('pdf.surat-alat-bg', compact('company', 'application', 'pjbu', 'equipments', 'formattedDate', 'stampBase64', 'signatureBase64'))->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'Surat_Alat_BG_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        // Store file in public storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'Surat Alat BG (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'Surat_Alat_BG_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('Surat_Alat_BG_' . $application->application_number . '.pdf');
    }

    public function previewAlatBs(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $equipments = $company->equipment()->where('sbu_application_id', $application->id)->whereRaw('LOWER(category) = ?', ['bs'])->get();

        $formattedDate = $this->formatIndonesianDate(now());

        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%peralatan%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        return response(view('pdf.surat-alat-bs', compact('company', 'application', 'pjbu', 'equipments', 'formattedDate', 'stampBase64', 'signatureBase64'))->render(), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function downloadAlatBs(Company $company, Application $application)
    {
        if ((int) $application->company_id !== (int) $company->id) {
            abort(403);
        }

        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
        if (!$pjbu) {
            return redirect()->back()->with('error', 'Belum ada Penanggung Jawab Badan Usaha (PJBU) terdaftar. Silakan daftarkan PJBU utama terlebih dahulu.');
        }

        $equipments = $company->equipment()->where('sbu_application_id', $application->id)->whereRaw('LOWER(category) = ?', ['bs'])->get();

        $formattedDate = $this->formatIndonesianDate(now());

        $template = \App\Models\Master\DocumentTemplate::where('is_active', true)->where('name', 'like', '%peralatan%')->first();
        $stampBase64 = null;
        $signatureBase64 = null;
        if ($template) {
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
        }

        $html = view('pdf.surat-alat-bs', compact('company', 'application', 'pjbu', 'equipments', 'formattedDate', 'stampBase64', 'signatureBase64'))->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $fileName = 'Surat_Alat_BS_' . time() . '_' . uniqid() . '.pdf';
        $filePath = "generated-documents/{$company->id}/" . $fileName;

        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        $company->archives()->create([
            'sbu_application_id' => $application->id,
            'document_template_id' => $template?->id,
            'document_type' => 'Surat Alat BS (PDF)',
            'file_path' => $filePath,
            'original_filename' => 'Surat_Alat_BS_' . $application->application_number . '.pdf',
            'generated_at' => now(),
        ]);

        return $pdf->download('Surat_Alat_BS_' . $application->application_number . '.pdf');
    }

    private function formatIndonesianDate($date): string
    {
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $timestamp = strtotime($date);
        $d = date('j', $timestamp);
        $m = $months[(int)date('n', $timestamp)];
        $y = date('Y', $timestamp);
        return "{$d} {$m} {$y}";
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'kblis' => MasterKbli::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'classifications' => MasterSbuClassification::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'subclassifications' => MasterSbuSubclassification::where('is_active', true)->orderBy('sort_order')->orderBy('code')->get(),
            'schemes' => MasterSbuScheme::where('is_active', true)->orderBy('sort_order')->orderBy('scheme_code')->get(),
            'statuses' => [
                'draft' => 'Draft',
                'berkas_belum_lengkap' => 'Berkas Belum Lengkap',
                'berkas_lengkap' => 'Berkas Lengkap',
                'proses' => 'Proses',
                'revisi' => 'Revisi',
                'terbit' => 'Terbit',
                'selesai' => 'Selesai',
            ],
            'types' => [
                'baru' => 'Baru',
                'perpanjangan' => 'Perpanjangan',
                'perubahan' => 'Perubahan',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateAndCheckRelations(Request $request, ?Application $application = null): array
    {
        $validated = $request->validate([
            'application_type' => ['required', 'string', Rule::in(['baru', 'perpanjangan', 'perubahan'])],
            'submission_date' => ['nullable', 'date'],
            'application_year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'master_kbli_id' => ['required', 'exists:master_kblis,id'],
            'master_sbu_classification_id' => ['required', 'exists:master_sbu_classifications,id'],
            'master_sbu_subclassification_id' => ['required', 'exists:master_sbu_subclassifications,id'],
            'master_sbu_scheme_id' => ['required', 'exists:master_sbu_schemes,id'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'lsbu_name' => ['nullable', 'string', 'max:255'],
            'association_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(['draft', 'berkas_belum_lengkap', 'berkas_lengkap', 'proses', 'revisi', 'terbit', 'selesai'])],
            'notes' => ['nullable', 'string'],
        ]);

        // Cross-check relation Classification <-> Subclassification
        $subMatches = MasterSbuSubclassification::query()
            ->whereKey($validated['master_sbu_subclassification_id'])
            ->where('master_sbu_classification_id', $validated['master_sbu_classification_id'])
            ->exists();

        if (!$subMatches) {
            throw ValidationException::withMessages([
                'master_sbu_subclassification_id' => 'Subklasifikasi yang dipilih tidak sesuai dengan Klasifikasi SBU.',
            ]);
        }

        // Cross-check relation Scheme <-> KBLI, Classification, Subclassification
        $schemeMatches = MasterSbuScheme::query()
            ->whereKey($validated['master_sbu_scheme_id'])
            ->where('master_kbli_id', $validated['master_kbli_id'])
            ->where('master_sbu_classification_id', $validated['master_sbu_classification_id'])
            ->where('master_sbu_subclassification_id', $validated['master_sbu_subclassification_id'])
            ->exists();

        if (!$schemeMatches) {
            throw ValidationException::withMessages([
                'master_sbu_scheme_id' => 'Skema SBU yang dipilih tidak sesuai dengan KBLI, Klasifikasi, dan Subklasifikasi.',
            ]);
        }

        return $validated;
    }
}
