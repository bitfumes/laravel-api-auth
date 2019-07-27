<?php

namespace Bitfumes\ApiAuth\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('updatePassword');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response(trans($response), Response::HTTP_ACCEPTED);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response(['errors'=>['email' => trans($response)]], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validateRequest($request);

        if ($this->checkOldPassword()) {
            auth()->user()->update(['password'=>request('password_confirmation')]);
            return response('success', Response::HTTP_ACCEPTED);
        }
        return response(['errors'=>['oldPassword'=>['Old Password is Incorrect']]], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param Request $request
     * @return array|void
     */
    protected function validateRequest(Request $request): void
    {
        $request->validate([
            'oldPassword' => 'required',
            'password'    => 'required|confirmed|min:6',
        ]);
    }

    /**
     * @return bool
     */
    protected function checkOldPassword(): bool
    {
        return Hash::check(request('oldPassword'), auth()->user()->password);
    }
}
