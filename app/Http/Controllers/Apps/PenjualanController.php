<?php

namespace App\Http\Controllers\Apps;
use Illuminate\Http\Request;
use App\Services\PenjualanService;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sales;


class PenjualanController extends Controller
{
    private PenjualanService $penjualan_service;
    private ProductService $product_service;

    public function __construct()
    {
      $this->penjualan_service = new PenjualanService;
      $this->product_service = new ProductService;
    }
    public function index(){
        return $this->view_admin("admin.transaksi.index", "Penjualan", [], TRUE);
    }

    public function detail(){
        return $this->view_admin("admin.transaksi.riwayat", "Daftar Penjualan", [], TRUE);
    }

    public function list(Request $request){
        $penjualan = $this->penjualan_service->get_list_paged($request);
        $count = $this->penjualan_service->get_list_count($request);

        $data = [];
        $no = $request->start;

        foreach ($penjualan as $penjualan) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $penjualan->customer_name;
            $row[] = $penjualan->product_name;
            $row[] = 'Rp ' . number_format($penjualan->selling_price, 0, ',', '.');
            $row[] = $penjualan->quantity;
            $row[] = 'Rp ' . number_format($penjualan->total_price, 0, ',', '.');
            $row[] = Carbon::parse($penjualan->sold_at)->format('d M Y H:i:s');
            $row[] = $penjualan->description;
            $button = "<button class='btn btn-warning btn-sm m-1' onclick='generatePdf(" . $penjualan->id . ")'>
              <i class='bi bi-file-pdf'></i> PDF
          </button>";

            $row[] = $button;
            $data[] = $row;
            // dd($penjualan);
        }

        $json = [
            "draw" => $request->draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        ];

        return \response()->json($json, 200);
    }

  public function generatePDF($id) {
      $penjualan = Sales::with('product')->find($id);
  
      $pdf = PDF::loadView('pdf.invoice', compact('penjualan'));
      return $pdf->download('penjualan_'.$id.'.pdf');
  }

    public function getProduct(Request $request){
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
          $button = '<button class="btn btn-info btn-sm mb-3 order-btn"
                        data-product-id="' . $product->id . '"
                        data-product-name="' . $product->product_name . '"
                        data-product-price-sell="' . $product->product_price_sell . '"
                        data-product-price-capital="' . $product->product_price_capital . '"
                        data-product-description="' . $product->product_description . '"
                        onclick="orderProduct(this)">
                    Order
                </button>';
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

      public function order(Request $request){
            try {
                $data = [
                    'product_id' => $request->product_id,
                    'customer_name' => $request->customer_name,
                    'quantity' => $request->quantity,
                    'selling_price' => $request->product_price_sell,
                    'total_price' => $request->product_price_sell * $request->quantity,
                    'description' => $request->description,
                ];
              $response = $this->penjualan_service->order($data);
              return redirect()->route("app.transaksi.index")->with("success", "Order Product Berhasil");

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

  
}
