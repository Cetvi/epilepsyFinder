<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Epilepsy Finder</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col gap-6">
    <header class="w-full max-w-md flex flex-col items-center gap-4">
        <div class="flex justify-center">

            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('logo/logo.png') }}" alt="Epilepsy Finder Logo"
                    class="w-auto h-40 mb-2 rounded-full" />
                <h1 class="text-3xl font-semibold dark:text-blue-400 mb-4 mt-10">Epilepsy Finder</h1>
            </div>
        </div>

        @if (Route::has('login'))
            <nav class="flex gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-6 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full max-w-md text-center text-[#706f6c] dark:text-[#A1A09A]">
        <main class="p-6 bg-white dark:bg-[#161615] rounded-lg shadow-lg">
            <h2 class="mb-2 font-medium text-lg dark:text-[#EDEDEC]">Let's get started</h2>
            <p>Find more about epilepsy!<br /></p>
        </main>
    </div>
</body>

</html>
