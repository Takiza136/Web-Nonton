<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Nonton - Platform modern untuk menonton dan mencari video YouTube dengan interface yang indah dan responsif">
    <meta name="keywords" content="youtube, video, nonton, streaming, indonesia">
    <meta name="author" content="Nonton App">
    
    <!-- Title -->
    <title>@yield('title', 'Nonton - YouTube Video Player')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 16.5L16 12L10 7.5V16.5Z" fill="currentColor"/>
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="currentColor"/>
                    </svg>
                    <h1>Nonton</h1>
                </div>
                
                <nav class="nav">
                    <a href="#trending" class="nav-link active">Trending</a>
                    <a href="#search" class="nav-link">Search</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Nonton. Powered by YouTube Data API v3</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/youtube.js') }}"></script>
    @stack('scripts')
</body>
</html>
