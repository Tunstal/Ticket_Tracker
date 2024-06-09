<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

     // The share method is used to share data with the frontend that is accessible globally.
    public function share(Request $request): array
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            return array_merge(parent::share($request), [
                'admin' => [
                    'user' => [
                        'idUser' => Auth::user()->idUser,
                        'username' => Auth::user()->username,
                        'role' => Auth::user()->role,
                    ]
                    ],
                'auth' => [
                    'user' => [
                        'idUser' => Auth::user()->idUser,
                        'username' => Auth::user()->username,
                        'role' => Auth::user()->role,
                    ]
            ]]);
        } else {
            return array_merge(parent::share($request), [
                'auth' => Auth::user() ? [
                    'user' => [
                        'idUser' => Auth::user()->idUser,
                        'username' => Auth::user()->username,
                        'role' => Auth::user()->role,
                    ]
                ] : null,
            ]);
        }
    }
}
