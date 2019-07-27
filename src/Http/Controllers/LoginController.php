<?php

namespace Bitfumes\ApiAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Bitfumes\ApiAuth\Http\Requests\LoginRequest;

class LoginController extends AuthController
{
    public function __construct()
    {
        $this->user= app()['config']['api-auth.models.user'];
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->performLogin($request);
    }

    /**
     * @param $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    protected function performLogin($request)
    {
        $token = auth()->attempt(['email'=>$request->email, 'password'=> $request->password]);

        if (! $token) {
            return $this->noTokenResponse();
        }

        if (! $this->checkEmailVerify()) {
            return $this->emailNotVerifiedResponse();
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    protected function noTokenResponse(): JsonResponse
    {
        return response()->json([
            'errors' => [
                'error' => 'Credentials does\'t match our record',
            ],
        ], 401);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    protected function emailNotVerifiedResponse()
    {
        return response(['errors' => ['verify' => 'Please verify your email first.']], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @return mixed
     */
    protected function checkEmailVerify()
    {
        $user = $this->user::whereEmail(request('email'))->first();
        return $user->email_verified_at;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
