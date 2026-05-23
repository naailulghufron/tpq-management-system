<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[Fillable(['group', 'key', 'value', 'type', 'description', 'is_public'])]
class Setting extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('settings')
            ->logOnly(['group', 'key', 'value', 'type', 'description', 'is_public'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }
}
