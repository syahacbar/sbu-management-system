<?php

namespace App\Models\Workspace;

use App\Models\Company;
use App\Models\Master\MasterEquipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyEquipment extends Model
{
    protected $table = 'company_equipments';

    protected $fillable = [
        'company_id',
        'sbu_application_id',
        'master_equipment_id',
        'category',
        'name',
        'specification',
        'quantity',
        'unit',
        'ownership_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'sbu_application_id');
    }

    public function masterEquipment(): BelongsTo
    {
        return $this->belongsTo(MasterEquipment::class, 'master_equipment_id');
    }
}
