<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterEquipment extends Model
{
    protected $table = 'master_equipments';

    protected $fillable = [
        'category',
        'code',
        'name',
        'specification',
        'unit',
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
