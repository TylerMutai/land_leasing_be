<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        //Manually verify
        if ($this->verifyEmail($request)) {
            return view('auth.email-verified');
        }
        return view('auth.email-not-verified');


    }

    private function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!(hash_equals((string)$request->route('id'),
                (string)$user->getKey()) && hash_equals((string)$request->route('hash'),
                sha1($user->getEmailForVerification())))) {
            return false;
        }
        $user->markEmailAsVerified();
        event(new Verified($user));
        return true;
    }

    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }

    public function index()
    {
        return view('auth.verify-email');
    }
}
