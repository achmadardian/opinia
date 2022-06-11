<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use CodeIgniter\HTTP\IncomingRequest;

class AuthController extends ResourceController
{
    use ResponseTrait;

    /**
     * Attempt to login
     *
     * @return mixed
     */
    public function login()
    {
        // get request data
        $data = [
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ];

        // validate data
        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ])) {
            // return with validation error
            return $this->respond([
                'message' => 'Failed to Login: Validation Error.',
                'error' => $this->validator->getErrors(),
                'status' => 422
            ]);
        }

        // get user data 
        $user = new User();
        $userExists = $user->where('email', $data['email'])->first();

        // check password
        $verifyPassword = password_verify($data['password'], $userExists['password']);

        if (!$userExists || !$verifyPassword) {
            return $this->respond(data: [
                'message' => 'Failed to login: Email or Password is incorrect.',
                'status' => 401
            ], status: 401);
        }

        try {
            $payload = [
                'fullname' => $userExists['fullname'],
                'email' => $userExists['email']
            ];

            $token = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS256');

            return $this->respond(data: [
                'message' => 'User has been successfully login.',
                'token' => $token,
                'status' => 200
            ], status: 200);
        } catch (\Throwable $th) {
            return $this->respond(data: [
                'message'   => $th->getMessage()
            ], status: 501);
        }
    }

    /**
     * Attempt to logout
     *
     * @return mixed
     */
    public function logout()
    {
        //
    }

    // public function readUser()
    // {
    //     $request = service('request');
    //     $key = getenv('JWT_SECRET_KEY');
    //     $headers = $request->getHeader('Authorization');
    //     $jwt = $headers->getValue();

    //     $userData = JWT::decode($jwt, new Key($key, 'HS256'));
    //     $users = $userData->fullname;

    //     return $this->respond(data: [
    //         'status' => 1,
    //         'users' => $users
    //     ], status: 200);
    // }

    public function currentUser()
    {
        //$header = $this->request->getServer('HTTP_AUTHORIZATION');

        $request = service('request');
        $header = $request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return $this->respond(data: [
                'message' => 'Token Required',
                'status' => 401
            ], status: 401);
        }

        $token = explode(' ', $header)[1];

        try {
            $currentUser = JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS256'));

            return $this->respond(data: [
                'fullname' => $currentUser->fullname,
                'email' => $currentUser->email
            ], status: 200);
        } catch (SignatureInvalidException) {
            return $this->respond(data: [
                'message' => 'Invalid Token',
                'status' => 401
            ], status: 401);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'message' => $e->getMessage(),
                'status' => 401
            ], status: 401);
        }
    }
}
