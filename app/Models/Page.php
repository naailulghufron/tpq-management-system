<?php

namespace App\Models;

use App\Models\Concerns\HasAuditRelations;
use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasAuditRelations, HasFactory, LogsModelActivity, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }
}
