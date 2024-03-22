<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Data Transaksi</title>
    <style>
        /* CSS untuk styling PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Data Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Projek</th>
                <th>Nama Desa</th>
                <th>Harga</th>
                <th>PPN</th>
                <th>PPH</th>
                <th>Total Harga</th>
                <th>Status Pembayaran</th>
                <th>Status Kontrak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $transaction)
                <tr>
                    <td>{{ $transaction->id_transaksi }}</td>
                    <td>{{ $transaction->nama_projek }}</td>
                    <td>{{ $transaction->nama_desa }}</td>
                    <td>{{ $transaction->harga }}</td>
                    <td>{{ $transaction->ppn }}</td>
                    <td>{{ $transaction->pph }}</td>
                    <td>{{ $transaction->harga_total }}</td>
                    <td>{{ $transaction->status_pembayaran }}</td>
                    <td>{{ $transaction->status_kontrak }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
