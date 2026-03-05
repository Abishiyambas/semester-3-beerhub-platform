<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="w-full py-6">
        @isset($header)
            <header class="mb-6 px-4">
                {{ $header }}
            </header>
        @endisset

        <main class="px-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
