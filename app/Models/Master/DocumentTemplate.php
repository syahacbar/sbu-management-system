<?php

namespace App\Models\Master;

class DocumentTemplate extends MasterReference
{
    protected $table = 'master_document_templates';

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'sort_order',
        'description',
        'header_text',
        'logo_path',
        'signature_path',
        'stamp_path',
        'template_body',
        'footer_text',
    ];

    /**
     * Render the template body by replacing placeholders with actual data.
     *
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(array $data): string
    {
        $html = $this->template_body ?? '';
        foreach ($data as $key => $val) {
            $html = str_replace('{' . $key . '}', (string) $val, $html);
        }
        return $html;
    }
}
