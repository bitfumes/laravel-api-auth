<?php

namespace Bitfumes\ApiAuth\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Bitfumes\ApiAuth\Helpers\ImageCrop;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Bitfumes\ApiAuth\Http\Requests\UpdateRequest;
use Bitfumes\ApiAuth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('getUser');
        $this->resource = app()['config']['api-auth.resources.user'];
        $this->user     = app()['config']['api-auth.models.user'];
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->user::create($request->all());
        $user->sendEmailVerificationNotification();
        return response(['message' => 'Now verify your email ID to activate your account'], Response::HTTP_CREATED);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request)
    {
        $user = auth()->user();
        $this->checkForAvatar($request);
        $user->update($request->all());
        return response([
            'data'=> new $this->resource($user),
        ], Response::HTTP_ACCEPTED);
    }

    protected function checkForAvatar($request)
    {
        if ($request->avatar) {
            $height   = config('api-auth.avatar.thumb_height');
            $width    = config('api-auth.avatar.thumb_width');
            $path     = config('api-auth.avatar.path');
            $disk     = config('api-auth.avatar.disk');
            $filename = Str::random();
            $image    = base64_decode($request->avatar);

            $this->removeOldAvatar();

            Storage::disk($disk)->put("{$path}/{$filename}.jpg", $image);

            $im    = imagecreatefromstring($image);
            $thumb = ImageCrop::crop($im, $width, $height);
            Storage::disk($disk)->put("{$path}/{$filename}_thumb.jpg", $thumb);
            ImageCrop::clearnUp($im);

            unset($request['avatar']);
            $request['avatar'] = "{$path}/{$filename}";
        }
    }

    protected function removeOldAvatar()
    {
        $user     = auth()->user();
        $disk     = config('api-auth.avatar.disk');
        Storage::disk($disk)->delete("{$user->avatar}.jpg");
        Storage::disk($disk)->delete("{$user->avatar}_thumb.jpg");
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function getUser()
    {
        $user = auth()->user();
        return response([
            'data'=> new $this->resource($user),
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user(),
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
