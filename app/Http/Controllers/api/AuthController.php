<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Respositories\AuthRepository;
use App\Http\Requests\AuthRequest;

class AuthController extends Controller
{
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function test(){
        return "Nhan";
    }
    public function signup(AuthRequest $request)
    {
        return $this->authRepository->signup($request);
    }

    public function signin(AuthRequest $request)
    {
        return $this->authRepository->signin($request);
    }

    public function signout()
    {
        return $this->authRepository->signout();
    }
}
