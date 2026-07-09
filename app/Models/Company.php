<?php

namespace App\Models;

use App\Models\Workspace\Archive;
use App\Models\Workspace\Application;
use App\Models\Workspace\BalanceEntry;
use App\Models\Workspace\Director;
use App\Models\Workspace\Document;
use App\Models\Workspace\Equipment;
use App\Models\Workspace\Expert;
use App\Models\Workspace\Pjbu;
use App\Models\Workspace\Pjskbu;
use App\Models\Workspace\Pjtbu;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'nib', 'npwp', 'email', 'phone', 'address', 'is_active', 'description'])]
class Company extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function directors(): HasMany
    {
        return $this->hasMany(Director::class);
    }

    public function pjbus(): HasMany
    {
        return $this->hasMany(Pjbu::class);
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
        return $this->hasMany(Equipment::class);
    }

    public function balanceEntries(): HasMany
    {
        return $this->hasMany(BalanceEntry::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }
}
