<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | File Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal">
                        Go to Dashboard
                    </a>
            @endif
            </nav>
            @endif
        </header>
        <div
            class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div
                    class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <h1 class="mb-1 font-medium text-2xl">Welcome to Your File Management System</h1>
                    <p class="mb-4 text-[#706f6c]">Easily upload, organize, and share your files and
                        folders securely in the cloud. Collaborate with your team and access your documents anywhere,
                        anytime.</p>
                    <ul class="mb-6 space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                            Secure file storage and sharing
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                            Organize files in folders and subfolders
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                            Quick upload and download
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-purple-500"></span>
                            Manage access and privacy
                        </li>
                    </ul>
                    <div class="flex gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm">Go
                                to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm">Log
                                in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow text-sm">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div
                    class="bg-[#fff2f2] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden flex items-center justify-center">
                    <svg class="w-3/4 mx-auto" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="30" width="100" height="60" rx="8" fill="#F53003"
                            fill-opacity="0.1" />
                        <rect x="20" y="40" width="80" height="40" rx="6" fill="#F53003"
                            fill-opacity="0.2" />
                        <rect x="35" y="55" width="50" height="10" rx="2" fill="#F53003" />
                        <rect x="35" y="70" width="30" height="6" rx="2" fill="#F53003"
                            fill-opacity="0.7" />
                        <rect x="68" y="70" width="17" height="6" rx="2" fill="#F53003"
                            fill-opacity="0.4" />
                    </svg>
                </div>
            </main>
        </div>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>

    </html>
