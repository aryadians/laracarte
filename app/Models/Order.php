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
        // 1. Cek flag 'stock_reduced' di database agar stok tidak terpotong 2 kali
        if ($this->stock_reduced) {
            return;
        }

        // 2. Gunakan DB Transaction (Agar data konsisten, semua terpotong atau batal semua jika error)
        DB::transaction(function () {
            // Loop semua item dalam pesanan ini
            foreach ($this->items as $item) {
                $product = $item->product;

                // Jika produk ditemukan
                if ($product) {
                    // Kurangi stok produk sesuai jumlah pesanan
                    // (decrement otomatis menangani pengurangan angka di database)
                    $product->decrement('stock', $item->quantity);
                }
            }

            // 3. Tandai order ini bahwa stoknya SUDAH dipotong
            // update() ini akan mengubah kolom 'stock_reduced' jadi 1 (true)
            $this->update(['stock_reduced' => true]);
        });
    }
}
