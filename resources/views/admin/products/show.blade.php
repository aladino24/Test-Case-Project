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
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $product->product_name }}" readonly>
                </div>
                <div class="form-group mt-3">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" readonly>{{ $product->product_description }}</textarea>
                </div>
                <div class="form-group mt-3">
                    <label for="price">Harga</label>
                    <input type="text" class="form-control" value="{{ $product->product_price_capital }}" readonly>
                </div>

                <div class="form-group mt-3">
                    <label for="price_customer">Harga Customer</label>
                    <input type="text" class="form-control" value="{{ $product->product_price_sell }}" readonly>
                </div>
            </div>

            {{-- Edit data --}}

        </div>
        
    </div>
</div>

@endsection