<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Exception\ValidationHttpException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {        
        $credentials = $request->only(['email', 'password']);
        
        $auth = Auth::guard();      
        try {
            $auth->factory()->setTTL(10080);
            $user = User::query()
                    ->where('email', $credentials['email'])
                    ->where('password', $credentials['password'])
                    ->first();
            $token = $user ? $auth->login($user) : false;
            if (!$token) {
                throw new ValidationHttpException(['password' => ['Incorrect email or password.']]);
            }
        } catch (JWTException $e) {
            // let the more useful exception through if we're in a testing environment
            throw config('app.env') !== 'local' ? new HttpException(500) : $e;
        }        
        
        return $this->authResponse($token);
    }
    
    public function auth(JWTAuth $JWTAuth)
    {
        try {
            $token = Auth::guard('api')->login(Auth::guard('api-key')->user());
            return $this->authResponse($token);
        } catch (JWTException $e) {
            abort(401);
        }
    }
    
    public function me() {
        $user = Auth::guard()->user();
        return new JsonResource($user);
    }
    
    public function logout()
    {
        Auth::guard()->logout();
        return response()
            ->json(['message' => 'Successfully logged out']);
    }
}
