<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .total-box { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Total</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->table->name ?? 'Takeaway' }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Grand Total Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
    </div>
</body>
</html>