<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Post;

class PostController extends ResourceController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('posts');
            $builder->select('*');
            $builder->join('posts_types', 'posts_types.post_type_id = posts.post_type_id');
            $builder->join('users', 'users.user_id = posts.user_id');
            $query = $builder->get();

            return $this->respond(data: [
                'message' => 'List all posts.',
                'data' => $query->getResult(),
                'status' => 200
            ], status: 200);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'message' => 'Error not implemented ' . $e->getMessage() . '.',
                'status' => 501
            ], status: 501);
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $post = new Post();
        $data = $post->find($id);

        if (!$data) {
            return $this->respond(data: [
                'message' => 'Failed to get single post: Param not defined.',
                'status' => 404
            ], status: 404);
        }

        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('posts');
            $builder->select('*');
            $builder->join('posts_types', 'posts_types.post_type_id = posts.post_type_id');
            $builder->join('users', 'users.user_id = posts.user_id');
            $query = $builder->get();

            return $this->respond(data: [
                'message' => 'List single post.',
                'data' => $query->getResult(),
                'status' => 200
            ], status: 200);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'message' => 'Error not implemented ' . $e->getMessage() . '.',
                'status' => 501
            ], status: 501);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = [
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'post_type_id' => $this->request->getVar('post_type_id'),
            'user_id' => $this->request->getVar('user_id'),
        ];

        if (!$this->validate([
            'title' => 'required',
            'description' => 'required',
            'post_type_id' => 'required',
            'user_id' => 'required'
        ])) {
            return $this->respond(data: [
                'message' => 'Failed to create Post: Validation error.',
                'error' => $this->validator->getErrors(),
                'status' => 422
            ]);
        }

        try {
            $post = new Post();
            $post->save($data);

            return $this->respond(data: [
                'message' => 'A new Post has been successfully added.',
                'status' => 201
            ], status: 201);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'messages' => 'Error Not implemented ' . $e->getMessage(),
                'status' => 501
            ], status: 501);
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $post = new Post();
        $data = $post->find($id);

        if (!$data) {
            return $this->respond(data: [
                'message' => 'Failed to get single post: Param not defined.',
                'status' => 404
            ], status: 404);
        }

        $data = [
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'post_type_id' => $this->request->getVar('post_type_id'),
            'user_id' => $this->request->getVar('user_id'),
        ];

        if (!$this->validate([
            'title' => 'required',
            'description' => 'required',
            'post_type_id' => 'required',
            'user_id' => 'required'
        ])) {
            return $this->respond(data: [
                'message' => 'Failed to create Post: Validation error.',
                'error' => $this->validator->getErrors(),
                'status' => 422
            ]);
        }

        try {
            $post->update($id, $data);

            return $this->respond(data: [
                'message' => 'A new Post has been successfully updated.',
                'status' => 201
            ], status: 201);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'messages' => 'Error Not implemented ' . $e->getMessage() . '.',
                'status' => 501
            ], status: 501);
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $post = new Post();
        $data = $post->find($id);

        if (!$data) {
            return $this->respond(data: [
                'message' => 'Failed to get single Post: Param not defined.',
                'status' => 404
            ], status: 404);
        }

        try {
            $post->delete($id);

            return $this->respond(data: [
                'message' => 'A Post Type has been successfully deleted.',
                'status' => 200
            ], status: 200);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'messages' => 'Error Not implemented ' . $e->getMessage() . '.',
                'status' => 501
            ], status: 501);
        }
    }
}
