<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ( \Session::has('locale')) {
            \App::setLocale(\Session::get('locale'));
            // You also can set the Carbon locale
            Carbon::setLocale(\Session::get('locale'));
        } else {
            if (in_array($request->getPreferredLanguage(), ['fr', 'fr_FR', 'fr-fr', 'fr_BE', 'fr-be', 'FR', 'fr_CA', 'fr_LU', 'fr_CH'] ) ) {
                \App::setLocale($request->getPreferredLanguage());
                Carbon::setLocale($request->getPreferredLanguage());
            } else {
                \App::setLocale('en');
                Carbon::setLocale('en');
            }
        }

        return $next($request);
    }
}
