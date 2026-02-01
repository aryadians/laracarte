<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Order::query()->with(['items.product', 'table'])->where('status', 'paid');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function map($order): array
    {
        $items = $order->items->map(function ($item) {
            return $item->product->name . ' (' . $item->quantity . 'x)';
        })->implode(', ');

        return [
            $order->created_at->format('d/m/Y H:i'),
            '#' . $order->id,
            $order->customer_name,
            $order->table->name ?? 'Takeaway',
            $items,
            $order->subtotal,
            $order->tax_amount,
            $order->service_charge,
            $order->total_price,
            $order->payment_method === 'qris' ? 'QRIS' : 'Tunai',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Order ID',
            'Pelanggan',
            'Meja',
            'Menu Dipesan',
            'Subtotal',
            'Pajak (Tax)',
            'Service',
            'Grand Total',
            'Metode Bayar',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
