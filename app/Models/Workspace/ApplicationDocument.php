<?php

namespace App\Models\Workspace;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $table = 'application_documents';

    protected $fillable = [
        'company_application_id',
        'requirement_name',
        'file_path',
        'file_name',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'company_application_id');
    }
}
