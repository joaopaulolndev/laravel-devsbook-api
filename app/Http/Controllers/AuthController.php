<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'auth:api',
            ['except' => ['login', 'create', 'unauthorized']]
        );
    }

    public function unauthorized()
    {
        return $this->jsonResponse(['error' => 'Não autorizado'], 401);
    }

    public function login(Request $request)
    {
        $array = ['error' => ''];

        $validator = Validator(
            $request->only(['email', 'password']),
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:6']
            ]
        );

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
            return $this->jsonResponse($array, 400);
        }

        $data = $request->only(['email', 'password']);
        $email = $data['email'];
        $password = $data['password'];

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            $array['error'] = 'E-mail e/ ou senha errados.';
            return $this->jsonResponse($array, 400);
        }

        $array['token'] = $token;
        return $this->jsonResponse($array);
    }

    public function logout()
    {
        auth()->logout();
        return $this->jsonResponse(['error' => '']);
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return $this->jsonResponse(['error' => '', 'token' => $token]);
    }

    public function create(Request $request)
    {
        $array = ['error' => ''];
        $data = $request->only(['name', 'email', 'password', 'birthdate']);

        $validator = Validator(
            $data,
            [
                'name' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
                'birthdate' => ['required', 'date']
            ]
        );

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
            return $this->jsonResponse($array, 400);
        }

        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $birthdate = $data['birthdate'];

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->save();

        $token = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if (!$token) {
            $array['error'] = 'Ocorreu um erro !';
            return $this->jsonResponse($array, 500);
        }

        $array['token'] = $token;
        return $this->jsonResponse($array);
    }
}
