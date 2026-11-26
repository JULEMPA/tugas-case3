<x-layouts.app title="Login">
    <h1 class="mb-6 text-2xl font-bold">Login</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 rounded-xl bg-white p-6 shadow">
        @csrf

        <flux:input name="email" type="email" label="Email" value="{{ old('email') }}" required />
        <flux:input name="password" type="password" label="Password" required />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1" class="rounded"> Ingat saya
        </label>

        @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

        <flux:button type="submit" variant="primary" class="w-full">Login</flux:button>
    </form>

    <p class="mt-4 text-center text-sm">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 underline">Daftar di sini</a>
    </p>
</x-layouts.app>
