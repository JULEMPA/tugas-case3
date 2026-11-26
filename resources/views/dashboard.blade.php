<x-layouts.app title="Dashboard">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h1>
            <p class="text-zinc-600">Kelola data barang di bawah ini.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:button type="submit" variant="danger">Logout</flux:button>
        </form>
    </div>

    <livewire:barang-index />
</x-layouts.app>
