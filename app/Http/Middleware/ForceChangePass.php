<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceChangePass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($request->path() !== 'admin/my-profile' && $request->path() !== 'admin/my-profile/settings-update' && $user && !$user->set_pass) {
            return redirect('admin/my-profile')->with('error', 'Hasło tymczasowe musi zostać zmienione! Prosimy  zmienić hasło na własne. Zapraszamy do korzystania z aplikacji.');
        }

        return $next($request);
    }
}
