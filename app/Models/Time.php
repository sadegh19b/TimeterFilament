<?php

namespace App\Models;

use App\Utils\TimeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Time extends Model
{
    protected $fillable = [
        'project_id',
        'date',
        'start',
        'end',
        'jira_issue',
        'task',
        'description',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->total = 0;

            if (! is_null($model->end)) {
                $start = TimeHelper::hoursMinutesToTimestamp($model->start);
                $end = TimeHelper::hoursMinutesToTimestamp($model->end);

                $model->total = $end - $start;
            }

            $model->saveQuietly();
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
