<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PautangPro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }

        .brand-hero-bg {
            background-image:
                linear-gradient(135deg, rgba(15,23,42,.88) 0%, rgba(15,76,84,.72) 100%),
                url('{{ asset('images/landing-hero.jpg') }}');
            background-size: cover;
            background-position: center;
        }

        .btn-brand-gradient {
            background: linear-gradient(135deg, #0284c7 0%, #10b981 100%);
            border: none;
            color: #fff;
            transition: filter .2s ease, transform .2s ease;
        }
        .btn-brand-gradient:hover,
        .btn-brand-gradient:focus {
            filter: brightness(1.08);
            transform: translateY(-1px);
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">
    @if (session('status'))
        <div class="alert alert-success text-center mb-0 rounded-0">{{ session('status') }}</div>
    @endif

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>