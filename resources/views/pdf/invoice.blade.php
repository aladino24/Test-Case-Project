<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        /* Define CSS styles for the PDF report */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Laporan Penjualan</h1>

<table>
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
        </tr>
    </thead>
    <tbody>

        <tr>
            <td>1</td>
            <td>{{ $penjualan->customer_name }}</td>
            <td>{{ $penjualan->product->product_name }}</td>
            <td>{{ 'Rp ' . number_format($penjualan->product->product_price_sell , 0, ',', '.')}}</td>
            <td>{{ $penjualan->quantity }}</td>
            <td>{{ 'Rp ' . number_format($penjualan->total_price , 0, ',', '.')}}</td>
            <td>{{ $penjualan->created_at }}</td>
            <td>{{ $penjualan->description }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
