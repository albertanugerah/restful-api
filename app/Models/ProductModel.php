<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
  protected $table = 'product';
  protected $primaryKey = 'product_id';
  protected $allowedFields = ['product_name', 'product_price'];

  public function getProducts($id = false)
  {
    if ($id === false) {
      return $this->findAll();
    }
    return $this->where(['product_id' => $id])->first();
  }

  //cek koneksi database
  // $db = \Config\Database::connect();
  // $product = $db->query("SELECT * FROM product");
  // dd($product);
}
