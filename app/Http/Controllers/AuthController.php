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
