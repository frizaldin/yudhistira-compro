<x-frontend.layout>
    <!-- PAGE TITLE & SEARCH -->
    <section class="section section-blog-header"
        style="background-image: url('{{ asset('frontend/assets/img/bg-product-buku.png') }}');">
        <div class="container">
            <div class="blog-header-content">
                <h1 class="blog-page-title">{{ languageText('News and Article', 'Artikel dan Berita') }}</h1>
                <form action="{{ url('blog') }}" method="GET" class="blog-search-wrapper">
                    <input type="text" name="search" class="blog-search-input"
                        placeholder="{{ languageText('Search for something...', 'Cari Sesuatu...') }}"
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
    @if (isset($blogs) && $blogs->count() > 0)
        <section class="section section-rekomendasi">
            <div class="container">
                <h2 class="section-title-left">{{ languageText('Recommended', 'Rekomendasi') }}</h2>
                <div class="featured-article-card">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="featured-article-image">
                                <img src="{{ asset($blogs->first()->photo) }}"
                                    alt="{{ languageText($blogs->first()->name, $blogs->first()->nama) }}"
                                    class="img-fluid" loading="eager" />
                                {{-- <div class="featured-article-links">
                                    <a href="#" class="featured-link-item">bukuyudhistira</a>
                                    <a href="#" class="featured-link-item">www.yudhistira.co.id</a>
                                    <a href="#" class="featured-link-item">www.yudigital.id</a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="featured-article-content">
                                <div class="article-meta">
                                    @if ($blogs->first()->category)
                                        <span
                                            class="badge">{{ languageText($blogs->first()->category?->title, $blogs->first()->category?->judul) }}</span>
                                    @endif
                                    <span
                                        class="date">{{ date('d M Y', strtotime($blogs->first()->date ?? $blogs->first()->created_at)) }}</span>
                                </div>
                                <h3 class="featured-article-title">
                                    {{ languageText($blogs->first()->name, $blogs->first()->nama) }}
                                </h3>
                                <p class="featured-article-description">
                                    {!! Str::limit(
                                        languageText(
                                            $blogs->first()->overview ?? $blogs->first()->description,
                                            $blogs->first()->pratinjau ?? $blogs->first()->deskripsi,
                                        ),
                                        200,
                                    ) !!}
                                </p>
                                <a href="{{ url('blog/' . $blogs->first()->url) }}"
                                    class="btn-link">{{ languageText('Read More', 'Baca Selengkapnya') }}
                                    <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- BERITA DAN EVENT SECTION -->
    <section class="section section-berita-event">
        <div class="container">
            <h2 class="section-title-left">{{ languageText('News and Article', 'Artikel dan Berita') }}</h2>
            <div class="blog-filter-buttons">
                <a href="{{ url('blog') }}" class="filter-btn {{ !request('category') ? 'active' : '' }}"
                    data-filter="all">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ url('blog?category=' . $category->id) }}"
                        class="filter-btn {{ request('category') == $category->id || request('category') == (string) $category->id ? 'active' : '' }}"
                        data-filter="{{ Str::slug($category->title) }}">
                        {{ languageText($category->title, $category->judul) }}
                    </a>
                @endforeach
            </div>
            <div class="blog-articles-grid">
                @forelse($blogs as $blog)
                    <article class="blog-article-card"
                        data-category="{{ $blog->category ? Str::slug($blog->category?->title) : '' }}">
                        <div class="article-thumb">
                            <img src="{{ asset($blog->photo) }}" alt="{{ languageText($blog->name, $blog->nama) }}"
                                class="img-fluid" loading="lazy" />
                        </div>
                        <div class="article-meta">
                            @if ($blog->category)
                                <span
                                    class="badge">{{ languageText($blog->category?->title, $blog->category?->judul) }}</span>
                            @endif
                            <span
                                class="date">{{ date('d M Y', strtotime($blog->date ?? $blog->created_at)) }}</span>
                        </div>
                        <h3>{{ languageText($blog->name, $blog->nama) }}</h3>
                        <p>
                            {!! Str::limit(languageText($blog->overview ?? $blog->description, $blog->pratinjau ?? $blog->deskripsi), 150) !!}
                        </p>
                        <a href="{{ url('blog/' . $blog->url) }}"
                            class="btn-link">{{ languageText('Read More', 'Baca Selengkapnya') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </article>
                @empty
                    <div class="col-12">
                        <p class="text-center">
                            {{ languageText('No articles found', 'Tidak ada artikel yang ditemukan') }}.</p>
                    </div>
                @endforelse
            </div>
            <!-- PAGINATION -->
            @if (isset($blogs) && $blogs->hasPages())
                <div class="blog-pagination">
                    @if ($blogs->onFirstPage())
                        <button class="pagination-btn" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $blogs->previousPageUrl() }}" class="pagination-btn">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    @php
                        $currentPage = $blogs->currentPage();
                        $lastPage = $blogs->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    @if ($startPage > 1)
                        <a href="{{ $blogs->url(1) }}" class="pagination-number">1</a>
                        @if ($startPage > 2)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                    @endif

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <button class="pagination-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $blogs->url($page) }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                        <a href="{{ $blogs->url($lastPage) }}" class="pagination-number">{{ $lastPage }}</a>
                    @endif

                    @if ($blogs->hasMorePages())
                        <a href="{{ $blogs->nextPageUrl() }}" class="pagination-btn">
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
