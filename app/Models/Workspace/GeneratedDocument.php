<?php

namespace App\Models\Workspace;

use App\Models\Company;
use App\Models\Master\DocumentTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedDocument extends Model
{
    protected $table = 'generated_documents';

    protected $fillable = [
        'company_id',
        'sbu_application_id',
        'document_template_id',
        'document_type',
        'file_path',
        'original_filename',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'sbu_application_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class, 'document_template_id');
    }
}
