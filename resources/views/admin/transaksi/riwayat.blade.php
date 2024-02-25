@extends('layouts.admin.app')

@section('content')
<div class="card-body table-responsive">

    @if (check_authorized("003U"))
      <table class="table table-bordered" id="tableRiwayatTransaksi">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Customer</th>
            <th>Product</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Total Harga</th>
            <th>Tanggal Transaksi</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    @endif

  </div>

@endsection

@if (check_authorized("005PS"))
  @push('script')
    <script>
      CORE.dataTableServer("tableRiwayatTransaksi", "/app/riwayat-transaksi/list");
    </script>
  @endpush
    
@endif

@push('script')
<script>
    // generate pdf
    function generatePdf(id) {
      window.open('/app/riwayat-transaksi/generate-pdf/' + id, '_blank');
    }
</script>
@endpush

