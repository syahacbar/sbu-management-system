<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\Workspace\ApplicationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class ApplicationDocumentController extends Controller
{
    private array $allowedRequirements = [
        'Akta Pendirian',
        'Akta Perubahan',
        'NIB (Nomor Induk Berusaha)',
        'NPWP Perusahaan',
        'Neraca Keuangan',
        'Surat Pernyataan PJBU / PJT'
    ];

    public function upload(Request $request, Company $company, Application $application): RedirectResponse
    {
        $request->validate([
            'requirement_name' => ['required', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'], // Max 5MB
        ]);

        $reqName = $request->string('requirement_name')->toString();

        if (!in_array($reqName, $this->allowedRequirements, true)) {
            return redirect()->back()->withErrors(['requirement_name' => 'Persyaratan dokumen tidak valid.']);
        }

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();

            // Store file under storage/app/public/application_documents/
            $path = $file->store('application_documents', 'public');

            // Find existing document for this requirement
            $existing = $application->documents()->where('requirement_name', $reqName)->first();

            if ($existing) {
                // Delete old file from storage
                Storage::disk('public')->delete($existing->file_path);
                
                // Update record
                $existing->update([
                    'file_path' => $path,
                    'file_name' => $originalName,
                ]);
            } else {
                // Create new record
                $application->documents()->create([
                    'requirement_name' => $reqName,
                    'file_path' => $path,
                    'file_name' => $originalName,
                ]);
            }

            return redirect()->back()->with('status', "Dokumen {$reqName} berhasil diunggah.");

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Gagal mengunggah berkas: ' . $e->getMessage()]);
        }
    }

    public function destroy(Company $company, Application $application, ApplicationDocument $document): RedirectResponse
    {
        // Double check ownership
        if ((int) $document->company_application_id !== (int) $application->id) {
            abort(403, 'Akses ditolak.');
        }

        try {
            // Delete file from storage
            Storage::disk('public')->delete($document->file_path);
            
            // Delete record from database
            $document->delete();

            return redirect()->back()->with('status', "Dokumen {$document->requirement_name} berhasil dihapus.");
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus berkas: ' . $e->getMessage()]);
        }
    }

    public function download(Company $company, Application $application, ApplicationDocument $document): StreamedResponse
    {
        if ((int) $document->company_application_id !== (int) $application->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
