<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    /**
     * AuthController constructor.
     *
     * @param AuthRepositoryInterface $authRepository The authentication repository interface to be injected.
     */
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * 
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login de um usuário",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", format="password", example="admin.1234")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5...")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     *
     * Handles user login.
     *
     * @param Request $request 
     * @return mixed Returns the result from the authentication repository's login method.
     */
    public function login(Request $request): mixed
    {
        return $this->authRepository->login($request);
    }

    /**
     * Handles user logout.
     *
     * @param Request $request
     * @return mixed 
     */
    public function logout(Request $request): mixed
    {
        return $this->authRepository->logout($request);
    }
}
