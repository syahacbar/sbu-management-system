<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['master_sbu_classification_id', 'code', 'name', 'description', 'is_active', 'sort_order'])]
class MasterSbuSubclassification extends Model
{
    protected $table = 'master_sbu_subclassifications';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(MasterSbuClassification::class, 'master_sbu_classification_id');
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(MasterSbuScheme::class, 'master_sbu_subclassification_id');
    }
}
