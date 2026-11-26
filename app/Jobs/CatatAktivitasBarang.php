<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CatatAktivitasBarang implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $aktivitas) {}

    public function handle(): void
    {
        Log::info('[Barang] ' . $this->aktivitas);
    }
}
