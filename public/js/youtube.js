// STATE MANAGEMENT
// ========================================
const state = {
    currentQuery: '',
    currentFilter: 'trending',
    currentCategory: 'all',
    debounceTimer: null,
    cachedVideos: new Map()
};

// ========================================
// DOM ELEMENTS
// ========================================
const elements = {
    searchInput: document.getElementById('searchInput'),
    searchBtn: document.getElementById('searchBtn'),
    videosGrid: document.getElementById('videosGrid'),
    loadingState: document.getElementById('loadingState'),
    errorState: document.getElementById('errorState'),
    errorMessage: document.getElementById('errorMessage'),
    sectionTitle: document.getElementById('sectionTitle'),
    videoModal: document.getElementById('videoModal'),
    closeModal: document.getElementById('closeModal'),
    playerContainer: document.getElementById('playerContainer'),
    modalVideoTitle: document.getElementById('modalVideoTitle'),
    modalVideoDescription: document.getElementById('modalVideoDescription'),
    filterTabs: document.querySelectorAll('.filter-tab'),
    modalOverlay: document.querySelector('.modal-overlay'),
    categoryChips: document.querySelectorAll('.category-chip')
};

// ========================================
// UTILITY FUNCTIONS
// ========================================
function formatViewCount(count) {
    if (!count) return '0 views';
    const num = parseInt(count);
    if (num >= 1000000) return `${(num / 1000000).toFixed(1)}M views`;
    if (num >= 1000) return `${(num / 1000).toFixed(1)}K views`;
    return `${num} views`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return 'Hari ini';
    if (diffDays === 1) return 'Kemarin';
    if (diffDays < 7) return `${diffDays} hari lalu`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} minggu lalu`;
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} bulan lalu`;
    return `${Math.floor(diffDays / 365)} tahun lalu`;
}

function showLoading() {
    elements.loadingState.classList.remove('hidden');
    elements.errorState.classList.add('hidden');
    elements.videosGrid.innerHTML = '';
}

function hideLoading() {
    elements.loadingState.classList.add('hidden');
}

function showError(message) {
    elements.errorState.classList.remove('hidden');
    elements.errorMessage.textContent = message;
    elements.loadingState.classList.add('hidden');
    elements.videosGrid.innerHTML = '';
}

function hideError() {
    elements.errorState.classList.add('hidden');
}

// ========================================
// API FUNCTIONS
// ========================================
async function fetchTrendingVideos() {
    try {
        showLoading();
        hideError();

        // Use 'US' as a proxy for Global trending, as YouTube API requires a region code
        const response = await fetch(`${window.apiRoutes.trending}?regionCode=US`);
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        hideLoading();
        displayVideos(data.items, 'trending');

    } catch (error) {
        console.error('Error fetching trending videos:', error);
        hideLoading();
        showError('Gagal memuat video trending. Pastikan API key valid.');
    }
}

async function searchVideos(query, region = 'ID') {
    if (!query || query.trim() === '') {
        fetchTrendingVideos();
        return;
    }

    try {
        showLoading();
        hideError();

        // Pass regionCode to the backend
        const response = await fetch(`${window.apiRoutes.search}?q=${encodeURIComponent(query)}&maxResults=12&regionCode=${region}`);
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        hideLoading();

        if (!data.items || data.items.length === 0) {
            showError(`Tidak ada hasil untuk "${query}"`);
            return;
        }

        displayVideos(data.items, 'search');

    } catch (error) {
        console.error('Error searching videos:', error);
        hideLoading();
        showError('Gagal mencari video. Silakan coba lagi.');
    }
}

// ... UI functions ...

function handleCategoryChange(category) {
    state.currentCategory = category;

    // Update active chip and get chip text
    let activeChipText = '';
    elements.categoryChips.forEach(chip => {
        if (chip.dataset.category === category) {
            chip.classList.add('active');
            activeChipText = chip.textContent;
        } else {
            chip.classList.remove('active');
        }
    });

    // Map category to search query
    const categoryQueries = {
        'all': null,
        'music': 'music',
        'gaming': 'gaming',
        'live': 'live stream',
        'podcast': 'podcast'
    };

    // Map category to region
    const categoryRegions = {
        'music': 'US',
        'gaming': 'US',
        'live': 'ID',
        'podcast': 'ID'
    };

    const query = categoryQueries[category];
    const region = categoryRegions[category] || 'ID';

    if (query) {
        // Search for category with specific region
        state.currentQuery = query;
        searchVideos(query, region);
        elements.sectionTitle.textContent = `Video ${activeChipText}`;
    } else {
        // Show trending (ID)
        fetchTrendingVideos();
    }
}

// ========================================
// UI RENDERING
// ========================================
function displayVideos(videos, type = 'trending') {
    elements.videosGrid.innerHTML = '';

    // Update section title
    if (type === 'search') {
        elements.sectionTitle.textContent = `Hasil Pencarian: "${state.currentQuery}"`;
    } else {
        elements.sectionTitle.textContent = 'Video Trending';
    }

    videos.forEach(video => {
        const videoCard = createVideoCard(video, type);
        elements.videosGrid.appendChild(videoCard);
    });
}

function createVideoCard(video, type) {
    const card = document.createElement('div');
    card.className = 'video-card';

    // Extract video ID and video data based on type
    let videoId, thumbnailUrl, title, channelTitle, publishedAt, viewCount;

    if (type === 'search') {
        videoId = video.id.videoId;
        thumbnailUrl = video.snippet.thumbnails.high?.url || video.snippet.thumbnails.medium.url;
        title = video.snippet.title;
        channelTitle = video.snippet.channelTitle;
        publishedAt = video.snippet.publishedAt;
        viewCount = null; // Search results don't include view count
    } else {
        videoId = video.id;
        thumbnailUrl = video.snippet.thumbnails.high?.url || video.snippet.thumbnails.medium.url;
        title = video.snippet.title;
        channelTitle = video.snippet.channelTitle;
        publishedAt = video.snippet.publishedAt;
        viewCount = video.statistics?.viewCount;
    }

    card.innerHTML = `
        <div class="video-thumbnail">
            <img src="${thumbnailUrl}" alt="${title}">
            <div class="play-overlay">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5V19L19 12L8 5Z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        <div class="video-info">
            <h4 class="video-title">${title}</h4>
            <p class="video-channel">${channelTitle}</p>
            <div class="video-meta">
                ${viewCount ? `<span>${formatViewCount(viewCount)}</span>` : ''}
                <span>${formatDate(publishedAt)}</span>
            </div>
        </div>
    `;

    card.addEventListener('click', () => openVideoModal(videoId, title, video.snippet.description));

    return card;
}

// ========================================
// VIDEO MODAL
// ========================================
function openVideoModal(videoId, title, description) {
    elements.videoModal.classList.remove('hidden');
    elements.modalVideoTitle.textContent = title;
    elements.modalVideoDescription.textContent = description || 'Tidak ada deskripsi tersedia.';

    // Set YouTube URL for the watch button
    const watchButton = document.getElementById('watchOnYouTube');
    watchButton.href = `https://www.youtube.com/watch?v=${videoId}`;

    // Create YouTube iframe
    elements.playerContainer.innerHTML = `
        <iframe
            src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&origin=${window.location.origin}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        ></iframe>
    `;

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    elements.videoModal.classList.add('hidden');
    elements.playerContainer.innerHTML = '';
    document.body.style.overflow = '';
}

// ========================================
// EVENT HANDLERS
// ========================================
function handleSearch() {
    const query = elements.searchInput.value.trim();
    if (query) {
        state.currentQuery = query;
        searchVideos(query);
    }
}

function handleSearchInput() {
    // Debounce search input
    clearTimeout(state.debounceTimer);
    state.debounceTimer = setTimeout(() => {
        const query = elements.searchInput.value.trim();
        if (query.length >= 3) {
            state.currentQuery = query;
            searchVideos(query);
        }
    }, 500);
}

function handleFilterChange(filterType) {
    state.currentFilter = filterType;

    // Update active tab
    elements.filterTabs.forEach(tab => {
        if (tab.dataset.filter === filterType) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });

    // Load appropriate content
    if (filterType === 'trending') {
        fetchTrendingVideos();
    } else if (filterType === 'search' && state.currentQuery) {
        searchVideos(state.currentQuery);
    }
}



// ========================================
// EVENT LISTENERS
// ========================================
elements.searchBtn.addEventListener('click', handleSearch);

elements.searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        handleSearch();
    }
});

elements.searchInput.addEventListener('input', handleSearchInput);

elements.closeModal.addEventListener('click', closeVideoModal);

elements.modalOverlay.addEventListener('click', closeVideoModal);

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !elements.videoModal.classList.contains('hidden')) {
        closeVideoModal();
    }
});

elements.filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        handleFilterChange(tab.dataset.filter);
    });
});

// Category chips event listeners
elements.categoryChips.forEach(chip => {
    chip.addEventListener('click', () => {
        handleCategoryChange(chip.dataset.category);
    });
});

// ========================================
// INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    // Load trending videos on page load
    fetchTrendingVideos();
});
