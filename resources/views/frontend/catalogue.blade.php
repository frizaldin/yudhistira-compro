<x-frontend.layout>
    <!-- CATALOGUE BANNER -->
    <section class="section section-catalogue-banner"
        style="background: url({{ asset('frontend/assets/img/bg-product-buku.png') }})">
        <div class="container">
            <div class="catalogue-header-content">
                <h1 class="catalogue-page-title">{{ languageText('Catalogue', 'Katalog') }}</h1>
                <form action="{{ url('catalogue') }}" method="GET" class="catalogue-search-wrapper">
                    <input type="text" name="search" class="catalogue-search-input"
                        placeholder="{{ languageText('Search', 'Cari') }}..." value="{{ request('search') }}" />
                    @if (isset($categories) && $categories->count() > 0)
                        <select name="category" class="catalogue-category-filter d-none">
                            <option value="">{{ languageText('All Categories', 'Semua Kategori') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                    {{ languageText($category->title, $category->judul) }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <button type="submit" class="catalogue-search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- CATALOGUES SECTION -->
    @if (isset($cataloguesByCategory) && count($cataloguesByCategory) > 0)
        <section class="section section-catalogue-list">
            <div class="container">
                @foreach ($cataloguesByCategory as $categoryData)
                    <div class="catalogue-category-section mb-5">
                        @if (!$categoryData['category'])
                            <div class="catalogue-category-header mb-4">
                                <h2 class="catalogue-category-title">
                                    @if ($categoryData['category'])
                                        {{ languageText($categoryData['category']->title, $categoryData['category']->judul) }}
                                    @else
                                        {{ languageText('Other Catalogues', 'Katalog Lainnya') }}
                                    @endif
                                </h2>
                            </div>
                        @endif
                        <div class="row">
                            @if ($categoryData['category'])
                                <div class="col-md-4 mb-4">
                                    <article class="catalogue-card shadow">
                                        <div class="catalogue-card-image">
                                            <img src="{{ asset($categoryData['category']->file) }}"
                                                alt="{{ languageText($categoryData['category']->title, $categoryData['category']->judul) }}"
                                                class="img-fluid" loading="lazy" />
                                        </div>
                                        <div class="catalogue-card-body">
                                            <h4 class="catalogue-card-title-category m-0 text-center">
                                                {{ languageText($categoryData['category']->title, $categoryData['category']->judul) }}
                                            </h4>
                                        </div>
                                    </article>
                                </div>
                                <div class="col-md-8 px-5 mb-4">
                                @else
                                    <div class="col-md-12">
                            @endif
                            <div
                                class="catalogue-slider catalogue-slider-{{ $categoryData['category'] ? $categoryData['category']->id : 'uncategorized' }}">
                                @foreach ($categoryData['catalogues'] as $catalogue)
                                    <div class="catalogue-slide">
                                        <article class="catalogue-card">
                                            <div class="catalogue-card-image">
                                                @if ($catalogue->thumbnail)
                                                    <img src="{{ asset($catalogue->thumbnail) }}"
                                                        alt="{{ languageText($catalogue->title, $catalogue->judul) }}"
                                                        class="img-fluid" loading="lazy" />
                                                @else
                                                    <img src="{{ asset('frontend/assets/img/default-product.png') }}"
                                                        alt="{{ languageText($catalogue->title, $catalogue->judul) }}"
                                                        class="img-fluid" loading="lazy" />
                                                @endif
                                            </div>
                                            <div class="catalogue-card-body">
                                                <h4 class="catalogue-card-title">
                                                    {{ languageText($catalogue->title, $catalogue->judul) }}
                                                </h4>
                                                @if ($catalogue->pdf)
                                                    <a href="{{ asset($catalogue->pdf) }}" target="_blank"
                                                        class="btn btn-primary btn-sm w-100">
                                                        <i class="bi bi-file-pdf"></i>
                                                        {{ languageText('View PDF', 'Lihat PDF') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            </div>
    @endforeach
    </div>
    </section>
@else
    <section class="section section-catalogue-list">
        <div class="container">
            <div class="text-center py-5">
                <p class="text-muted">{{ languageText('No catalogues found', 'Tidak ada katalog ditemukan') }}</p>
            </div>
        </div>
    </section>
    @endif
</x-frontend.layout>
