@extends('layouts.admin.app')

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 text-right">
                <a href="{{ route('app.products.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <form action="{{ route('app.products.store') }}" method="POST">
                @csrf
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" class="form-control" name="product_name">
                    </div>
                    <div class="form-groupmt-3">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="product_description"></textarea>
                    </div>
    
                    <div class="form-group row mt-4">
                        <div class="col-md-6">
                            <label for="price">Harga</label>
                            <input type="text" class="form-control" name="product_price_capital">
                        </div>
                        <div class="col-md-6">
                            <label for="price_customer">Harga Customer</label>
                            <input type="text" class="form-control" name="product_price_sell" >
                        </div>
                    </div>
    
                    <div class="form-group row d-flex align-items-center justify-content-end">
                       <button
                         type="submit"
                            class="btn btn-success mt-3"
                        >Simpan</button>
                    </div>
    
                </div>

            </form>

        </div>

    </div>

</div>
@endsection