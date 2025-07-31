<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apotek Sakura')</title>

    <!-- Favicon-->
    <link rel="icon" href="{{ asset('landing/assets/sakurapotek2.ico') }}" />

    <!-- Bootstrap CSS (CDN Fallback) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" />

    <!-- Custom Styles -->
    <link href="{{ asset('landing/css/styles.css') }}?v={{ config('app.version', '1.0') }}" rel="stylesheet" />
    
    <style>
        /* Ensure consistent styling */
        body {
            font-family: "Roboto Slab", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        
        /* Fix for navigation between pages */
        .page-section {
            padding: 6rem 0;
        }
        
        /* Ensure proper Bootstrap loading */
        .container, .container-fluid, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
            width: 100%;
            padding-right: var(--bs-gutter-x, 0.75rem);
            padding-left: var(--bs-gutter-x, 0.75rem);
            margin-right: auto;
            margin-left: auto;
        }
    </style>
</head>
<body id="page-top">

    @include('landing.partials.navbar')

    @yield('content')

    @include('landing.partials.footer')

    @yield('scripts')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="{{ asset('landing/js/scripts.js') }}?v={{ filemtime(public_path('landing/js/scripts.js')) }}"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    
    <!-- Ensure proper page loading -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force re-apply Bootstrap styles if needed
            if (typeof bootstrap !== 'undefined') {
                console.log('Bootstrap loaded successfully');
            } else {
                console.warn('Bootstrap not loaded properly');
            }
        });
    </script>
</body>
</html>
