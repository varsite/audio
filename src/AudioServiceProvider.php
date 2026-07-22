<?php

declare(strict_types=1);

namespace Varsite\Audio;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Varsite\Audio\Models\AudioCategory;
use Varsite\Audio\Models\AudioSample;
use Varsite\Audio\Policies\AudioCategoryPolicy;
use Varsite\Audio\Policies\AudioSamplePolicy;
use Varsite\Platform\Routing\ModuleRouteRegistrar;
use Varsite\Platform\Support\ModuleManager;
use Varsite\Platform\Support\NavRegistry;

final class AudioServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $registrar = $this->app->make(ModuleRouteRegistrar::class);
        $registrar->register('audio', require __DIR__.'/../routes/api.php');
        $registrar->register('audio', require __DIR__.'/../routes/admin.php');

        $this->app->make(NavRegistry::class)
            ->item('Treść', ['id' => 'audio.samples', 'label' => 'Próbki audio', 'icon' => 'audio-lines', 'href' => '/audio/samples', 'order' => 10], groupOrder: 20)
            ->item('Treść', ['id' => 'audio.categories', 'label' => 'Kategorie', 'icon' => 'tags', 'href' => '/audio/categories', 'order' => 20], groupOrder: 20);

        Gate::policy(AudioSample::class, AudioSamplePolicy::class);
        Gate::policy(AudioCategory::class, AudioCategoryPolicy::class);

        $this->app->make(ModuleManager::class)->register(new AudioModule);
    }
}
