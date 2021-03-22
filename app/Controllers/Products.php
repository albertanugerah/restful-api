<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Message;

class Products extends ResourceController
{
  use ResponseTrait;

  public function __construct()
  {
    $this->productModel = new ProductModel();
  }

  //get all product
  public function index()
  {
    $data = $this->productModel->getProducts();
    return $this->respond($data, 200);
  }
  //get single product
  public function show($id = null)
  {
    $data = $this->productModel->getProducts($id);
    if ($data) {
      return $this->respond($data);
    }
    return $this->failNotFound('No data found with id ' . $id);
  }

  //create a product
  public function create()
  {
    $model = $this->productModel;
    $data = [
      'product_name' => $this->request->getPost('product_name'),
      'product_price' => $this->request->getPost('product_price')
    ];

    $insert =  $model->insert($data);

    $success = [
      'status' => 201,
      'error' => null,
      'message' => [
        'success' => 'Data Saved'
      ]
    ];
    if ($insert) {
      return $this->respondCreated($success, 201);
    } else {
      $fail = [
        'status'   => 400,
        'error'    => $data,
        'messages' => 'data not saved',
      ];
      return $this->fail($fail);
    }
  }

  // update product
  public function update($id = null)
  {

    $json = $this->request->getJSON();
    if ($json) {
      $data = [
        'product_name' => $json->product_name,
        'product_price' => $json->product_price
      ];
    } else {
      $input = $this->request->getRawInput();
      $data = [
        'product_name' => $input['product_name'],
        'product_price' => $input['product_price']
      ];
    }
    // Insert to Database
    $this->productModel->update($id, $data);
    $response = [
      'status'   => 200,
      'error'    => null,
      'messages' => [
        'success' => 'Data Updated'
      ]
    ];
    return $this->respond($response);
  }

  //delete product
  public function delete($id = null)
  {
    $data = $this->productModel->find($id);
    if ($data) {
      $this->productModel->delete($id);
      $response = [
        'status'   => 200,
        'error'    => null,
        'messages' => [
          'success' => 'Data Deleted'
        ]
      ];
      return $this->respondDeleted($response);
    } else {
      return $this->failNotfound('No data found with id ' . $id);
    }
  }
}
