<div class="space-y-6">

    {{-- Statistik (disimpan di Cache) --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow">
            <p class="text-sm text-zinc-500">Jenis Barang</p>
            <p class="text-2xl font-bold">{{ $statistik['jenis'] }}</p>
        </div>
        <div class="rounded-xl bg-white p-5 shadow">
            <p class="text-sm text-zinc-500">Total Stok</p>
            <p class="text-2xl font-bold">{{ number_format($statistik['total_stok']) }}</p>
        </div>
        <div class="rounded-xl bg-white p-5 shadow">
            <p class="text-sm text-zinc-500">Total Nilai Stok</p>
            <p class="text-2xl font-bold">Rp {{ number_format($statistik['total_nilai'], 0, ',', '.') }}</p>
        </div>
    </div>
    <p class="-mt-4 text-xs text-zinc-500">
        Statistik ini diambil dari cache, dihitung terakhir pukul {{ $statistik['dihitung'] }}.
    </p>

    {{-- Notifikasi (Alpine.js menyembunyikannya otomatis setelah 3 detik) --}}
    @if ($pesan)
        <div wire:key="pesan-{{ $pesanKe }}"
             x-data="{ tampil: true }"
             x-init="setTimeout(() => tampil = false, 3000)"
             x-show="tampil"
             x-transition
             class="rounded-lg bg-green-100 p-4 text-green-800">
            {{ $pesan }}
        </div>
    @endif

    {{-- Toolbar: pencarian, sort by, sampah, dan tambah --}}
    <div class="flex flex-col gap-4 rounded-xl bg-white p-5 shadow sm:flex-row sm:items-end sm:justify-between">
        <div class="w-full sm:max-w-xs">
            <flux:input wire:model.live.debounce.300ms="search" label="Cari" placeholder="Kode, nama, atau kategori" />
        </div>
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex flex-col gap-1">
                <label for="sortBy" class="text-sm font-medium text-zinc-700">Sort by</label>
                <select id="sortBy"
                        wire:model.live="sortBy"
                        class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm focus:border-zinc-500 focus:outline-none">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="nama_az">Nama A-Z</option>
                    <option value="nama_za">Nama Z-A</option>
                    <option value="harga_termurah">Harga Termurah</option>
                    <option value="harga_termahal">Harga Termahal</option>
                    <option value="stok_terbanyak">Stok Terbanyak</option>
                    <option value="stok_tersedikit">Stok Tersedikit</option>
                </select>
            </div>

            <a href="{{ route('barang.sampah') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium hover:bg-zinc-50"
               aria-label="Buka sampah">
                🗑️ Sampah
                @if ($jumlahSampah > 0)
                    <span class="rounded-full bg-red-100 px-2 text-xs text-red-700">{{ $jumlahSampah }}</span>
                @endif
            </a>
            <flux:button variant="primary" wire:click="create">+ Tambah Barang</flux:button>
        </div>
    </div>


    {{-- Form tambah/edit --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <h2 class="mb-4 text-lg font-bold">{{ $editId ? 'Edit Barang' : 'Tambah Barang' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <flux:input wire:model="kode" label="Kode Barang" />
                        @error('kode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <flux:input wire:model="nama" label="Nama Barang" />
                        @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <flux:input wire:model="kategori" label="Kategori" />
                        @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <flux:input type="number" wire:model="harga" label="Harga (Rp)" min="0" />
                            @error('harga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:input type="number" wire:model="stok" label="Stok" min="0" />
                            @error('stok') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-2">
                        <flux:button type="button" wire:click="tutupForm">Batal</flux:button>
                        <flux:button type="submit" variant="primary">Simpan</flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Tabel data aktif --}}
    <div class="overflow-x-auto rounded-xl bg-white shadow">
        <table class="min-w-full divide-y divide-zinc-200 text-sm">
            <thead class="bg-zinc-50 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Kode</th>
                    <th class="px-4 py-3 font-semibold">Nama</th>
                    <th class="px-4 py-3 font-semibold">Kategori</th>
                    <th class="px-4 py-3 text-right font-semibold">Harga</th>
                    <th class="px-4 py-3 text-right font-semibold">Stok</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse ($barangs as $barang)
                    <tr wire:key="barang-{{ $barang->id }}">
                        <td class="whitespace-nowrap px-4 py-3 font-mono">{{ $barang->kode }}</td>
                        <td class="px-4 py-3">{{ $barang->nama }}</td>
                        <td class="px-4 py-3">{{ $barang->kategori }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $barang->stok }}</td>
                        <td class="px-4 py-3">
                            @if ($barang->stok == 0)
                                <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">Habis</span>
                            @elseif ($barang->stok <= 5)
                                <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-800">Menipis</span>
                            @else
                                <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">Tersedia</span>
                            @endif
                        </td>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex gap-2">
                                <flux:button size="sm" wire:click="edit({{ $barang->id }})" aria-label="Edit {{ $barang->nama }}">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $barang->id }})"
                                             wire:confirm="Pindahkan barang ini ke sampah?"
                                             aria-label="Hapus {{ $barang->nama }}">Hapus</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-zinc-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $barangs->links() }}
    </div>
</div>
