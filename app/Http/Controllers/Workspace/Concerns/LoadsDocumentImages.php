<?php

namespace App\Http\Controllers\Workspace\Concerns;

use App\Models\Master\DocumentTemplate;

trait LoadsDocumentImages
{
    /**
     * @return array{0: string|null, 1: string|null}
     */
    private function loadDocumentImages(string $templateKeyword): array
    {
        $template = DocumentTemplate::where('is_active', true)
            ->where(function ($query) use ($templateKeyword): void {
                $query->where('code', $templateKeyword)
                    ->orWhere('name', 'like', "%{$templateKeyword}%");
            })
            ->first();

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
}
