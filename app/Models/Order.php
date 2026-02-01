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
    }
}
