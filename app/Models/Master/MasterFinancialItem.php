<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterFinancialItem extends Model
{
    protected $table = 'master_financial_items';

    protected $fillable = [
        'code',
        'name',
        'section',
        'group_name',
        'is_calculated',
        'is_active',
        'sort_order',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_calculated' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
