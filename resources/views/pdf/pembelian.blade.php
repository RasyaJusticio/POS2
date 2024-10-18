<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian - {{ $pembelian->uuid }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Struk Pembelian</h1>
    <h2>ID Pembelian: {{ $pembelian->uuid }}</h2>
    <h2>Tanggal: {{ $pembelian->created_at->format('d-m-Y H:i:s') }}</h2>

    <h3>Detail Pembelian</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Harga</th>
                {{-- <th>Jumlah</th> --}}
                {{-- <th>Total</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->item as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp {{ number_format($item['price'], 2, ',', '.') }}</td>
                    {{-- <td>{{ $quantity }}</td> --}}
                    {{-- <td>Rp {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pembayaran: Rp {{ number_format($pembelian->total_price) }}
    </div>

</body>
</html>
