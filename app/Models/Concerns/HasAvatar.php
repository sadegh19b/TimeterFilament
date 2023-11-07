<?php

namespace App\Models\Concerns;

use Storage;

trait HasAvatar
{
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar && Storage::disk($this->getAvatarDisk())->exists($this->avatar)
            ? Storage::disk($this->getAvatarDisk())->url($this->avatar)
            : $this->defaultAvatarUrl();
    }

    public function removeAvatar(): bool
    {
        return Storage::disk($this->getAvatarDisk())->delete($this->avatar) && $this->update(['avatar' => null]);
    }

    protected function defaultAvatarUrl(): string
    {
        return asset('media/avatars/default-avatar.png');
    }

    public function getAvatarDisk(): string
    {
        return 'media';
    }

    public function getAvatarDirectory(): string
    {
        return 'avatars';
    }
}
