<?php

namespace App\Http\Middleware;

use App\Models\ReservationLink;
use App\Models\TransportLink;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TransportLinkIsValid
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
        if ($request->isMethod('get')) {

            /**
             * No token = Goodbye.
             */
            if (!$request->has('link_token')) {
                return redirect(route('welcome'));
            }

            $link_token = $request->get('link_token');

            /**
             * Lets try to find invitation by its token.
             * If failed -> return to request page with error.
             */
            try {
                $link = TransportLink::where('link_token', $link_token)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect(route('welcome'))
                    ->with('error', __('Ce lien est invalide, veuillez nous contacter'));
            }

        }

        return $next($request);
    }
}
