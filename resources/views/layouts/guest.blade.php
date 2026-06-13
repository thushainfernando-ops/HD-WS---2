<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(255,107,53,0.18),_transparent_26%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.14),_transparent_24%),linear-gradient(180deg,#f8fafc,#ffffff)]">
        <div class="min-h-screen px-4 py-10 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
