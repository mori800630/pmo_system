<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // デバッグログ
        \Log::info('AdminMiddleware check', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user() ? auth()->user()->role : 'not authenticated',
            'is_admin' => auth()->user() ? auth()->user()->isAdmin() : false,
            'url' => $request->url()
        ]);

        if (!auth()->check() || !auth()->user()->isAdmin()) {
            \Log::warning('Admin access denied', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user() ? auth()->user()->role : 'not authenticated'
            ]);
            abort(403, '管理者権限が必要です。');
        }

        return $next($request);
    }
}
