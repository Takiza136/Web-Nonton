# Nonton - YouTube Video Web Application

Platform modern untuk browsing dan menonton video YouTube dengan interface yang indah dan responsif.

## 🎯 Fitur

- **Search Video**: Cari video YouTube dengan real-time results
- **Video Player**: Embedded YouTube player dalam modal yang elegant
- **Trending Videos**: Lihat video trending di Indonesia
- **Responsive Design**: Tampil sempurna di semua device
- **Modern UI**: Dark theme dengan glassmorphism effects
- **Fast & Smooth**: Smooth animations dan transitions

## 🚀 Setup & Installation

### Prerequisites

- PHP 8.2 atau lebih tinggi
- Composer
- YouTube Data API v3 Key

### Langkah Instalasi

1. **Clone atau download project ini**

2. **Install dependencies**
   ```bash
   cd youtube-app
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Tambahkan YouTube API Key di file `.env`**
   ```
   YOUTUBE_API_KEY=your_api_key_here
   ```

5. **Jalankan development server**
   ```bash
   php artisan serve
   ```

6. **Buka browser dan akses**
   ```
   http://localhost:8000
   ```

## 🔑 Cara Mendapatkan YouTube API Key

1. Kunjungi [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih yang sudah ada
3. Enable **YouTube Data API v3**
4. Buat credentials → API Key
5. Copy API key ke file `.env`

## 🛠️ Teknologi

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, Vanilla JavaScript
- **Styling**: Custom CSS dengan modern design system
- **API**: YouTube Data API v3

## 📱 Screenshots

Interface modern dengan dark theme, search yang responsive, dan video player yang smooth.

## 📝 License

Open source untuk tujuan pembelajaran.

---

**Dibuat dengan ❤️ menggunakan Laravel dan YouTube API**
