<?php

namespace App\Http\Controllers\Api\V1\Auth;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Interfaces\AuthenticationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class AuthenticationController extends Controller
{
    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }

    /**
     * Sign up
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        $data             = $request->safe()->only('name', 'email');
        $data['password'] = bcrypt($request->input('password'));

        $user = $this->authenticationRepository->storeUser($data);

        if (!$user) {
            return Response::json([
                'message' => 'Sign up is not successful. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Sign up is successful.'
        ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Login
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->only('email', 'password');

        $token = $this->authenticationRepository->getToken($data);

        if (!$token) {
            return Response::json([
                'message' => 'Invalid credentials. Please try again with valid one.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Sign in successful.',
            'token'   => $token
        ], HttpResponse::HTTP_OK);
    }
}
