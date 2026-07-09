<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'description', 'is_active', 'sort_order'])]
class MasterSbuClassification extends Model
{
    protected $table = 'master_sbu_classifications';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function subclassifications(): HasMany
    {
        return $this->hasMany(MasterSbuSubclassification::class, 'master_sbu_classification_id');
    }

    public function schemes(): HasMany
    {
        return $this->hasMany(MasterSbuScheme::class, 'master_sbu_classification_id');
    }
}
