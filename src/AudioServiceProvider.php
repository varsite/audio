<?php

declare(strict_types=1);

namespace Varsite\Audio;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Varsite\Audio\Models\AudioCategory;
use Varsite\Audio\Models\AudioTrack;
use Varsite\Audio\Policies\AudioCategoryPolicy;
use Varsite\Audio\Policies\AudioTrackPolicy;
use Varsite\Platform\Routing\ModuleRouteRegistrar;
use Varsite\Platform\Support\ModuleManager;
use Varsite\Platform\Capabilities\Action;
use Varsite\Platform\Capabilities\Column;
use Varsite\Platform\Capabilities\Field;
use Varsite\Platform\Capabilities\Filter;
use Varsite\Platform\Capabilities\ResourceCapability;
use Varsite\Platform\Capabilities\WidgetCapability;
use Varsite\Platform\Capabilities\CapabilityRegistry;

final class AudioServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $registrar = $this->app->make(ModuleRouteRegistrar::class);
        $registrar->register('audio', require __DIR__.'/../routes/api.php');
        $registrar->register('audio', require __DIR__.'/../routes/admin.php');

        $this->registerResourceCapabilitys();

        Gate::policy(AudioTrack::class, AudioTrackPolicy::class);
        Gate::policy(AudioCategory::class, AudioCategoryPolicy::class);

        $this->app->make(ModuleManager::class)->register(new AudioModule);
    }

    /**
     * Zasoby panelu modułu (Warstwa deklaratywna). Moduł opisuje ekrany w PHP;
     * panel renderuje je generycznie — instalacja modułu nie wymaga zmian w panelu
     * ani budowania frontendu.
     */
    private function registerResourceCapabilitys(): void
    {
        $this->app->make(CapabilityRegistry::class)
            ->register(
                ResourceCapability::make('audio.tracks')
                    ->label('Nagranie', 'Nagrania audio')
                    ->icon('audio-lines')
                    ->endpoint('/v1/admin/audio/tracks')
                    ->permission('audio.view')
                    ->columns([
                        Column::text('title')->label('Tytuł')->sortable()->primary(),
                        Column::badge('category.name')->label('Kategoria'),
                        Column::duration('media.duration')->label('Czas'),
                        Column::status('status', [
                            'published' => ['tone' => 'ok', 'label' => 'Opublikowane'],
                            'draft' => ['tone' => 'warn', 'label' => 'Szkic'],
                            'hidden' => ['tone' => 'muted', 'label' => 'Ukryte'],
                        ])->label('Status'),
                        Column::date('published_at')->label('Publikacja')->sortable(),
                    ])
                    ->filters([
                        Filter::search(['title']),
                        Filter::select('category_id', '/v1/admin/audio/categories')->label('Kategoria'),
                        Filter::segmented('status', [
                            'all' => 'Wszystkie',
                            'published' => 'Opublikowane',
                            'draft' => 'Szkice',
                        ]),
                    ])
                    ->form([
                        Field::text('title')->label('Tytuł')->required(),
                        Field::select('category_id', '/v1/admin/audio/categories')->label('Kategoria'),
                        Field::select('status', [
                            'draft' => 'Szkic',
                            'published' => 'Opublikowane',
                            'hidden' => 'Ukryte',
                        ])->label('Status')->required(),
                        Field::reference('media_id', '/v1/admin/media', ['type' => 'audio'])->label('Plik audio')
                            ->hint('Wybierz plik z biblioteki mediów.'),
                        Field::textarea('description')->label('Opis'),
                    ])
                    ->actions([
                        Action::edit(),
                        Action::delete()->permission('audio.delete'),
                    ])
                    ->reorderable('/v1/admin/audio/tracks/reorder'),
            )
            ->register(
                ResourceCapability::make('audio.categories')
                    ->label('Kategoria', 'Kategorie audio')
                    ->icon('tags')
                    ->endpoint('/v1/admin/audio/categories')
                    ->permission('audio.category.manage')
                    ->columns([
                        Column::text('name')->label('Nazwa')->sortable()->primary(),
                        Column::text('slug')->label('Slug'),
                        Column::number('order')->label('Kolejność')->sortable(),
                    ])
                    ->filters([Filter::search(['name'])])
                    ->form([Field::text('name')->label('Nazwa')->required()])
                    ->actions([Action::edit(), Action::delete()]),
            )
            ->register(
                WidgetCapability::make('audio.published')
                    ->label('Opublikowane nagrania')
                    ->icon('audio-lines')
                    ->variant(WidgetCapability::VARIANT_STAT)
                    ->size(WidgetCapability::SIZE_QUARTER)
                    ->order(10)
                    ->endpoint('/v1/admin/audio/stats/published')
                    ->permission('audio.view')
                    ->opensAt('/audio/tracks'),
            )
            ->register(
                WidgetCapability::make('audio.recent')
                    ->label('Ostatnio dodane nagrania')
                    ->icon('audio-lines')
                    ->variant(WidgetCapability::VARIANT_LIST)
                    ->size(WidgetCapability::SIZE_HALF)
                    ->order(30)
                    ->endpoint('/v1/admin/audio/stats/recent')
                    ->permission('audio.view'),
            );
    }
}
