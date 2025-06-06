<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Arr;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            // 'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'permissions' => [
                    'post' => [
                        'create' => $request->user()?->can('create', Post::class),
                        // 'update' => $request->user()?->can('update', Post::class),
                        // 'delete' => $request->user()?->can('delete', Post::class),
                    ],
                ],
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'notification' => collect(Arr::only($request->session()->all(), ['success', 'error', 'warning', 'info', 'default']))
                ->mapWithKeys(fn ($notification, $key) => ['type' => $key, 'body' => $notification]),
        ];
    }
}
