<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Aplikasi')</title>

    {{-- Bootstrap CSS (opsional) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Style custom global --}}
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 0.8rem 1rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        main {
            padding-bottom: 60px; /* beri ruang untuk footer */
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            padding: 0.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }

        /* Agar mobile lebih nyaman */
        @media (max-width: 576px) {
            header {
                font-size: 1rem;
                padding: 0.6rem;
            }
            footer {
                font-size: 0.8rem;
            }
        }
    </style>

    {{-- Stack style dari child --}}
    @stack('style')
</head>
<body>

    {{-- Header --}}
    {{-- <header>
        @yield('header', 'Aplikasi Mobile')
    </header> --}}

    {{-- Main Content --}}
    <main class="container-fluid">
        @yield('main')
    </main>

    {{-- Footer --}}
    <footer>
        &copy;it_cgt - Semangat SOnya
    </footer>

    {{-- Bootstrap JS (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Stack script dari child --}}
    @stack('scripts')
</body>
</html>
