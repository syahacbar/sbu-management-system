<?php

namespace App\Models\Workspace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationExpert extends Model
{
    protected $table = 'application_experts';

    protected $fillable = [
        'sbu_application_id',
        'expert_type',
        'name',
        'nik',
        'npwp',
        'npwp_clean',
        'skk_registration_number',
        'skk_classification',
        'skk_subclassification',
        'skk_qualification',
        'skk_level',
        'skk_issued_at',
        'skk_expired_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'skk_issued_at' => 'date',
            'skk_expired_at' => 'date',
        ];
    }

    public function setNpwpAttribute(?string $value): void
    {
        $this->attributes['npwp'] = $value;
        $this->attributes['npwp_clean'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'sbu_application_id');
    }
}
