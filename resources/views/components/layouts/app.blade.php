<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>{{ $title ?? 'Tugas Case 3' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-100 text-zinc-900 antialiased">
    <main class="mx-auto w-full max-w-md px-4 py-10 sm:max-w-3xl lg:max-w-5xl">
        {{ $slot }}
    </main>
    @fluxScripts
</body>
</html>
