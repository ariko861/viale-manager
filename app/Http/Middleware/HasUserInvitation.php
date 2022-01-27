<?php

namespace App\Http\Middleware;

use App\Models\UserInvite;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HasUserInvitation
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
            if (!$request->has('invitation_token')) {
                return redirect(route('welcome'));
            }

            $invitation_token = $request->get('invitation_token');

            /**
             * Lets try to find invitation by its token.
             * If failed -> return to request page with error.
             */
            try {
                $invitation = UserInvite::where('invitation_token', $invitation_token)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect(route('welcome'))
                    ->with('error', 'Wrong invitation token! Please check your URL.');
            }

            /**
             * Let's check if users already registered.
             * If yes -> redirect to login with error.
             */
            if (!is_null($invitation->registered_at)) {
                return redirect(route('login'))->with('error', 'The invitation link has already been used.');
            }
        }

        return $next($request);
    }
}
