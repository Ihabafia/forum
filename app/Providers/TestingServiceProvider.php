<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

class TestingServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource) {
            $this->has($key);
            expect($this->prop($key))->toEqual($resource->response()->getData(true));

            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $collection) {
            $this->hasResource("{$key}.data", $collection);
            expect($this->prop($key))->toHaveKeys(['data', 'links', 'meta']);

            return $this;
        });

        TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource) {
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->hasResource($key, $resource));
        });

        TestResponse::macro('assertHasPaginatedResource', function (string $key, ResourceCollection $collection) {
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->hasPaginatedResource($key, $collection));
        });

        TestResponse::macro('assertInertiaComponent', function (string $component) {
            $this->assertInertia(fn (AssertableInertia $page) => $page->component($component, true));
        });
    }
}
