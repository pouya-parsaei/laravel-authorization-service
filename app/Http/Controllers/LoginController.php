<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\Auth\Traits\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use  ThrottlesLogins;



    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validateForm($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if (!$this->isValidCredentials($request)) {
            $this->incrementLoginAttempts($request);
            return $this->sendLoginFailedResponse();
        }

        $user = $this->getUser($request);


        Auth::login($user, $request->remember);
        return $this->sendSuccessResponse();
    }

    private function validateForm(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required'],
            ],
        );
    }

    private function isValidCredentials($request)
    {
        return Auth::validate($request->only(['email', 'password']));
    }

    private function sendLoginFailedResponse()
    {
        return back()->with('failed', __('auth.failed'));
    }

    private function getUser(Request $request)
    {
        return User::where('email', $request->email)->firstOrFail();
    }

    protected function sendHasTwoFactorResponse()
    {
        return redirect()->route('auth.login.code.form');
    }

    private function sendSuccessResponse()
    {
        session()->regenerate();
        return redirect()->intended(route('home'));
    }


    public function logout()
    {
        session()->invalidate();

        Auth::logout();

        return redirect()->route('home');
    }

}
