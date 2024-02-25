<?php

namespace App\Http\Controllers\Apps;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

   private ProductService $product_service;
   public function __construct()
   {
     $this->product_service = new ProductService;
   }

    public function index()
    {
      return $this->view_admin("admin.products.index", "Daftar Product", [], TRUE);
    }

    public function get(Request $request){
      $products = $this->product_service->get_list_paged($request);
      $count = $this->product_service->get_list_count($request);

      $data = [];
      $no = $request->start;

      foreach ($products as $product) {
        $no++;
        $row = [];
        $row[] = $no;
        $row[] = $product->product_name;
        $row[] = $product->product_description;
        $row[] = 'Rp ' . number_format($product->product_price_capital, 0, ',', '.');
        $row[] = 'Rp ' . number_format($product->product_price_sell, 0, ',', '.');
        $button = "<a href='" . \route("app.product.show", $product->id) . "' class='btn btn-info btn-sm m-1'>Detail</a>";
        if (\auth()->user()->id === 1) {
          $button .= form_delete("formProduct$product->id", route("app.product.destroy", $product->id));
          // edit
          $button .= "<a href='" . \route("app.product.edit", $product->id) . "' class='btn btn-warning btn-sm m-1'>Edit</a>";
        }
        $row[] = $button;
        $data[] = $row;
      }

      $output = [
        "draw" => $request->draw,
        "recordsTotal" => $count,
        "recordsFiltered" => $count,
        "data" => $data
      ];

      return \response()->json($output, 200);
    }

    public function show($id){
      $product = $this->product_service->get($id);
      return $this->view_admin("admin.products.show", "Detail Product", compact("product"), TRUE);
    }

    public function update(Request $request, $id){

      $this->validate($request, [
        "product_name" => "required",
        "product_description" => "required",
        "product_price_capital" => "required",
        "product_price_sell" => "required",
      ]);

      $this->product_service->update($request, $id);
      return \redirect()->route("app.products.index")->with("success", "Product berhasil diupdate");
    }

    public function edit($id){
      $product = $this->product_service->get($id);
      return $this->view_admin("admin.products.form", "Edit Product", compact("product"), TRUE);
    }

    public function create(){
      return $this->view_admin("admin.products.create", "Tambah Product", [], TRUE);
    }

    public function store(Request $request){
      $this->validate($request, [
        "product_name" => "required",
        "product_description" => "required",
        "product_price_capital" => "required",
        "product_price_sell" => "required",
      ]);

      $this->product_service->store($request);
      return \redirect()->route("app.products.index")->with("success", "Product berhasil ditambahkan");
    }

    public function destroy($id){
      $response = $this->product_service->destroy($id);
      return \response_json($response, 200);
    }

}
