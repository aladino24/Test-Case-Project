@extends('layouts.admin.app')

@section('content')

<div class="card">
    <div class="card-body table-responsive">

      @if (check_authorized("003U"))
       <a href="{{ route('app.products.create') }}" class="btn btn-success btn-sm mb-3">Tambah</a>
      @endif
      @if (check_authorized("003U"))
        <table class="table table-bordered" id="tableProducts">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th>Harga Customer</th>
              <th>Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      @endif

    </div>
  </div>

@endsection

@if (check_authorized("005PS"))
  @push('script')
    <script>
      CORE.dataTableServer("tableProducts", "/app/product/get");
    </script>
  @endpush
    
@endif
