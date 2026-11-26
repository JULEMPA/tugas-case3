<?php

namespace App\Livewire;

use App\Jobs\CatatAktivitasBarang;
use App\Models\Barang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class BarangIndex extends Component
{
    use WithPagination;

    public ?int $editId = null;
    public string $kode = '';
    public string $nama = '';
    public string $kategori = '';
    public ?int $harga = null;
    public ?int $stok = null;

    public string $search = '';
    public string $sortBy = 'terbaru';
    public bool $showForm = false;

    public string $pesan = '';
    public int $pesanKe = 0;

    public function updatedSearch(): void
    {$this->resetPage();}

    public function updatedSortBy(): void
    {$this->resetPage();}

    protected function rules(): array
    {
        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                $this->editId
                    ? Rule::unique('barangs', 'kode')->ignore($this->editId)
                    : Rule::unique('barangs', 'kode'),
            ],
            'nama' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $barang = Barang::findOrFail($id);

        $this->editId = $barang->id;
        $this->kode = $barang->kode;
        $this->nama = $barang->nama;
        $this->kategori = $barang->kategori;
        $this->harga = $barang->harga;
        $this->stok = $barang->stok;

        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editId) {
            Barang::findOrFail($this->editId)->update($data);
            $this->beri('Barang berhasil diperbarui.');
        } else {
            Barang::create($data);
            $this->beri('Barang berhasil ditambahkan.');
        }

        $this->hapusCache();
        $this->resetForm();
        $this->showForm = false;
    }

    public function tutupForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        CatatAktivitasBarang::dispatch("Dipindahkan ke sampah: {$barang->kode} ({$barang->nama})");

        $this->hapusCache();
        $this->beri('Barang dipindahkan ke sampah.');
    }

    public function render()
    {
        $query = Barang::query()
           ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('kode', 'like', "%{$this->search}%")
                        ->orWhere('nama', 'like', "%{$this->search}%")
                        ->orWhere('kategori', 'like', "%{$this->search}%");
                });
            });

        $barangs = $this->terapkanUrutan($query)->paginate(5);

        return view('livewire.barang-index', [
            'barangs' => $barangs,
            'statistik' => $this->statistik(),
            'jumlahSampah' => Barang::onlyTrashed()->count(),
        ]);
    }


    private function statistik(): array
    {
        return Cache::remember('barang-statistik', now()->addMinutes(10), function () {
            return [
                'jenis' => Barang::count(),
                'total_stok' => (int) Barang::sum('stok'),
                'total_nilai' => (int) Barang::selectRaw('COALESCE(SUM(harga * stok), 0) as total')->value('total'),
                'dihitung' => now()->format('H:i:s'),
            ];
        });
    }
    private function terapkanUrutan($query)
    {
        return match ($this->sortBy) {
            'terlama' => $query->orderBy('created_at')->orderBy('id'),
            'nama_az' => $query->orderBy('nama')->orderBy('id'),
            'nama_za' => $query->orderByDesc('nama')->orderBy('id'),
            'harga_termurah' => $query->orderBy('harga')->orderBy('id'),
            'harga_termahal' => $query->orderByDesc('harga')->orderBy('id'),
            'stok_terbanyak' => $query->orderByDesc('stok')->orderBy('id'),
            'stok_tersedikit' => $query->orderBy('stok')->orderBy('id'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };
    }


    private function hapusCache(): void
    {
        Cache::forget('barang-statistik');
    }

    private function resetForm(): void
    {
        $this->reset(['editId', 'kode', 'nama', 'kategori', 'harga', 'stok']);
        $this->resetErrorBag();
    }

    private function beri(string $teks): void
    {
        $this->pesan = $teks;
        $this->pesanKe++;
    }
}
