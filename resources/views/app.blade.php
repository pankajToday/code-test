<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ env('APP_NAME', 'My Day Book') }}</title>
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
{{-- font-sans antialiased --}}
<body class="font-sans antialiased">      
      @inertia

</body>
</html>
