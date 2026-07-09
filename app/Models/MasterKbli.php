<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'description', 'is_active', 'sort_order'])]
class MasterKbli extends Model
{
    protected $table = 'master_kblis';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function sbuSchemes(): HasMany
    {
        return $this->hasMany(MasterSbuScheme::class, 'master_kbli_id');
    }
}
