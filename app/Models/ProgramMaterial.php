<?php

namespace App\Models;

use App\Models\Concerns\HasAuditRelations;
use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramMaterial extends Model
{
    use HasAuditRelations, HasFactory, LogsModelActivity, SoftDeletes;

    protected $guarded = ['id'];

    public function programLevel(): BelongsTo
    {
        return $this->belongsTo(ProgramLevel::class);
    }
}
