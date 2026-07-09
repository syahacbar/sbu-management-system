<?php

namespace App\Models\Workspace;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialStatement extends Model
{
    protected $table = 'financial_statements';

    protected $fillable = [
        'company_id',
        'sbu_application_id',
        'year_one',
        'year_two',
        'statement_date',
    ];

    protected function casts(): array
    {
        return [
            'year_one' => 'integer',
            'year_two' => 'integer',
            'statement_date' => 'date',
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

    public function values(): HasMany
    {
        return $this->hasMany(FinancialStatementValue::class, 'financial_statement_id');
    }

    /**
     * Helper to sum dynamic input items for a specific group and year
     */
    public function sumGroup(string $groupName, string $yearKey): float
    {
        $amountField = $yearKey === 'year_one' ? 'year_one_amount' : 'year_two_amount';
        
        return (float) $this->values()
            ->whereHas('masterItem', function ($query) use ($groupName) {
                $query->where('group_name', $groupName)
                    ->where('is_calculated', false);
            })
            ->sum($amountField);
    }

    // Calculations for Year One
    public function getTotalAktivaLancarYearOneAttribute(): float
    {
        return $this->sumGroup('lancar', 'year_one');
    }

    public function getTotalAktivaTetapYearOneAttribute(): float
    {
        return $this->sumGroup('tetap', 'year_one');
    }

    public function getTotalAktivaYearOneAttribute(): float
    {
        return $this->total_aktiva_lancar_year_one + $this->total_aktiva_tetap_year_one;
    }

    public function getTotalKewajibanYearOneAttribute(): float
    {
        return $this->sumGroup('kewajiban', 'year_one');
    }

    public function getTotalEkuitasYearOneAttribute(): float
    {
        return $this->sumGroup('ekuitas', 'year_one');
    }

    public function getKekayaanBersihYearOneAttribute(): float
    {
        return $this->total_aktiva_year_one - $this->total_kewajiban_year_one;
    }

    // Calculations for Year Two
    public function getTotalAktivaLancarYearTwoAttribute(): float
    {
        return $this->sumGroup('lancar', 'year_two');
    }

    public function getTotalAktivaTetapYearTwoAttribute(): float
    {
        return $this->sumGroup('tetap', 'year_two');
    }

    public function getTotalAktivaYearTwoAttribute(): float
    {
        return $this->total_aktiva_lancar_year_two + $this->total_aktiva_tetap_year_two;
    }

    public function getTotalKewajibanYearTwoAttribute(): float
    {
        return $this->sumGroup('kewajiban', 'year_two');
    }

    public function getTotalEkuitasYearTwoAttribute(): float
    {
        return $this->sumGroup('ekuitas', 'year_two');
    }

    public function getKekayaanBersihYearTwoAttribute(): float
    {
        return $this->total_aktiva_year_two - $this->total_kewajiban_year_two;
    }
}
