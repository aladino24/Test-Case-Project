<?php

namespace App\Services;
use App\Models\SessionToken;
use App\Services\Cores\BaseService;
use Illuminate\Http\Request;
use App\Models\Sales;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenjualanService extends BaseService{
    public function get_list_paged($request){
        $results = $this->generate_query_get($request);
        if ($request->length != -1) {
            $results = $results->offset($request->start)->limit($request->length);
        }
        return $results->get();
    }

    protected function generate_query_get ( $request ) {
        $column_search = ["products.product_name","sales.customer_name", "sales.quantity", "sales.selling_price", "sales.total_price", "sales.description", "sales.sold_at"];
        $column_order = [NULL, "sales.customer_name","products.product_name","sales.selling_price",  "sales.quantity", "sales.total_price", "sales.description", "sales.sold_at"];
        $order = ["sales.id" => "DESC"];

        $results = Sales::query()
        ->leftJoin('products', 'sales.product_id', '=', 'products.id') // Gabungkan tabel produk
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

    public function get_list_count ( $request ) {
        $results = $this->generate_query_get($request);
        return $results->count();
    }

    public function order($data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required',
            'quantity' => 'required',
            'selling_price' => 'required',
            'total_price' => 'required',
            'description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        try {
    
            $penjualan = Sales::create([
                'product_id' => $data['product_id'],
                'customer_name' => $data['customer_name'],
                'quantity' => $data['quantity'],
                'selling_price' => $data['selling_price'],
                'total_price' => $data['total_price'],
                'description' => $data['description'],
                'sold_at' => date('Y-m-d H:i:s')
            ]);

            $response = \response_success_default("Berhasil order product!", $penjualan->id, route("app.transaksi.index"));
        } catch (\Exception $e) {
            ErrorService::error($e, "Gagal store user!");
            $response = \response_errors_default();
        }

        return $response;
    }
}


