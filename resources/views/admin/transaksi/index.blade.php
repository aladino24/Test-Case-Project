@extends('layouts.admin.app')

@section('content')

<div class="card">
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
    <div class="card-body table-responsive">

      @if (check_authorized("003U"))
        <table class="table table-bordered" id="tableProducts">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th>Harga Modal</th>
              <th>Harga Customer</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      @endif

    </div>
  </div>

  <div class="modal fade" id="modalInputQuantity">
    <div class="modal-dialog">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Order Barang</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
  
          <form  action="{{ route('app.transaksi.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="customer_name">Nama Customer</label>
              <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
  
            <div class="form-group">
              <label>Quantity</label>
              <input type="hidden" name="product_name" id="product_name">
              <input type="hidden" name="product_price_sell" id="product_price_sell">
              <input type="hidden" name="product_price_capital" id="product_price_capital">
              <input type="hidden" class="form-control" name="product_description" id="product_description">
              <input type="hidden" name="product_id" id="product_id">
              <input type="number" class="form-control" name="quantity" >
            </div>

            <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" rows="3" name="description"></textarea>
            </div>
  
            <button type="submit" class="btn btn-success btn-sm mt-2">Lanjutkan Order</button>
          </form>
  
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
  
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
       function orderProduct(button) {
          var productId = button.getAttribute('data-product-id');
          var productName = button.getAttribute('data-product-name');
          var productPriceSell = button.getAttribute('data-product-price-sell');
          var productPriceCapital = button.getAttribute('data-product-price-capital');
          var productDescription = button.getAttribute('data-product-description');


          document.getElementById('product_id').value = productId;
          document.getElementById('product_name').value = productName;
          document.getElementById('product_price_sell').value = productPriceSell;
          document.getElementById('product_price_capital').value = productPriceCapital;
          document.getElementById('product_description').value = productDescription;



          $('#modalInputQuantity').modal('show');
      }
  </script>
@endpush

@if (check_authorized("005PS"))
  @push('script')
    <script>
      CORE.dataTableServer("tableProducts", "/app/transaksi/get-product");
    </script>
  @endpush
    
@endif
