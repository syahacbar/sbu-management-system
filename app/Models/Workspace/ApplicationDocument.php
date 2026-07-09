<?php

namespace App\Models\Workspace;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $table = 'application_documents';

    protected $fillable = [
        'company_id',
        'sbu_application_id',
        'document_type',
        'file_path',
        'original_filename',
        'document_date',
        'expired_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'expired_at' => 'date',
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

    /**
     * Backward compatibility accessor for requirement_name
     */
    public function getRequirementNameAttribute(): string
    {
        return $this->document_type;
    }

    /**
     * Backward compatibility mutator for requirement_name
     */
    public function setRequirementNameAttribute(string $value): void
    {
        $this->attributes['document_type'] = $value;
    }

    /**
     * Backward compatibility accessor for file_name
     */
    public function getFileNameAttribute(): ?string
    {
        return $this->original_filename;
    }

    /**
     * Backward compatibility mutator for file_name
     */
    public function setFileNameAttribute(?string $value): void
    {
        $this->attributes['original_filename'] = $value;
    }

    /**
     * Backward compatibility accessor for company_application_id
     */
    public function getCompanyApplicationIdAttribute(): ?int
    {
        return $this->sbu_application_id;
    }

    /**
     * Backward compatibility mutator for company_application_id
     */
    public function setCompanyApplicationIdAttribute(?int $value): void
    {
        $this->attributes['sbu_application_id'] = $value;
    }
}
