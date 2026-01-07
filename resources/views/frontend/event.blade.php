<x-frontend.layout>
    <!-- PAGE TITLE & SEARCH -->
    <section class="section section-blog-header"
        style="background-image: url('{{ asset('frontend/assets/img/bg-product-buku.png') }}');">
        <div class="container">
            <div class="blog-header-content">
                <h1 class="blog-page-title">Event Yudhistira</h1>
                <form action="{{ url('event') }}" method="GET" class="blog-search-wrapper">
                    <input type="text" name="search" class="blog-search-input" placeholder="Cari Sesuatu...."
                        value="{{ request('search') }}" />
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}" />
                    @endif
                    <button type="submit" class="blog-search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- REKOMENDASI SECTION -->
    @if (isset($events) && $events->count() > 0)
        <section class="section section-rekomendasi">
            <div class="container">
                <h2 class="section-title-left">Rekomendasi</h2>
                <div class="featured-article-card">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="featured-article-image">
                                <img src="{{ asset($events->first()->photo) }}"
                                    alt="{{ languageText($events->first()->name, $events->first()->nama) }}"
                                    class="img-fluid" loading="eager" />
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="featured-article-content">
                                <div class="article-meta">
                                    @if ($events->first()->category)
                                        <span
                                            class="badge">{{ languageText($events->first()->category->title, $events->first()->category->judul) }}</span>
                                    @endif
                                    <span
                                        class="date">{{ date('d M Y', strtotime($events->first()->date ?? $events->first()->created_at)) }}</span>
                                </div>
                                <h3 class="featured-article-title">
                                    {{ languageText($events->first()->name, $events->first()->nama) }}
                                </h3>
                                <p class="featured-article-description">
                                    {!! Str::limit(
                                        languageText(
                                            $events->first()->overview ?? $events->first()->description,
                                            $events->first()->pratinjau ?? $events->first()->deskripsi,
                                        ),
                                        200,
                                    ) !!}
                                </p>
                                <a href="{{ url('event/' . $events->first()->url) }}"
                                    class="btn-link">{{ languageText('Read More', 'Baca Selengkapnya') }}
                                    <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- EVENT SECTION -->
    <section class="section section-berita-event">
        <div class="container">
            <h2 class="section-title-left">Event</h2>
            <div class="blog-filter-buttons">
                <a href="{{ url('event') }}" class="filter-btn {{ !request('category') ? 'active' : '' }}"
                    data-filter="all">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ url('event?category=' . $category->id) }}"
                        class="filter-btn {{ request('category') == $category->id || request('category') == (string) $category->id ? 'active' : '' }}"
                        data-filter="{{ Str::slug($category->title) }}">
                        {{ languageText($category->title, $category->judul) }}
                    </a>
                @endforeach
            </div>
            <div class="blog-articles-grid">
                @forelse($events as $event)
                    <article class="blog-article-card"
                        data-category="{{ $event->category ? Str::slug($event->category->title) : '' }}">
                        <div class="article-thumb">
                            <img src="{{ asset($event->photo) }}" alt="{{ languageText($event->name, $event->nama) }}"
                                class="img-fluid" loading="lazy" />
                        </div>
                        <div class="article-meta">
                            @if ($event->category)
                                <span
                                    class="badge">{{ languageText($event->category->title, $event->category->judul) }}</span>
                            @endif
                            <span
                                class="date">{{ date('d M Y', strtotime($event->date ?? $event->created_at)) }}</span>
                        </div>
                        <h3>{{ languageText($event->name, $event->nama) }}</h3>
                        <p>
                            {!! Str::limit(
                                languageText($event->overview ?? $event->description, $event->pratinjau ?? $event->deskripsi),
                                150,
                            ) !!}
                        </p>
                        <a href="{{ url('event/' . $event->url) }}"
                            class="btn-link">{{ languageText('Read More', 'Baca Selengkapnya') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </article>
                @empty
                    <div class="col-12">
                        <p class="text-center">
                            {{ languageText('No events found', 'Tidak ada event yang ditemukan') }}.</p>
                    </div>
                @endforelse
            </div>
            <!-- PAGINATION -->
            @if (isset($events) && $events->hasPages())
                <div class="blog-pagination">
                    @if ($events->onFirstPage())
                        <button class="pagination-btn" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $events->previousPageUrl() }}" class="pagination-btn">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    @php
                        $currentPage = $events->currentPage();
                        $lastPage = $events->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    @if ($startPage > 1)
                        <a href="{{ $events->url(1) }}" class="pagination-number">1</a>
                        @if ($startPage > 2)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                    @endif

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <button class="pagination-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $events->url($page) }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                        <a href="{{ $events->url($lastPage) }}" class="pagination-number">{{ $lastPage }}</a>
                    @endif

                    @if ($events->hasMorePages())
                        <a href="{{ $events->nextPageUrl() }}" class="pagination-btn">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <button class="pagination-btn" disabled>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-frontend.layout>
