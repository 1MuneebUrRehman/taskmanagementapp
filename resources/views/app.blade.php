<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Theme initialization to prevent flash of wrong theme -->
        <script>
            (() => {
                try {
                    const root = document.documentElement;
                    const stored = localStorage.getItem('theme');
                    const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const isDark = stored ? stored === 'dark' : systemPrefersDark;
                    if (isDark) {
                        root.classList.add('dark');
                    } else {
                        root.classList.remove('dark');
                    }
                } catch (e) {
                    console.log('Error in Theme Initializations..........')
                    console.log(e)
                }
            })();
        </script>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        @inertia
    </body>
</html>
