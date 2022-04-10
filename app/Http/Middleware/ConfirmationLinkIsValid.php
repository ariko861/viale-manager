<?php

namespace App\Http\Middleware;

use App\Models\ReservationLink;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConfirmationLinkIsValid
{

    public function handle($request, Closure $next)
    {
        /**
         * Only for GET requests. Otherwise, this middleware will block our registration.
         */
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
                $link = ReservationLink::where('link_token', $link_token)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect(route('welcome'))
                    ->with('error', __('Ce lien est invalide ou a déjà été utilisé, veuillez nous contacter'));
            }

            /**
             * Let's check if users already registered.
             * If yes -> redirect to login with error.
             */
            if (!is_null($link->confirmed_at)) {
                return redirect(route('welcome'))->with('error', __('Ce lien a déjà été utilisé, veuillez nous contacter.') );
            }
        }

        return $next($request);
    }
}
