<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'master_kbli_id',
    'master_sbu_classification_id',
    'master_sbu_subclassification_id',
    'scheme_code',
    'scheme_name',
    'qualification',
    'description',
    'is_active',
    'sort_order',
])]
class MasterSbuScheme extends Model
{
    protected $table = 'master_sbu_schemes';

    public const QUALIFICATIONS = ['Kecil', 'Menengah', 'Besar'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
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
}
