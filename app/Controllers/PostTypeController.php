<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PostType;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;

class PostTypeController extends ResourceController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $postType = new PostType();
        $getPostType = $postType->findAll();

        return $this->respond(data: [
            'message'   => 'List all Posts Types',
            'data'      => $getPostType,
            'status'    => 200
        ], status: 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $postType = new PostType();
        $data = $postType->find($id);

        if (!$data) {
            return $this->respond(data: [
                'message' => 'Failed to get single post type: Param not defined.',
                'status' => 404
            ], status: 404);
        }

        return $this->respond(data: [
            'message' => 'List a single post type.',
            'data' => $data,
            'status' => 200
        ], status: 200);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = [
            'jenis' => $this->request->getVar('jenis'),
            'type' => $this->request->getVar('type')
        ];

        if (!$this->validate([
            'jenis' => 'required',
            'type'  => 'required'
        ])) {
            return $this->respond(data: [
                'message'   => 'Failed to create: Validation error.',
                'error'     => $this->validator->getErrors(),
                'status'    => 422
            ], status: 422);
        }

        try {
            $postType = new PostType();
            $postType->save($data);

            return $this->respond(data: [
                'message'   => 'A new Post Type has been successfully added.',
                'status'    => 201
            ], status: 201);
        } catch (\Throwable $e) {
            return $this->respond(data: [
                'message'   => 'Not implemented: ' . $e->getMessage() . '.',
                'status'    => 501
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
        $postType = new PostType();
        $postTypeExists = $postType->find($id);

        if (!$postTypeExists) {
            return $this->respond(data: [
                'message'   => 'Failed to update: Param not defined or Post Type not found.',
                'status'    => 404
            ], status: 404);
        }

        $data = [
            'jenis' => $this->request->getVar('jenis'),
            'type'  => $this->request->getVar('type')
        ];

        if (!$this->validate([
            'jenis' => 'required',
            'type'  => 'required'
        ])) {
            return $this->respond(data: [
                'message'   => 'Failed to update: Validation error',
                'error'     => $this->validator->getErrors(),
                'status'    => 422
            ], status: 422);
        }

        try {
            $postType->update($id, $data);

            return $this->respond(data: [
                'message'   => 'Post Type has been successfully updated',
                'status'    => 200
            ], status: 200);
        } catch (\Throwable $e) {
            return $this->respond(data: [
                'message'   => 'Failed to update: ' . $e->getMessage() . '.',
                'status'    => 501
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
        $postType = new PostType();
        $data = $postType->find($id);

        if (!$data) {
            return $this->respond(data: [
                'message' => 'Failed to get single post type: Param not defined.',
                'status' => 404
            ], status: 404);
        }

        try {
            $postType->delete($id);

            return $this->respond(data: [
                'message' => 'A Post Type has been successfully deleted.',
                'status' => 200
            ], status: 200);
        } catch (\Exception $e) {
            return $this->respond(data: [
                'messages' => 'Error Not implemented ' . $e->getMessage(),
                'status' => 501
            ], status: 501);
        }
    }
}
