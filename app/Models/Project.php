<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasImage;

    public const MEDIA_DISK = 'media';
    public const LOGO_DIRECTORY = 'projects/logo';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'logo',
        'link',
        'jira_link',
    ];

    public function getImageFieldName(): string
    {
        return 'logo';
    }

    public function getImageDirectory(): ?string
    {
        return self::LOGO_DIRECTORY;
    }

    public function times(): HasMany
    {
        return $this->hasMany(Time::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
