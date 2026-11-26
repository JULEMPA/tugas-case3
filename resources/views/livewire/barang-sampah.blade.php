<div class="space-y-6">

    <p class="text-sm text-zinc-600">
        Barang di sampah tidak tampil di daftar utama. Kamu bisa memulihkannya atau menghapusnya permanen.
    </p>

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

    @if ($barangs->isEmpty())
        <div class="rounded-xl bg-white p-10 text-center shadow">
            <p class="text-4xl">🗑️</p>
            <p class="mt-2 font-semibold">Sampah kosong</p>
            <p class="text-sm text-zinc-500">Barang yang dihapus akan muncul di sini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($barangs as $barang)
                <div wire:key="sampah-{{ $barang->id }}" class="flex flex-col rounded-xl bg-white p-5 shadow">
                    <div class="mb-3 flex items-start justify-between gap-2">
                        <div>
                            <p class="font-mono text-xs text-zinc-500">{{ $barang->kode }}</p>
                            <p class="text-lg font-semibold">{{ $barang->nama }}</p>
                        </div>
                        <span class="rounded bg-zinc-100 px-2 py-1 text-xs text-zinc-600">{{ $barang->kategori }}</span>
                    </div>

                    <p class="text-sm text-zinc-600">
                        Stok: {{ $barang->stok }} · Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-zinc-500">
                        Dibuang pada {{ $barang->deleted_at->format('d M Y, H:i') }}
                    </p>

                    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:justify-end">
                        <flux:button size="sm" wire:click="restore({{ $barang->id }})"
                                     aria-label="Pulihkan {{ $barang->nama }}">Pulihkan</flux:button>
                        <flux:button size="sm" variant="danger"
                                     wire:click="forceDelete({{ $barang->id }})"
                                     wire:confirm="Hapus permanen? Data tidak bisa dikembalikan."
                                     aria-label="Hapus permanen {{ $barang->nama }}">Hapus Permanen</flux:button>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            {{ $barangs->links() }}
        </div>
    @endif
</div>
