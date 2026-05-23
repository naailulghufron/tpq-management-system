<?php

namespace App\Models;

use App\Models\Concerns\HasAuditRelations;
use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentProgress extends Model
{
    use HasAuditRelations, HasFactory, LogsModelActivity, SoftDeletes;

    protected $table = 'student_progress';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function studentProgram(): BelongsTo
    {
        return $this->belongsTo(StudentProgram::class);
    }

    public function programMaterial(): BelongsTo
    {
        return $this->belongsTo(ProgramMaterial::class);
    }
}
