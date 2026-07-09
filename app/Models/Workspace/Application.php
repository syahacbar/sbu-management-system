<?php

namespace App\Models\Workspace;

use App\Models\Company;
use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Models\MasterSbuScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $table = 'sbu_applications';

    protected $fillable = [
        'company_id',
        'application_number',
        'application_type',
        'submission_date',
        'application_year',
        'master_kbli_id',
        'master_sbu_classification_id',
        'master_sbu_subclassification_id',
        'master_sbu_scheme_id',
        'qualification',
        'lsbu_name',
        'association_name',
        'status',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'application_year' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function kbli(): BelongsTo
    {
        return $this->belongsTo(MasterKbli::class, 'master_kbli_id');
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(MasterSbuClassification::class, 'master_sbu_classification_id');
    }

    public function subclassification(): BelongsTo
    {
        return $this->belongsTo(MasterSbuSubclassification::class, 'master_sbu_subclassification_id');
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(MasterSbuScheme::class, 'master_sbu_scheme_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class, 'sbu_application_id');
    }

    public function experts(): HasMany
    {
        return $this->hasMany(ApplicationExpert::class, 'sbu_application_id');
    }
}
