<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

abstract class MasterReference extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
        'sort_order',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
