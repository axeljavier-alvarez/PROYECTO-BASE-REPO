<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#C0C0C0] min-h-screen bg-white dark:bg-zinc-800">

        {{ $slot }}

        @fluxScripts
    </body>
</html>
