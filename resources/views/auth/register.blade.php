<x-layouts.app title="Daftar">
    <h1 class="mb-6 text-2xl font-bold">Daftar Akun</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 rounded-xl bg-white p-6 shadow">
        @csrf

        <flux:input name="name" label="Nama Lengkap" value="{{ old('name') }}" required />
        <flux:input name="email" type="email" label="Email" value="{{ old('email') }}" required />
        <flux:input name="password" type="password" label="Password (minimal 8 karakter)" required />
        <flux:input name="password_confirmation" type="password" label="Ulangi Password" required />

        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <flux:button type="submit" variant="primary" class="w-full">Daftar</flux:button>
    </form>

    <p class="mt-4 text-center text-sm">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 underline">Login di sini</a>
    </p>
</x-layouts.app>
