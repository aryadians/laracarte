<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Wajib ada untuk fitur stok

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke tabel OrderItem (Rincian Pesanan)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke tabel Table (Meja)
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * --- LOGIKA PENGURANGAN STOK OTOMATIS ---
     * Fungsi ini dipanggil saat status pesanan berubah jadi 'Served' atau 'Paid'.
     */
    public function reduceStock()
    {
        if ($this->stock_reduced) {
            return;
        }

        DB::transaction(function () {
            foreach ($this->items as $item) {
                $product = $item->product;

                if ($product) {
                    // 1. Kurangi Stok Produk Jadi (Existing)
                    $product->decrement('stock', $item->quantity);

                    // 2. Kurangi Stok Bahan Baku (New)
                    // Ambil relasi ingredients dari produk
                    foreach ($product->ingredients as $ingredient) {
                        // total kebutuhan = qty pesanan x qty per resep
                        $neededQty = $item->quantity * $ingredient->pivot->quantity;
                        $ingredient->decrement('stock', $neededQty);
                    }
                }
            }

            $this->update(['stock_reduced' => true]);
        });
    }    public function getWhatsappUrlAttribute()
    {
        $storeName = \App\Models\Setting::value('store_name', 'LaraCarte Resto');
        $date = $this->created_at->format('d M Y H:i');
        
        $message = "*STRUK DIGITAL - {$storeName}*\n";
        $message .= "--------------------------------\n";
        $message .= "Order ID: #{$this->id}\n";
        $message .= "Tgl: {$date}\n";
        $message .= "Meja: " . ($this->table->name ?? 'Takeaway') . "\n";
        $message .= "--------------------------------\n";
        
        foreach ($this->items as $item) {
            $message .= "{$item->quantity}x {$item->product->name} (" . number_format($item->price, 0, ',', '.') . ")\n";
            // Jika ada varian (opsional, jika ingin detail)
            // $message .= "   + Varian...\n";
        }
        
        $message .= "--------------------------------\n";
        $message .= "Total: Rp " . number_format($this->total_price, 0, ',', '.') . "\n";
        $message .= "Status: " . strtoupper($this->payment_method) . " ({$this->status})\n";
        $message .= "--------------------------------\n";
        $message .= "Terima kasih telah berkunjung!\n";
        $message .= url('/');

        return "https://wa.me/?text=" . urlencode($message);
    }
}
