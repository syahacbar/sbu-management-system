<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Company;
use App\Models\Workspace\Application;
use App\Models\Workspace\ApplicationDocument;
use App\Models\Workspace\GeneratedDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $settings = Setting::getCached();

        $info = [
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'database' => config('database.default'),
            'environment' => app()->environment(),
            'storage_used' => $this->formatBytes($this->getStorageUsed()),
            'total_companies' => Company::count(),
            'total_applications' => Application::count(),
            'total_documents' => ApplicationDocument::count(),
            'total_archives' => GeneratedDocument::count(),
        ];

        return view('admin.settings.index', compact('settings', 'user', 'info'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tab = $request->input('tab', 'profile');

        $rules = match ($tab) {
            'app-profile' => [
                'app_name' => ['required', 'string', 'max:255'],
                'app_company_name' => ['required', 'string', 'max:255'],
                'app_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
                'app_favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:1024'],
                'app_address' => ['nullable', 'string', 'max:500'],
                'app_phone' => ['nullable', 'string', 'max:50'],
                'app_email' => ['nullable', 'email', 'max:255'],
                'app_website' => ['nullable', 'url', 'max:255'],
                'app_footer' => ['nullable', 'string', 'max:500'],
                'app_copyright' => ['nullable', 'string', 'max:500'],
            ],
            'documents' => [
                'doc_city_default' => ['nullable', 'string', 'max:255'],
                'doc_number_format' => ['nullable', 'string', 'max:255'],
                'app_prefix_number' => ['nullable', 'string', 'max:50'],
                'doc_default_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                'doc_date_format' => ['nullable', 'string', 'max:50'],
                'doc_paper_size' => ['nullable', 'string', 'max:50'],
                'doc_margin_pdf' => ['nullable', 'string', 'max:50'],
                'doc_orientation' => ['nullable', 'string', 'max:20'],
            ],
            'storage' => [
                'storage_upload_folder' => ['nullable', 'string', 'max:255'],
                'storage_archive_folder' => ['nullable', 'string', 'max:255'],
                'storage_max_upload_size' => ['nullable', 'integer', 'min:1', 'max:1024'],
                'storage_allowed_types' => ['nullable', 'string', 'max:500'],
            ],
            'backup' => [
                'backup_db_folder' => ['nullable', 'string', 'max:255'],
                'backup_doc_folder' => ['nullable', 'string', 'max:255'],
            ],
            default => [],
        };

        $validated = $request->validate($rules);

        $settings = [];

        foreach ($validated as $key => $value) {
            $settings[$key] = ['value' => $value, 'type' => 'string'];
        }

        if ($tab === 'app-profile') {
            if ($request->hasFile('app_logo')) {
                $path = $request->file('app_logo')->store('settings', 'public');
                $settings['app_logo'] = ['value' => $path, 'type' => 'file'];
            }

            if ($request->hasFile('app_favicon')) {
                $path = $request->file('app_favicon')->store('settings', 'public');
                $settings['app_favicon'] = ['value' => $path, 'type' => 'file'];
            }
        }

        Setting::setMany($settings);

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        User::where('id', $user->id)->update($data);

        return back()->with('status', 'Profil admin berhasil diperbarui.');
    }

    public function backupDatabase(): RedirectResponse
    {
        $backupFolder = Setting::get('backup_db_folder', storage_path('backups/database'));
        $filename = 'backup-db-' . now()->format('Y-m-d-His') . '.sql';

        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0755, true);
        }

        $db = config('database.connections.mysql');
        $command = sprintf(
            '"%s" -h %s -u %s %s %s > "%s" 2>&1',
            $this->findMysqldump(),
            $db['host'],
            $db['username'],
            $db['password'] ? '-p"' . $db['password'] . '"' : '',
            $db['database'],
            $backupFolder . DIRECTORY_SEPARATOR . $filename,
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return back()->with('error', 'Backup database gagal. Pastikan mysqldump tersedia.');
        }

        return back()->with('status', 'Backup database berhasil: ' . $filename);
    }

    public function backupStorage(): RedirectResponse
    {
        $backupFolder = Setting::get('backup_doc_folder', storage_path('backups/documents'));
        $filename = 'backup-storage-' . now()->format('Y-m-d-His') . '.zip';

        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0755, true);
        }

        $zip = new \ZipArchive();
        $zipPath = $backupFolder . DIRECTORY_SEPARATOR . $filename;

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return back()->with('error', 'Gagal membuat file zip.');
        }

        $storagePath = storage_path('app');
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY,
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $relativePath = 'storage/' . substr($file->getPathname(), strlen($storagePath) + 1);
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }

        $zip->close();

        return back()->with('status', 'Backup storage berhasil: ' . $filename);
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    private function getStorageUsed(): int
    {
        $path = storage_path('app');
        $total = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $total += $file->getSize();
            }
        }

        return $total;
    }

    private function findMysqldump(): string
    {
        $paths = [
            'mysqldump',
            '"C:\\laragon\\bin\\mysql\\bin\\mysqldump.exe"',
            '"C:\\xampp\\mysql\\bin\\mysqldump.exe"',
        ];

        foreach ($paths as $path) {
            $testPath = trim($path, '"');
            if (file_exists($testPath)) {
                return $path;
            }
        }

        return 'mysqldump';
    }
}
