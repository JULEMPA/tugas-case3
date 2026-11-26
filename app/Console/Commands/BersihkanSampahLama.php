<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;

class BersihkanSampahLama extends Command
{
    protected $signature = 'barang:bersihkan-sampah {--hari=30}';
    protected $description = 'Hapus permanen barang yang sudah lebih dari beberapa hari di sampah';

    public function handle(): int
    {
        $batas = now()->subDays((int) $this->option('hari'));

        $jumlah = Barang::onlyTrashed()
            ->where('deleted_at', '<', $batas)
            ->forceDelete();

        $this->info("Dihapus permanen: {$jumlah} barang.");

        return self::SUCCESS;
    }
}
