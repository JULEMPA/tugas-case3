<x-layouts.app title="Sampah">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
           class="rounded-full p-2 text-xl hover:bg-zinc-200"
           aria-label="Kembali ke daftar barang">←</a>
        <h1 class="text-2xl font-bold">Sampah</h1>
    </div>

    <livewire:barang-sampah />
</x-layouts.app>
