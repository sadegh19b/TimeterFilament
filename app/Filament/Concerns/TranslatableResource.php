<?php

namespace App\Filament\Concerns;

use Lang;

trait TranslatableResource
{
    public static function getModelLabel(): string
    {
        return static::getTranslateLabel('singular') ?? parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return static::getTranslateLabel('plural') ?? parent::getPluralModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return static::getTranslateLabel('navigation') ?? parent::getNavigationLabel();
    }

    private static function getTranslateLabel(string $type): ?string
    {
        $resourceName = static::getSlug();
        $translateKey = "app.resources.{$resourceName}.{$type}";

        return Lang::has($translateKey) ? Lang::get($translateKey) : null;
    }
}
