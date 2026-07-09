<?php

namespace App\Models\Workspace;

use App\Models\Master\MasterFinancialItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialStatementValue extends Model
{
    protected $table = 'financial_statement_values';

    protected $fillable = [
        'financial_statement_id',
        'master_financial_item_id',
        'year_one_amount',
        'year_two_amount',
    ];

    protected function casts(): array
    {
        return [
            'year_one_amount' => 'float',
            'year_two_amount' => 'float',
        ];
    }

    public function statement(): BelongsTo
    {
        return $this->belongsTo(FinancialStatement::class, 'financial_statement_id');
    }

    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(MasterFinancialItem::class, 'master_financial_item_id');
    }
}
