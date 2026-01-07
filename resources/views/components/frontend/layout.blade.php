<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembelajaran Digital Bersama Yudhistira</title>

    <!-- Resource Hints - DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net" />
    <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
    <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Critical CSS - Preload -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        as="style" />
    <link rel="preload" href="{{ asset('/') }}frontend/assets/css/style.css?v={{ strtotime(now()) }}"
        as="style" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"
        media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    </noscript>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" media="print"
        onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    </noscript>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    </noscript>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('/') }}frontend/assets/css/style.css?v={{ strtotime(now()) }}" />

    <!-- Google Fonts - Optimized with font-display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet" />
    </noscript>

    @stack('css')
    @if (isset($css))
        {{ $css }}
    @endif
    <!-- Critical CSS Inline -->
    <style>
        .ratio-16x9 {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 9 / 16 * 100 */
            overflow: hidden;
        }
        
        .ratio-16x9 iframe,
        .ratio-16x9 embed,
        .ratio-16x9 object {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .hero-visual {
            max-width: 30% !important;
        }

        @media(max-width: 700px) {
            .hero-visual {
                max-width: 100% !important;
            }

            .blur {
                mask-image: linear-gradient(to bottom, transparent 15%, black 100%);
                pointer-events: none;
            }
        }

        /* Prevent FOUT (Flash of Unstyled Text) */
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset($config->logo) }}" alt="" loading="eager">
                </a>
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <nav class="main-nav" id="mainNav">
                    <a href="{{ url('/') }}"
                        class="nav-link {{ request()->is('/') ? 'active' : '' }}">{{ languageText('Home', 'Beranda') }}</a>
                    <a href="{{ url('about') }}"
                        class="nav-link {{ request()->is('about') ? 'active' : '' }}">{{ languageText('About Us', 'Tentang Kami') }}</a>
                    @php
                        $isServiceActive = request()->is('service') || request()->is('service/*');
                    @endphp
                    <div class="nav-item-dropdown {{ $isServiceActive ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-dropdown {{ $isServiceActive ? 'active' : '' }}">{{ languageText('School Book Products', 'Produk Buku Sekolah') }}
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($service as $item)
                                <li><a href="{{ url('service/' . $item->url) }}"
                                        class="{{ request()->is('service/' . $item->url) ? 'active' : '' }}">{{ languageText($item->title, $item->judul) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @php
                        $isDigitalProductActive =
                            request()->is('digital-product') || request()->is('digital-product/*');
                    @endphp
                    <div class="nav-item-dropdown {{ $isDigitalProductActive ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link has-dropdown {{ $isDigitalProductActive ? 'active' : '' }}">{{ languageText('Digital Products', 'Produk Digital') }}
                            <i class="bi bi-chevron-down dropdown-arrow"></i></a>
                        <ul class="dropdown-menu">
                            @foreach ($digital_product as $item)
                                <li><a href="{{ url('digital-product/' . $item->url) }}"
                                        class="{{ request()->is('digital-product/' . $item->url) ? 'active' : '' }}">{{ languageText($item->title, $item->judul) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ url('blog') }}"
                        class="nav-link {{ request()->is('blog') || request()->is('blog/*') ? 'active' : '' }}">{{ languageText('News', 'Berita') }}</a>
                    <a href="{{ url('event') }}"
                        class="nav-link {{ request()->is('event') || request()->is('event/*') ? 'active' : '' }}">{{ languageText('Event', 'Event') }}</a>
                    <a href="{{ url('teacher-hub/login') }}"
                        class="nav-link {{ request()->is('teacher-hub/login') || request()->is('teacher-hub/*') ? 'active' : '' }}">{{ languageText('Teachers Hub', 'Teachers Hub') }}</a>
                    <a href="{{ url('catalogue') }}"
                        class="nav-link {{ request()->is('catalogue') || request()->is('catalogue/*') ? 'active' : '' }}">{{ languageText('Catalogue', 'Katalog') }}</a>
                </nav>
                <div class="header-right">
                    <div class="lang-dropdown">
                        <button class="lang-toggle">
                            <i class="bi bi-globe"></i>
                            <span>{{ session('language') === 'en' ? 'English' : 'Indonesian' }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('en') }}"
                                    class="{{ session('language') === 'en' ? 'active' : '' }}">English</a></li>
                            <li><a href="{{ url('id') }}"
                                    class="{{ session('language') !== 'en' ? 'active' : '' }}">Indonesian</a></li>
                        </ul>
                    </div>
                    <a href="{{ url('contact') }}" class="btn btn-primary">
                        <i class="bi bi-telephone"></i> {{ languageText('Contact Us', 'Hubungi Kami') }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-navigation-menu">
        <nav class="main-nav" id="mobileMainNav">
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="bi bi-x-lg"></i>
            </button>
            <a href="{{ url('/') }}"
                class="nav-link {{ request()->is('/') ? 'active' : '' }}">{{ languageText('Home', 'Beranda') }}</a>
            <a href="{{ url('about') }}"
                class="nav-link {{ request()->is('about') ? 'active' : '' }}">{{ languageText('About Us', 'Tentang Kami') }}</a>
            @php
                $isServiceActive = request()->is('service') || request()->is('service/*');
            @endphp
            <li class="nav-item-dropdown {{ $isServiceActive ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown {{ $isServiceActive ? 'active' : '' }}">{{ languageText('School Book Products', 'Produk Buku Sekolah') }}
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    @foreach ($service as $item)
                        <li><a href="{{ url('service/' . $item->url) }}"
                                class="{{ request()->is('service/' . $item->url) ? 'active' : '' }}">{{ languageText($item->title, $item->judul) }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @php
                $isDigitalProductActive = request()->is('digital-product') || request()->is('digital-product/*');
            @endphp
            <li class="nav-item-dropdown {{ $isDigitalProductActive ? 'active' : '' }}">
                <a href="#"
                    class="nav-link has-dropdown {{ $isDigitalProductActive ? 'active' : '' }}">{{ languageText('Digital Products', 'Produk Digital') }}
                    <i class="bi bi-chevron-down dropdown-arrow"></i></a>
                <ul class="dropdown-menu">
                    @foreach ($digital_product as $item)
                        <li><a href="{{ url('digital-product/' . $item->url) }}"
                                class="{{ request()->is('digital-product/' . $item->url) ? 'active' : '' }}">{{ languageText($item->title, $item->judul) }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <a href="{{ url('blog') }}"
                class="nav-link {{ request()->is('blog') || request()->is('blog/*') ? 'active' : '' }}">{{ languageText('News', 'Berita') }}</a>
            <a href="{{ url('event') }}"
                class="nav-link {{ request()->is('event') || request()->is('event/*') ? 'active' : '' }}">{{ languageText('Event', 'Event') }}</a>
            <a href="{{ url('teacher-hub/login') }}"
                class="nav-link {{ request()->is('teacher-hub/login') || request()->is('teacher-hub/*') ? 'active' : '' }}">{{ languageText('Teachers Hub', 'Teachers Hub') }}</a>
            <a href="{{ url('catalogue') }}"
                class="nav-link {{ request()->is('catalogue') || request()->is('catalogue/*') ? 'active' : '' }}">{{ languageText('Catalogue', 'Katalog') }}</a>
        </nav>
    </div>

    {{ $slot }}

    <!-- FOOTER -->
    <footer class="site-footer mt-0">
        <div class="container footer-inner">
            <div class="footer-col footer-about">
                <span class="footer-logo-text"><img src="{{ asset($config->logo_footer) }}" alt=""
                        loading="lazy"></span>
                <p>
                    {{ languageText($config->short_description, $config->deskripsi_singkat) }}
                </p>
                <a href="{{ url('about') }}"
                    class="btn-link light">{{ languageText('Learn More', 'Pelajari Lebih Lanjut') }} <i
                        class="bi bi-arrow-right"></i></a>
                <div class="footer-socials">
                    @foreach ($social_media as $item)
                        <a href="{{ $item->link }}" target="_blank" class="social-icon"
                            aria-label="{{ $item->title }}">
                            <img src="{{ asset($item->file) }}" style="width: 20px; height: 20px;"
                                alt="{{ $item->title }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="footer-col">
                <h4>{{ languageText('School Book Products', 'Produk Buku Sekolah') }}</h4>
                <ul>
                    @forelse($service as $item)
                        <li><a
                                href="{{ url('service/' . $item->url) }}">{{ languageText($item->title, $item->judul) }}</a>
                        </li>
                    @empty
                        <li><a
                                href="{{ url('service') }}">{{ languageText('View All Services', 'Lihat Semua Layanan') }}</a>
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="footer-col">
                <h4>{{ languageText('Digital Products', 'Produk Digital') }}</h4>
                <ul>
                    @forelse($digital_product as $item)
                        <li><a
                                href="{{ url('digital-product/' . $item->url) }}">{{ languageText($item->title, $item->judul) }}</a>
                        </li>
                    @empty
                        <li><a
                                href="{{ url('digital-product') }}">{{ languageText('View All Digital Products', 'Lihat Semua Produk Digital') }}</a>
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="footer-col">
                <h4>{{ languageText('Information', 'Informasi') }}</h4>
                <ul>
                    <li><a href="{{ url('blog') }}">{{ languageText('News', 'Berita') }}</a></li>
                    <li><a href="{{ url('event') }}">{{ languageText('Events', 'Event') }}</a></li>
                    <li><a href="{{ url('catalogue') }}">{{ languageText('Catalogue', 'Katalog') }}</a></li>
                    <!--<li><a href="{{ url('gallery') }}">{{ languageText('Gallery', 'Galeri') }}</a></li>-->
                </ul>
            </div>

            <div class="footer-col">
                <h4>{{ languageText('Contact Us', 'Hubungi Kami') }}</h4>
                <ul>
                    @if ($config && $config->address)
                        <li>
                            <a href="{{ url('contact') }}"><i class="bi bi-geo-alt"></i>
                                {{ $config->address }}</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ url('contact') }}"><i class="bi bi-geo-alt"></i>
                                {{ languageText('Jakarta, Indonesia', 'Jakarta, Indonesia') }}</a>
                        </li>
                    @endif
                    @if ($config && $config->notelp)
                        <li>
                            <a href="tel:{{ $config->notelp }}"><i class="bi bi-telephone"></i>
                                {{ $config->notelp }}</a>
                        </li>
                    @endif
                    @if ($config && $config->email)
                        <li>
                            <a href="mailto:{{ $config->email }}"><i class="bi bi-envelope"></i>
                                {{ $config->email }}</a>
                        </li>
                    @endif
                    @if ($config && $config->url)
                        <li>
                            <a href="{{ $config->url }}" target="_blank"><i class="bi bi-globe"></i>
                                {{ parse_url($config->url, PHP_URL_HOST) ?: $config->url }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <p>Copyright &copy;2025 Yudhistira. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- jQuery (required for Slick) - Load with defer -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" defer></script>
    <!-- Bootstrap 5 JS - Defer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer>
    </script>
    <!-- Slick JS - Defer (depends on jQuery) -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js" defer>
    </script>
    <!-- Custom JS - Defer (depends on jQuery and Slick) -->
    <script src="{{ asset('/') }}frontend/assets/js/main.js?v={{ strtotime(now()) }}" defer></script>
    @stack('js')
    @if (isset($js))
        {{ $js }}
    @endif
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('videoModal');
        const container = document.getElementById('videoContainer');
    
        // Ambil src iframe yang udah lu render dari DB (sekali doang)
        const initialIframe = container.querySelector('iframe');
        const iframeSrc = initialIframe ? initialIframe.getAttribute('src') : null;
    
        // Bersihin isi awal biar kita kontrol dari JS
        container.innerHTML = '';
    
        function renderLoader() {
            container.innerHTML = `
                <div class="video-loading">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `;
        }
    
        function renderIframe() {
            renderLoader();
    
            if (!iframeSrc) {
                container.innerHTML = `<div class="text-center p-4">Video tidak ditemukan.</div>`;
                return;
            }
    
            const iframe = document.createElement('iframe');
            iframe.setAttribute('src', iframeSrc);
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allowfullscreen', '');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    
            iframe.style.width = '100%';
            iframe.style.height = '100%';
    
            iframe.addEventListener('load', function () {
                const loader = container.querySelector('.video-loading');
                if (loader) loader.remove();
            });
    
            container.appendChild(iframe);
    
            // fallback kalau load event gak kepanggil (kadang happen)
            setTimeout(() => {
                const loader = container.querySelector('.video-loading');
                if (loader) loader.remove();
            }, 2000);
        }
    
        modal.addEventListener('shown.bs.modal', function () {
            renderIframe(); // buka modal => selalu bikin iframe baru => video restart
        });
    
        modal.addEventListener('hidden.bs.modal', function () {
            container.innerHTML = ''; // tutup => buang iframe
        });
    });
    </script>


    <style>
        .video-loading {
            position: absolute;
            inset: 0;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        
        .ratio-16x9 {
            position: relative; /* wajib biar loader absolute nempel */
        }


    </style>
</body>

</html>
