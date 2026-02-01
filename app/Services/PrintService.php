<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Exception;

class PrintService
{
    /**
     * Fungsi utama untuk mencetak struk secara langsung
     */
    public function printDirect(Order $order)
    {
        try {
            // Ambil nama printer dari database setting (jika ada)
            // Default ke nama printer windows 'Thermal_Printer'
            $printerName = Setting::value('printer_name', 'Thermal_Printer');
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            $this->formatReceipt($printer, $order);

            $printer->cut();
            $printer->close();

            return true;
        } catch (Exception $e) {
            // Log error atau lemparkan kembali
            throw new Exception("Gagal mencetak: " . $e->getMessage());
        }
    }

    /**
     * Format tampilan struk belanja
     */
    private function formatReceipt($printer, $order)
    {
        $storeName = Setting::value('store_name', 'LaraCarte Resto');
        $storeAddress = Setting::value('store_address', 'Jl. Digital No. 1');

        /* Header */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text($storeName . "\n");
        $printer->selectPrintMode();
        $printer->text($storeAddress . "\n");
        $printer->text(str_repeat("-", 32) . "\n");

        /* Info Transaksi */
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Order ID: #" . $order->id . "\n");
        $printer->text("Meja: " . ($order->table->name ?? 'Takeaway') . "\n");
        $printer->text("Tgl: " . $order->created_at->format('d/m/y H:i') . "\n");
        $printer->text(str_repeat("-", 32) . "\n");

        /* Items */
        foreach ($order->items as $item) {
            $name = $item->product->name;
            $printer->text($name . "\n");
            
            // Print Varian
            if ($item->selectedVariants->isNotEmpty()) {
                $variants = $item->selectedVariants->pluck('option_name')->join(', ');
                $printer->selectPrintMode(Printer::MODE_FONT_B); // Font kecil
                $printer->text("  + " . $variants . "\n");
                $printer->selectPrintMode(); // Balik normal
            }
            
            $qty = $item->quantity . " x " . number_format($item->price, 0, ',', '.');
            $sub = number_format($item->quantity * $item->price, 0, ',', '.');
            
            // Format alignment kanan-kiri sederhana
            $printer->text($this->formatLine($qty, $sub) . "\n");
        }

        $printer->text(str_repeat("-", 32) . "\n");

        /* Summary */
        $printer->text($this->formatLine("Subtotal", number_format($order->subtotal, 0, ',', '.')) . "\n");
        if ($order->service_charge > 0) {
            $printer->text($this->formatLine("Service", number_format($order->service_charge, 0, ',', '.')) . "\n");
        }
        if ($order->tax_amount > 0) {
            $printer->text($this->formatLine("Pajak", number_format($order->tax_amount, 0, ',', '.')) . "\n");
        }
        
        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->text($this->formatLine("TOTAL", number_format($order->total_price, 0, ',', '.')) . "\n");
        $printer->selectPrintMode();

        $printer->text(str_repeat("-", 32) . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima Kasih\n");
        $printer->text("Silakan Datang Kembali\n\n\n");
    }

    private function formatLine($left, $right, $width = 32)
    {
        $leftWidth = $width - strlen($right);
        return str_pad($left, $leftWidth, " ") . $right;
    }
}
