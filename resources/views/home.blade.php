@extends('layouts.app')

@section('title', 'Nonton - YouTube Video Player')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <section class="hero">
        <h2 class="hero-title">Temukan & Tonton Video Favorit Anda</h2>
        <p class="hero-subtitle">Platform modern untuk browsing dan menonton video YouTube</p>
        
        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-box">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="search-input" 
                    placeholder="Cari video, channel, atau topik..."
                    autocomplete="off"
                >
                <button id="searchBtn" class="search-btn">
                    <span>Cari</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Category Filter Chips -->
    <section class="category-section">
        <div class="category-chips">
            <button class="category-chip active" data-category="all">Semua</button>
            <button class="category-chip" data-category="music">Musik</button>
            <button class="category-chip" data-category="gaming">Gaming</button>
            <button class="category-chip" data-category="live">Live</button>
            <button class="category-chip" data-category="podcast">Podcast</button>
        </div>
    </section>

    <!-- Loading State -->
    <div id="loadingState" class="loading-state hidden">
        <div class="spinner"></div>
        <p>Memuat video...</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="error-state hidden">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 8V12M12 16H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p id="errorMessage">Terjadi kesalahan. Silakan coba lagi.</p>
    </div>

    <!-- Videos Section -->
    <section class="videos-section">
        <div class="section-header">
            <h3 id="sectionTitle">Video Trending</h3>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="trending">Trending</button>
                <button class="filter-tab" data-filter="search" style="display: none;">Hasil Pencarian</button>
            </div>
        </div>
        
        <div id="videosGrid" class="videos-grid">
            <!-- Videos will be loaded here dynamically -->
        </div>
    </section>
</div>

<!-- Video Player Modal -->
<div id="videoModal" class="modal hidden">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button id="closeModal" class="modal-close">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="modal-player">
            <div id="playerContainer" class="player-container">
                <!-- YouTube iframe will be inserted here -->
            </div>
            <div class="video-info">
                <div class="video-header">
                    <h4 id="modalVideoTitle"></h4>
                    <a id="watchOnYouTube" href="#" target="_blank" class="watch-youtube-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 6H6C4.89543 6 4 6.89543 4 8V18C4 19.1046 4.89543 20 6 20H16C17.1046 20 18 19.1046 18 18V14M14 4H20M20 4V10M20 4L10 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Tonton di YouTube
                    </a>
                </div>
                <p id="modalVideoDescription"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pass Laravel routes to JavaScript
    window.apiRoutes = {
        search: "{{ route('youtube.search') }}",
        trending: "{{ route('youtube.trending') }}",
        videoDetails: "{{ url('/api/video') }}"
    };
</script>
@endpush
