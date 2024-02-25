<?php


namespace App\Services;
use App\Models\SessionToken;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductService extends BaseService{

    /**
   * Generate query index page
   *
   * @param Request $request
   */
    public function generate_query_get(Request $request){
        $column_search = ["products.product_name", "products.product_description", "products.product_price_capital","products.product_price_sell"];
        $column_order = [NULL, "products.product_name", "products.product_description", "products.product_price_sell"];
        $order = ["products.id" => "DESC"];

        $results = Product::query()
            ->where(function ($query) use ($request, $column_search) {
                $i = 1;
                if (isset($request->search)) {
                    foreach ($column_search as $column) {
                        if ($request->search["value"]) {
                            if ($i == 1) {
                                $query->where($column, "LIKE", "%{$request->search["value"]}%");
                            } else {
                                $query->orWhere($column, "LIKE", "%{$request->search["value"]}%");
                            }
                        }
                        $i++;
                    }
                }
            });

        if (isset($request->order) && !empty($request->order)) {
            $results = $results->orderBy($column_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
        } else {
            $results = $results->orderBy(key($order), $order[key($order)]);
        }

        return $results;
    }

    public function get_list_paged($request){
        $results = $this->generate_query_get($request);
        if ($request->length != -1) {
        $results = $results->offset($request->start)->limit($request->length);
        }
        return $results->get();
    }

    public function get_list($request){
        $results = $this->generate_query_get($request);
        return $results->get();
    }

    public function get_detail($id){
        return $this->get_by_id($id);
    }

    public function get_by_id($id){
        return Product::find($id);
    }

    public function get_list_count (Request $request){
        $results = $this->generate_query_get($request);
        return $results->count();
    }

    public function get($id){
        return $this->get_by_id($id);
    }

    public function update(Request $request, $id){

        try {
            $product = $this->get_by_id($id)
                ->update([
                    'product_name' => $request->product_name,
                    'product_description' => $request->product_description,
                    'product_price_capital' => $request->product_price_capital,
                    'product_price_sell' => $request->product_price_sell,
                ]);

            
            return $product;
        } catch (\Exception $e) {
            ErrorService::error($e, "Gagal store user!");
            $response = \response_errors_default();
        }

        return $response;
    }


    public function store(Request $request){
        try {
            $product = Product::create([
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'product_price_capital' => $request->product_price_capital,
                'product_price_sell' => $request->product_price_sell,
            ]);

            $response = \response_success_default("Berhasil menambahkan product!", $product->id, route("app.products.index", $product->id));
        } catch (\Exception $e) {
            ErrorService::error($e, "Gagal store user!");
            $response = \response_errors_default();
        }

        return $response;
    }

    public function destroy($id){
        try {
            $product = $this->get_by_id($id);
            $product->delete();
            $response = \response_success_default("Berhasil menghapus product!",FALSE, \route("app.products.index"));
        } catch (\Exception $e) {
            ErrorService::error($e, "Gagal menghapus product!");
            $response = \response_errors_default();
        }

        return $response;
    }


}