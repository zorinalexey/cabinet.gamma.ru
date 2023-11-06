<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class NoLogin
{
    /**
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        return redirect()->to(route('cabinet'));
    }
}
