<?php

namespace App\Models;

use App\Models\Workspace\GeneratedDocument;
use App\Models\Workspace\Application;
use App\Models\Workspace\FinancialStatement;
use App\Models\Workspace\CompanyPerson;
use App\Models\Workspace\ApplicationDocument;
use App\Models\Workspace\CompanyEquipment;
use App\Models\Workspace\Expert;
use App\Models\Workspace\Pjskbu;
use App\Models\Workspace\Pjtbu;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'npwp',
    'npwp_clean',
    'nib',
    'email',
    'phone',
    'business_type',
    'qualification',
    'province',
    'city',
    'district',
    'village',
    'rt_rw',
    'street',
    'signing_place',
    'notes'
])]
class Company extends Model
{
    protected function casts(): array
    {
        return [];
    }

    public function setNpwpAttribute($value): void
    {
        $this->attributes['npwp'] = $value;
        $this->attributes['npwp_clean'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function getAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->rt_rw ? 'RT/RW ' . $this->rt_rw : null,
            $this->village ? 'Kel. ' . $this->village : null,
            $this->district ? 'Kec. ' . $this->district : null,
            $this->city,
            $this->province,
        ]);
        return implode(', ', $parts);
    }

    public function persons(): HasMany
    {
        return $this->hasMany(CompanyPerson::class);
    }

    public function directors(): HasMany
    {
        return $this->hasMany(CompanyPerson::class)->where('type', 'direktur');
    }

    public function pjbus(): HasMany
    {
        return $this->hasMany(CompanyPerson::class)->where('type', 'pjbu');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function pjtbus(): HasMany
    {
        return $this->hasMany(Pjtbu::class);
    }

    public function pjskbus(): HasMany
    {
        return $this->hasMany(Pjskbu::class);
    }

    public function experts(): HasMany
    {
        return $this->hasMany(Expert::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(CompanyEquipment::class);
    }

    public function balanceEntries(): HasMany
    {
        return $this->hasMany(FinancialStatement::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function archives(): HasMany
    {
        return $this->hasMany(GeneratedDocument::class);
    }
}
