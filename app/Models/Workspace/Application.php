<?php

namespace App\Models\Workspace;

use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Models\MasterSbuScheme;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends WorkspaceRecord
{
    protected $table = 'company_applications';

    protected $fillable = [
        'master_kbli_id',
        'master_sbu_classification_id',
        'master_sbu_subclassification_id',
        'master_sbu_scheme_id',
        'code',
        'name',
        'status',
        'record_date',
        'amount',
        'is_active',
        'sort_order',
        'description',
    ];

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
        return $this->hasMany(ApplicationDocument::class, 'company_application_id');
    }
}
