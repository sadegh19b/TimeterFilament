<?php

namespace App\Models\Concerns;

use Storage;

trait HasImage
{
    protected string $imageFieldName = 'image';

    public function getImageUrlAttribute(): ?string
    {
        return $this->getImageField() && Storage::disk($this->getImageDisk())->exists($this->$this->getImageField())
            ? Storage::disk($this->getImageDisk())->url($this->getImageField())
            : $this->defaultImageUrl();
    }

    public function removeImage(): bool
    {
        return Storage::disk($this->getImageDisk())->delete($this->getImageField())
            && $this->isImageNullable() ? $this->update([$this->getImageFieldName() => null]) : true;
    }

    public function getImageField(): string
    {
        $imageFieldName = $this->getImageFieldName();

        return $this->{$imageFieldName};
    }

    public function getImageFieldName(): string
    {
        return $this->imageFieldName;
    }

    public function setImageFieldName(string $fieldName): string
    {
        return $this->imageFieldName = $fieldName;
    }

    public function getImageDisk(): string
    {
        return 'media';
    }

    public function getImageDirectory(): ?string
    {
        return null;
    }

    public function defaultImageUrl(): ?string
    {
        return null;
    }

    public function isImageNullable(): bool
    {
        return true;
    }
}
