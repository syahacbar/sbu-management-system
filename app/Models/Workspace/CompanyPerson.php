<?php

namespace App\Models\Workspace;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPerson extends Model
{
    protected $table = 'company_persons';

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'nik',
        'birthplace',
        'npwp',
        'npwp_clean',
        'email',
        'position',
        'is_main',
    ];

    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function setNpwpAttribute($value): void
    {
        $this->attributes['npwp'] = $value;
        $this->attributes['npwp_clean'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }
}
