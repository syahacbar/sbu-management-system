<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Exception;

class MasterDocumentTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $templates = DocumentTemplate::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('master.document-templates.index', compact('templates', 'search'));
    }

    public function create(): View
    {
        return view('master.document-templates.form', [
            'item' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', 'unique:master_document_templates,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'header_text' => ['nullable', 'string'],
            'template_body' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'stamp' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('logo')) {
                $validated['logo_path'] = $request->file('logo')->store('templates/logos', 'public');
            }
            if ($request->hasFile('signature')) {
                $validated['signature_path'] = $request->file('signature')->store('templates/signatures', 'public');
            }
            if ($request->hasFile('stamp')) {
                $validated['stamp_path'] = $request->file('stamp')->store('templates/stamps', 'public');
            }

            DocumentTemplate::create($validated);

            return redirect()
                ->route('master.document-templates.index')
                ->with('status', 'Template Dokumen berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(DocumentTemplate $documentTemplate): View
    {
        return view('master.document-templates.form', [
            'item' => $documentTemplate,
        ]);
    }

    public function update(Request $request, DocumentTemplate $documentTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', Rule::unique('master_document_templates', 'code')->ignore($documentTemplate->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'header_text' => ['nullable', 'string'],
            'template_body' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'stamp' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('logo')) {
                if ($documentTemplate->logo_path) {
                    Storage::disk('public')->delete($documentTemplate->logo_path);
                }
                $validated['logo_path'] = $request->file('logo')->store('templates/logos', 'public');
            }

            if ($request->hasFile('signature')) {
                if ($documentTemplate->signature_path) {
                    Storage::disk('public')->delete($documentTemplate->signature_path);
                }
                $validated['signature_path'] = $request->file('signature')->store('templates/signatures', 'public');
            }

            if ($request->hasFile('stamp')) {
                if ($documentTemplate->stamp_path) {
                    Storage::disk('public')->delete($documentTemplate->stamp_path);
                }
                $validated['stamp_path'] = $request->file('stamp')->store('templates/stamps', 'public');
            }

            $documentTemplate->update($validated);

            return redirect()
                ->route('master.document-templates.index')
                ->with('status', 'Template Dokumen berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy(DocumentTemplate $documentTemplate): RedirectResponse
    {
        try {
            if ($documentTemplate->logo_path) {
                Storage::disk('public')->delete($documentTemplate->logo_path);
            }
            if ($documentTemplate->signature_path) {
                Storage::disk('public')->delete($documentTemplate->signature_path);
            }
            if ($documentTemplate->stamp_path) {
                Storage::disk('public')->delete($documentTemplate->stamp_path);
            }

            $documentTemplate->delete();

            return redirect()
                ->route('master.document-templates.index')
                ->with('status', 'Template Dokumen berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}
