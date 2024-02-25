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
            <div class="col-md-6">
                <form action="{{ route('app.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="product_description" class="form-control">{{ $product->product_description }}</textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="price">Harga</label>
                        <input type="text" name="product_price_capital" class="form-control" value="{{ $product->product_price_capital }}">
                    </div>

                    <div class="form-group mt-3">
                        <label for="price_customer">Harga Customer</label>
                        <input type="text" name="product_price_sell" class="form-control" value="{{ $product->product_price_sell }}">
                    </div>

                    <button type="submit" class="btn btn-success mt-3">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
