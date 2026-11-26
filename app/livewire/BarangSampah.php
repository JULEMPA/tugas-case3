<?php

namespace App\Livewire;

use App\Models\Barang;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class BarangSampah extends Component
{
    use WithPagination;

    public string $pesan = '';
    public int $pesanKe = 0;

    public function restore(int $id): void
    {
        Barang::onlyTrashed()->findOrFail($id)->restore();

        Cache::forget('barang-statistik');
        $this->beri('Barang berhasil dipulihkan.');
    }

    public function forceDelete(int $id): void
    {
        Barang::onlyTrashed()->findOrFail($id)->forceDelete();

        Cache::forget('barang-statistik');
        $this->beri('Barang dihapus permanen.');
    }

    public function render()
    {
        return view('livewire.barang-sampah', [
            'barangs' => Barang::onlyTrashed()
                ->latest('deleted_at')
                ->paginate(6),
        ]);
    }

    private function beri(string $teks): void
    {
        $this->pesan = $teks;
        $this->pesanKe++;
    }
}
