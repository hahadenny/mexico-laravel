<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Auth;

class SignUpController extends Controller
{
    /**
     * Sign up the user.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @param  \App\Api\V1\Requests\SignUpRequest  $request
     * @param  \PHPOpenSourceSaver\JWTAuth\JWTAuth  $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request, JWTAuth $JWTAuth)
    {
        $user = new User(array_merge($request->all(), ['role' => UserRole::User]));
        if(!$user->save()) {
            throw new HttpException(500);
        }

        $token = $JWTAuth->fromUser($user);
        return $this->authResponse($token, Response::HTTP_CREATED);
    }
}
