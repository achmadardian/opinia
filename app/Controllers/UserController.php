<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;

class UserController extends ResourceController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $user = new User();
        $data = $user->findAll();

        return $this->respond(data: [
            'message' => 'List all users',
            'data' => $data,
            'status' => 200
        ], status: 200);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function register()
    {
        // get request data
        $data = [
            'fullname' => $this->request->getVar('fullname'),
            'phone' => $this->request->getVar('phone'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT)
        ];

        // validate the request data
        if (!$this->validate([
            'fullname' => 'required',
            'phone' => 'required',
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique' => 'Email is already registered.'
                ]
            ],
            'password' => 'required|min_length[8]',
            'passconf' => 'required|matches[password]'
        ])) {
            // return with validation error
            return $this->respond([
                'messages' => 'Failed to register: Validation error.',
                'error' => $this->validator->getErrors(),
                'status' => 422
            ], 422);
        }

        try {
            // Insert data to the storage
            $user = new User();
            $user->save($data);

            // return with success response
            return $this->respond(data: [
                'message' => 'User has been successfully registered.',
                'data' => $data,
                'status' => 201
            ], status: 201);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'messages' => 'Error Not implemented ' . $e->getMessage() . '.',
                'status' => 501
            ], status: 501);
        }
    }
}
