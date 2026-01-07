<x-frontend.layout>
    <!-- YUPINTAR HERO SECTION -->
    <section class="section-yupintar-hero"
        style="background-image: url('{{ asset('frontend/assets/img/bg-product-buku.png') }}');">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="yupintar-hero-content">
                        <div class="yupintar-logo-small">
                            <img src="{{ asset($digital_product->logo) }}" alt="YuPintar" loading="eager" />
                        </div>
                        <h1 class="yupintar-title">{{ languageText($digital_product->title, $digital_product->judul) }}
                        </h1>
                        <div class="yupintar-description">
                            {!! languageText($digital_product->description, $digital_product->deskripsi) !!}
                        </div>
                        <div class="yupintar-cta-buttons">
                            <a href="{{ $digital_product->button_link_1 }}"
                                class="btn btn-yupintar-primary">{{ languageText($digital_product->button_text_1, $digital_product->tombol_teks_1) }}</a>
                            <a href="{{ $digital_product->button_link_2 }}"
                                class="btn btn-yupintar-outline">{{ languageText($digital_product->button_text_2, $digital_product->button_teks_2) }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="yupintar-device-mockups">
                        <div class="device-laptop">
                            <img src="{{ asset($digital_product->photo) }}" alt="Laptop Mockup" class="img-fluid" loading="eager" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="container d-flex justify-content-center my-5 pb-4">
                <div class="ratio ratio-4x3" style="width: 80%; max-height: 600px;">
                    {!! $digital_product->iframe_youtube !!}
                </div>
            </div>

            <!-- FEATURES SECTION -->
            <section class="section-yupintar-features">
                <div class="container">
                    <div class="section-head text-center mb-5">
                        <h2>{{languageText('Main Function of ','Fungsi Utama')}} {{ languageText($digital_product->title, $digital_product->judul) }}</h2>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach ($second_benefit as $item)
                            <div class="col-md-6 col-lg-3">
                                <div class="yupintar-feature-card">
                                    <div class="feature-icon-wrapper">
                                        <i><img src="{{ asset($item->file) }}" alt="" loading="lazy"></i>
                                    </div>
                                    <h4>{{ languageText($item->title, $item->judul) }}</h4>
                                    <p>{!! languageText($item->description, $item->deskripsi) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </section>

    <!-- ARTICLES SECTION -->
    <section class="section section-articles d-none">
        <div class="container">
            <div class="articles-head d-flex justify-content-between align-items-center mb-5">
                <h2 class="mb-0">{{ languageText('Artikel dan Press Release', 'Artikel dan Press Release') }}</h2>
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
                                            alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid" loading="lazy" />
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
                                            alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid" loading="lazy" />
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
                    {{ languageText('Lihat Semua Artikel dan Press Release', 'Lihat Semua Artikel dan Press Release') }}
                </a>
            </div>
        </div>
    </section>
</x-frontend.layout>
