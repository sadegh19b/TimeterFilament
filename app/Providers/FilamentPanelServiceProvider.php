<?php

namespace App\Providers;

use App\Filament\Pages;
use Filament\Widgets;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FilamentPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('panel')
            ->path('/')
            ->login()
            ->colors([
                'primary'   => '#003c71',
                'secondary' => '#007db3',
                'danger'    => Color::Rose,
                'gray'      => Color::Gray,
                'info'      => Color::Blue,
                'success'   => Color::Emerald,
                'warning'   => Color::Orange,
                'golden'    => Color::Amber,
            ])
            ->favicon(asset('favicon.png'))
            ->font('Vazirmatn, sans-serif', asset('css/Vazirmatn-font-face.css'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTheme::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                ThemesPlugin::make()
            ]);
    }

    public function boot(): void
    {
        Filament::getDefaultPanel()->brandName(__('app.titles.panel'));

        Filament::serving(function () {
            Filament::getDefaultPanel()->userMenuItems([
                'account' => MenuItem::make()->url(Pages\EditProfile::getUrl()),
            ]);
        });
    }
}
