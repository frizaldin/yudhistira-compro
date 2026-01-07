<x-frontend.layout>
    <!-- HERO -->
    <section class="hero">
        <div class="hero-slider">
            @foreach ($slider as $item)
                <div class="hero-slide">
                    <div class="hero-bg"
                        @if (isset($item->background) && $item->background) style="background-image: url('{{ asset($item->background) }}');" @endif>
                    </div>
                    <div class="container z-index-98">
                        <div class="row ">
                            <div class="col-lg-6">
                                <div class="hero-content">
                                    <h1>{{ languageText($item->title, $item->judul) }}</h1>
                                    <div class="hero-actions">
                                        <a href="{{ $item->button_link_1 }}"
                                            class="btn btn-hero-cta">{{ languageText($item->button_text_1, $item->tombol_teks_1) }}
                                            <span class="btn-arrow-circle"><i class="bi bi-arrow-right"></i></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <img src="{{ asset($item->photo) }}" class="blur hero-kid img-fluid" alt="Anak Membawa Buku"
                            loading="lazy" />
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- ABOUT STORY -->
    <section class="section section-story">
        <div class="container story-inner">
            <div class="story-text">
                <h2>{{ languageText($about->title, $about->judul) }}</h2>
                <div>
                    {!! languageText($about->overview, $about->pratinjau) !!}
                </div>
            </div>
            <div class="story-video">
                <div class="video-frame">
                    <img src="{{ asset($about->file) }}" alt="Video Thumbnail" loading="lazy" />
                    <a href="#" class="play-button" data-bs-toggle="modal" data-bs-target="#videoModal">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container stats-row">
            @foreach ($counter as $item)
                <div class="stat-item">
                    <div class="stat-icon">
                        <img src="{{ asset($item->file) }}" alt="Tahun" loading="lazy" />
                    </div>
                    <h3 data-target="{{ $item->number }}">
                        <span class="counter-number">0</span> {{ languageText($item->item, $item->satuan) }}
                    </h3>
                    <p>{{ languageText($item->title, $item->judul) }}</p>
                </div>
            @endforeach
        </div>
    </section>
    <style>
        .school-cards-slider .slick-track {
            display: flex;
        }
        
        .school-cards-slider .slide {
            height: auto;
            display: flex;
        }
        
        .school-card {
            flex-direction: column;
            height: 100%;
        }

    </style>
    <!-- SCHOOL BOOKS -->
    <section class="section section-school-books"
        style="background-image: url('assets/img/bg-buku-sekolah-yudhistira.png')">
        <div class="container">
            <div class="section-head text-center mb-5">
                <h2>{{ languageText($configuration->service_title, $configuration->service_judul) }}</h2>
                <p>{{ languageText($configuration->service_description, $configuration->service_deskripsi) }}
                </p>
            </div>
            <div class="school-cards-slider">
                @foreach ($service as $item)
                    <div class="slide">
                        <article class="school-card"
                            style="background: url({{ asset($item->bg_potrait) }}) no-repeat center center; background-size: cover;">
                            <h3>{{ languageText($item->title, $item->judul) }}</h3>
                            <p>
                                {!! languageText($item->description, $item->deskripsi) !!}
                            </p>
                            <p>
                                <a href="{{ url('service/' . $item->url) }}" class="btn btn-school-card">Cek Detail</a>
                            </p>
                            <img src="{{ asset($item->file) }}" alt="{{ languageText($item->title, $item->judul) }}"
                                class="school-photo" loading="lazy" />
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- DIGITAL PRODUCTS -->
    <section class="section section-digital">
        <div class="container">
            <div class="section-head">
                <h2>{{ languageText($configuration->digital_product_title, $configuration->digital_product_judul) }}
                </h2>
                <p>{!! languageText($configuration->digital_product_description, $configuration->digital_product_deskripsi) !!}</p>
            </div>
            <div class="digital-slider">
                @foreach ($digital_product as $item)
                    <div class="slide p-3">
                        <article class="digital-card">
                            <div class="digital-image d-flex justify-content-center align-items-center">
                                <div class="digital-logo">
                                    <img src="{{ asset($item->logo) }}" alt="YuPintar" loading="lazy" />
                                </div>
                            </div>
                            <h3>{{ languageText($item->title, $item->judul) }}</h3>
                            <p>
                                {!! languageText($item->description, $item->deskripsi) !!}
                            </p>
                            <a href="{{ url('digital-product/' . $item->url) }}"
                                class="btn btn-outline-danger">{{ languageText('Learn More', 'Pelajari Lebih Lanjut') }}</a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- EXPLORE BOOKS -->
    <section class="section section-explore">
        <div class="container">
            <div class="section-head text-center mb-5">
                <h2>{{ languageText('Explore Books', 'Jelajahi Buku-Buku') }} <span>Yudhistira</span></h2>
            </div>
            <div class="books-slider">
                @foreach ($product as $item)
                    <article class="book-card">
                        <div class="book-image d-flex justify-content-center align-items-center">
                            @if ($item->photo)
                                @if (isset($item->is_api_product) && $item->is_api_product)
                                    <img src="{{ $item->photo }}" alt="{{ languageText($item->name, $item->nama) }}"
                                        class="img-fluid" loading="lazy" />
                                @else
                                    <img src="{{ asset($item->photo) }}"
                                        alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid"
                                        loading="lazy" />
                                @endif
                            @else
                                <img src="{{ asset('frontend/assets/img/default-product.png') }}"
                                    alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid"
                                    loading="lazy" />
                            @endif
                        </div>
                        <h4>{{ languageText($item->name, $item->nama) }}</h4>
                        <div class="d-flex justify-content-evenly">
                            @if (isset($item->is_api_product) && $item->is_api_product)
                                <a href="{{ $item->link ?? $item->url }}" target="_blank"
                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="{{ languageText('Buy Now', 'Belanja Sekarang') }}"
                                    title="{{ languageText('Buy Now', 'Belanja Sekarang') }}"><i
                                        class="m-0 bi bi-cart"></i></a>
                            @else
                                <a href="{{ $item->link ?? url('product/' . $item->url) }}"
                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="{{ languageText('Buy Now', 'Belanja Sekarang') }}"
                                    title="{{ languageText('Buy Now', 'Belanja Sekarang') }}"><i
                                        class="m-0 bi bi-cart"></i></a>
                            @endif
                            @if ($item->type_sample == 'internal')
                                @if ($item->sample_product_id)
                                    <a href="{{ asset($item->sampleProduct->file) }}" class="btn btn-outline-primary"
                                        target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"
                                        title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"><i
                                            class="m-0 bi bi-download"></i></a>
                                @endif
                            @else
                                @if ($item->type_sample == 'external')
                                    <a href="{{ asset($item->link_sample) }}" class="btn btn-outline-primary"
                                        target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"
                                        title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"><i
                                            class="m-0 bi bi-download"></i></a>
                                @endif
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ARTICLES -->
    <section class="section section-articles">
        <div class="container">
            <div class="articles-head d-flex justify-content-between align-items-center mb-5">
                <h2 class="mb-0">{{ languageText('Articles and Press Releases', 'Artikel dan Press Release') }}</h2>
            </div>
            <div class="row g-4">
                <!-- Kolom Kiri: Featured Articles Slider -->
                <div class="col-lg-8">
                    <div class="featured-articles-slider">
                        @foreach ($blog as $item)
                            <div class="featured-slide">
                                <article class="featured-article-card">
                                    <div class="featured-article-image">
                                        <img src="{{ asset($item->photo) }}"
                                            alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid"
                                            loading="lazy" />
                                        <div class="featured-article-overlay">
                                            <div class="featured-article-content">
                                                <span
                                                    class="featured-badge">{{ languageText($item->category?->title, $item->category?->judul) }}</span>
                                                <h3><a
                                                        href="{{ url('blog/' . $item->url) }}">{{ languageText($item->name, $item->nama) }}</a>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Kolom Kanan: Sidebar Articles -->
                <div class="col-lg-4">
                    <div class="articles-sidebar">
                        <div class="sidebar-articles-list">
                            @foreach ($blog->take(5) as $item)
                                <article class="sidebar-article-item">
                                    <div class="sidebar-article-thumb">
                                        <img src="{{ asset($item->photo) }}"
                                            alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid"
                                            loading="lazy" />
                                    </div>
                                    <div class="sidebar-article-content">
                                        <h4><a
                                                href="{{ url('blog/' . $item->url) }}">{{ languageText($item->name, $item->nama) }}</a>
                                        </h4>
                                        <span
                                            class="sidebar-article-time">{{ \Carbon\Carbon::parse($item->date)->diffForHumans() }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles-cta text-center mt-5">
                <a href="{{ url('blog') }}" class="btn btn-primary">
                    {{ languageText('View All Articles and Press Releases', 'Lihat Semua Artikel dan Press Release') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">{{ languageText($about->title, $about->judul) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="ratio-16x9" id="videoContainer">
                        {!! $about->link_youtube !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend.layout>
