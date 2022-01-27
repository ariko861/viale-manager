<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInvite;

class RegisterController extends Controller
{
    //
    public function showRegistrationForm(Request $request)
    {
        $invitation_token = $request->get('invitation_token');
        $invitation = UserInvite::where('invitation_token', $invitation_token)->firstOrFail();
        $email = $invitation->email;

        return view('auth.register', compact('email'));
    }
}
