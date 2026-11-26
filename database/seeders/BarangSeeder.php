<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'BRG001', 'nama' => 'Laptop Asus', 'kategori' => 'Elektronik', 'harga' => 8500000, 'stok' => 5],
            ['kode' => 'BRG002', 'nama' => 'Mouse Logitech', 'kategori' => 'Aksesoris', 'harga' => 150000, 'stok' => 20],
            ['kode' => 'BRG003', 'nama' => 'Keyboard Mekanik', 'kategori' => 'Aksesoris', 'harga' => 450000, 'stok' => 10],
            ['kode' => 'BRG004', 'nama' => 'Monitor 24 inci', 'kategori' => 'Elektronik', 'harga' => 1800000, 'stok' => 7],
            ['kode' => 'BRG005', 'nama' => 'Kabel HDMI', 'kategori' => 'Aksesoris', 'harga' => 45000, 'stok' => 50],
        ];

        foreach ($data as $item) {
            Barang::firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
