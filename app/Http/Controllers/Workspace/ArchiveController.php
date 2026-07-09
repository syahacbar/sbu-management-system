<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Workspace\Archive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class ArchiveController extends Controller
{
    public function index(Request $request, Company $company): View
    {
        $search = $request->string('search')->toString();

        $items = $company->archives()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('record_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('workspace.archives.index', compact('company', 'items', 'search'));
    }

    public function show(Company $company, Archive $archive): View
    {
        if ((int) $archive->company_id !== (int) $company->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('workspace.archives.show', compact('company', 'archive'));
    }

    public function destroy(Company $company, Archive $archive): RedirectResponse
    {
        if ((int) $archive->company_id !== (int) $company->id) {
            abort(403, 'Akses ditolak.');
        }

        try {
            $archive->delete();
            return redirect()
                ->route('companies.workspace.archives.index', $company)
                ->with('status', 'Dokumen arsip berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus arsip: ' . $e->getMessage()]);
        }
    }
}
