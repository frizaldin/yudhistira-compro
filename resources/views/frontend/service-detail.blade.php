<x-frontend.layout>
    <!-- HERO SECTION -->
    <section class="hero-product">
        <div class="hero-product-bg" style="background-image: url('{{ asset($service->bg_landscape) }}')"></div>
        <div class="container">
            <div class="row align-items-center align-end-mobile g-4">
                <div class="col-lg-6">
                    <div class="hero-product-content">
                        <h1>{{ languageText($service->title, $service->judul) }}</h1>
                        {!! languageText($service->description, $service->deskripsi) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-product-visual">
            <img src="{{ asset($service->file) }}" alt="{{ languageText($service->title, $service->judul) }}"
                class="img-fluid" loading="eager" />
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="section section-features">
        <div class="container">
            <div class="section-head text-center mb-5">
                <h2>{{ languageText($service->title, $service->judul) }}</h2>
                {!! languageText($service->detail_information, $service->detail_informasi) !!}
            </div>
            <div class="text-start mb-5">
                <h3 class="features-subtitle text-center">
                    {{ languageText('Benefits', 'Keunggulan') }} {{ languageText($service->title, $service->judul) }}:
                </h3>
            </div>
            <div class="row g-4">
                @foreach ($benefits as $item)
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i><img src="{{ asset($item->file) }}"
                                        alt="{{ languageText($item->title, $item->judul) }}" class="img-fluid"
                                        loading="lazy"></i>
                            </div>
                            <h4>{{ languageText($item->title, $item->judul) }}</h4>
                            <p>{{ languageText($item->description, $item->deskripsi) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PRODUCT CATALOG SECTION -->
    @if ($category_product && $category_product->count() > 0)
        <section class="section section-product-catalog"
            style="background-image: url('{{ asset('frontend/assets/img/bg-product-buku.png') }}'); background-size:cover;background-repeat:no-repeat">
            <div class="container">
                <div class="section-head text-center mb-5">
                    <h2>{{ languageText('Product', 'Produk') }} {{ languageText($service->title, $service->judul) }}
                    </h2>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs product-tabs mb-4" role="tablist">
                    @foreach ($category_product as $item)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $item->id }}-tab"
                                data-bs-toggle="tab" data-bs-target="#cat-{{ $item->id }}" type="button"
                                role="tab">
                                {{ languageText($item->title, $item->judul) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="card">
                    <div class="card-body">
                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Buku Siswa Tab -->
                            @foreach ($category_product as $item)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="cat-{{ $item->id }}" role="tabpanel">
                                    <!-- Sub Tabs -->
                                    <div class="row g-4 justify-content-center d-none">
                                        @foreach ($item->subcategories->where('visible', 'yes') as $row)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="yupintar-feature-card">
                                                    <div class="feature-icon-wrapper">
                                                        <i><img src="{{ asset($row->photo) }}" alt=""
                                                                loading="lazy"></i>
                                                    </div>
                                                    <h4>{{ languageText($row->title, $row->judul) }}</h4>
                                                    <p>{!! languageText($row->description, $row->deskripsi) !!}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Desktop: Regular Tabs -->
                                    <ul class="nav nav-pills sub-tabs mb-4 " role="tablist">
                                        @foreach ($item->subcategories->where('visible', 'yes') as $subitem)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                    id="{{ $subitem->id }}-tab" data-bs-toggle="tab"
                                                    data-bs-target="#sub-{{ $subitem->id }}" type="button"
                                                    role="tab">
                                                    {{ languageText($subitem->title, $subitem->judul) }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <!-- Sub Tab Content -->
                                    <div class="tab-content">
                                        @foreach ($item->subcategories->where('visible', 'yes') as $subitem)
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                                id="sub-{{ $subitem->id }}" role="tabpanel">
                                                <div class="book-content-card p-0">
                                                    <div class="slider-subitems">
                                                        @foreach ($subitem->third_benefits->where('visible', 'yes') as $benefit)
                                                            <div class="slide-items p-3 ">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-lg-3">
                                                                                <div class="book-featured-image">
                                                                                    <img src="{{ asset($benefit->file) }}"
                                                                                        alt="{{ languageText($subitem->title, $subitem->judul) }}"
                                                                                        class="img-fluid" loading="lazy"
                                                                                        style="border-radius: 15px" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-9">
                                                                                <h3 class="mb-3">
                                                                                    {{ languageText($benefit->title, $benefit->judul) }}
                                                                                </h3>
                                                                                <div>
                                                                                    {!! languageText($benefit->description, $benefit->deskripsi) !!}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="book-catalog-section">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-4">
                                                            <h3 class="mb-0">
                                                                {{-- {{ languageText('Catalogue', 'Katalog') }} --}}
                                                                {{ languageText($service->title, $service->judul) }}
                                                            </h3>

                                                            <input type="text"
                                                                class="form-control product-search-input"
                                                                placeholder="{{ languageText('Search', 'Cari') }}..."
                                                                style="max-width: 300px;"
                                                                data-service-id="{{ $service->id }}"
                                                                data-category-id="{{ $item->id }}"
                                                                data-subcategory-id="{{ $subitem->id }}">
                                                        </div>
                                                        <div class="book-catalog row"
                                                            data-service-id="{{ $service->id }}"
                                                            data-category-id="{{ $item->id }}"
                                                            data-subcategory-id="{{ $subitem->id }}">
                                                            @php
                                                                // Filter produk berdasarkan service_id, category_id, dan subcategory_id
                                                                $filteredProducts = $products->filter(function (
                                                                    $product,
                                                                ) use ($service, $item, $subitem) {
                                                                    return $product->service_id == $service->id &&
                                                                        $product->category_id == $item->id &&
                                                                        $product->subcategory_id == $subitem->id;
                                                                });
                                                                $totalProducts = $filteredProducts->count();
                                                                $initialProducts = $filteredProducts->take(6);
                                                                $hasMore = $totalProducts > 6;
                                                            @endphp
                                                            @foreach ($initialProducts as $product)
                                                                @include('frontend.partials.product-card', [
                                                                    'product' => $product,
                                                                ])
                                                            @endforeach
                                                        </div>
                                                        @if ($hasMore)
                                                            <div
                                                                class="mt-3 load-more d-flex align-items-center justify-content-center">
                                                                <button class="btn btn-outline-primary load-more-btn"
                                                                    data-offset="6"
                                                                    data-service-id="{{ $service->id }}"
                                                                    data-category-id="{{ $item->id }}"
                                                                    data-subcategory-id="{{ $subitem->id }}">
                                                                    {{ languageText('Load More', 'Load More') }}
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <style>
        .yupintar-feature-card {
            background: #fff;
        }
    </style>
    <!-- ARTICLES SECTION -->
    <section class="section section-articles d-none">
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
                                            alt="{{ languageText($item->name, $item->nama) }}" class="img-fluid" />
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

    @push('js')
        <script>
            // Wait for jQuery to be available (since it's loaded with defer)
            (function() {
                function initServiceDetailScript() {
                    // Check if jQuery is available
                    if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
                        // jQuery not ready yet, wait a bit more (max 5 seconds)
                        if (typeof initServiceDetailScript.attempts === 'undefined') {
                            initServiceDetailScript.attempts = 0;
                        }
                        initServiceDetailScript.attempts++;
                        if (initServiceDetailScript.attempts < 100) { // 100 * 50ms = 5 seconds max
                            setTimeout(initServiceDetailScript, 50);
                        } else {
                            console.error('jQuery failed to load after 5 seconds');
                        }
                        return;
                    }

                    // jQuery is available, proceed with script
                    $(document).ready(function() {
                        console.log('Service detail page script loaded');

                        // Mobile sub-tabs: ensure clicks show Bootstrap tab and update active state
                        $(document).on('click', '.sub-tabs-slider .sub-tab-btn', function(e) {
                            // Prevent any default navigation or other handlers from running
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            e.stopPropagation();
                            var $btn = $(this);
                            // Use Bootstrap Tab API if available
                            try {
                                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                                    var tabInstance = bootstrap.Tab.getOrCreateInstance($btn[0]);
                                    tabInstance.show();
                                } else {
                                    // Fallback: directly show target pane and update classes
                                    var target = $btn.attr('data-bs-target') || $btn.data('bs-target');
                                    if (target) {
                                        // Hide active panes and show target
                                        $('.tab-pane.show.active').removeClass('show active');
                                        $(target).addClass('show active');
                                        // Update desktop nav active state as well
                                        $('[data-bs-target="' + target + '"]').removeClass('active');
                                        $('[data-bs-target="' + target + '"]').filter(function() {
                                            return $(this).is(':visible');
                                        }).first().addClass('active');
                                    }
                                }
                            } catch (err) {
                                console.warn('Error showing tab from mobile sub-tab:', err);
                            }

                            // Visual active state for mobile buttons
                            $btn.closest('.sub-tabs-slider').find('.sub-tab-btn').removeClass('active');
                            $btn.addClass('active');

                            return false;
                        });

                        // Flag to enable/disable tooltip initialization (set to false if tooltips cause errors)
                        const ENABLE_TOOLTIPS = true;

                        // Also listen for tab shown event to ensure search works when tab is activated
                        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                            console.log('Tab activated');
                            // Re-initialize search inputs in active tab
                            $('.tab-pane.active .product-search-input').each(function() {
                                console.log('Found search input in active tab');
                            });
                        });

                        // Test if search inputs exist
                        setTimeout(function() {
                            const searchInputs = $('.product-search-input');
                            console.log('Total search inputs found:', searchInputs.length);
                            if (searchInputs.length === 0) {
                                console.warn('No search inputs found!');
                            }
                        }, 5000);

                        // Function to remove lazy loading from AJAX-loaded images (load more & search)
                        function removeLazyLoading($container) {
                            // Find all lazy images in the container and make them eager
                            $container.find('img[loading="lazy"]').each(function() {
                                const $img = $(this);

                                // Remove lazy loading attributes and styles
                                $img.attr('loading', 'eager');
                                $img.css({
                                    'backgroundColor': 'transparent',
                                    'filter': 'opacity(1)',
                                    'opacity': '1',
                                    'visibility': 'visible'
                                });

                                // Remove lazy classes
                                $img.removeClass('lazy-loading lazy-initialized');
                                $img.addClass('lazy-show lazy-loaded');
                            });
                        }

                        // Debounce function - improved to preserve context
                        function debounce(func, wait) {
                            let timeout;
                            return function executedFunction() {
                                const context = this;
                                const args = arguments;
                                const later = function() {
                                    timeout = null;
                                    func.apply(context, args);
                                };
                                clearTimeout(timeout);
                                timeout = setTimeout(later, wait);
                            };
                        }

                        // Function to load products (for search and load more)
                        function loadProducts($container, serviceId, categoryId, subcategoryId, offset, limit,
                            search, append = false) {
                            return $.ajax({
                                url: '{{ route('service.loadMoreProducts') }}',
                                method: 'POST',
                                data: {
                                    service_id: serviceId,
                                    category_id: categoryId,
                                    subcategory_id: subcategoryId,
                                    offset: offset,
                                    limit: limit,
                                    search: search || '',
                                    _token: '{{ csrf_token() }}'
                                }
                            });
                        }

                        // Handle search input - using input event only (more reliable)
                        let searchTimeout = {};
                        $(document).on('input', '.product-search-input', function(e) {
                            const $input = $(this);
                            const inputId = $input.attr('id') || 'search-' + Math.random().toString(36)
                                .substr(2, 9);

                            // Clear previous timeout for this input
                            if (searchTimeout[inputId]) {
                                clearTimeout(searchTimeout[inputId]);
                            }

                            // Set new timeout
                            searchTimeout[inputId] = setTimeout(function() {
                                const $catalogSection = $input.closest('.book-catalog-section');
                                const $catalogContainer = $catalogSection.find('.book-catalog');
                                const $loadMoreContainer = $catalogSection.find('.load-more');
                                const searchTerm = $input.val().trim();
                                const serviceId = $input.data('service-id');
                                const categoryId = $input.data('category-id');
                                const subcategoryId = $input.data('subcategory-id');

                                // Validate required data
                                if (!serviceId || !categoryId || !subcategoryId) {
                                    console.error('Missing required data attributes:', {
                                        serviceId: serviceId,
                                        categoryId: categoryId,
                                        subcategoryId: subcategoryId
                                    });
                                    return;
                                }

                                // Show loading state
                                $catalogContainer.html(
                                    '<div class="col-12 text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                                );
                                $loadMoreContainer.hide();

                                // Load products with search
                                loadProducts($catalogContainer, serviceId, categoryId,
                                        subcategoryId, 0, 6,
                                        searchTerm,
                                        false)
                                    .done(function(response) {
                                        if (response && response.success) {
                                            // Clear and replace products
                                            if (response.html && response.html.trim() !== '') {
                                                $catalogContainer.html(response.html);
                                            } else {
                                                $catalogContainer.html(
                                                    '<div class="col-12 text-center py-5"><p>{{ languageText('No products found', 'Produk tidak ditemukan') }}</p></div>'
                                                );
                                            }

                                            // Show/hide load more button
                                            if (response.has_more && response.count > 0) {
                                                const $loadMoreBtn = $loadMoreContainer.find(
                                                    '.load-more-btn');
                                                if ($loadMoreBtn.length) {
                                                    $loadMoreBtn.data('offset', response
                                                        .next_offset);
                                                    $loadMoreBtn.data('search', searchTerm);
                                                    $loadMoreContainer.fadeIn();
                                                }
                                            } else {
                                                $loadMoreContainer.hide();
                                            }

                                            // Remove lazy loading from search results (images load immediately)
                                            removeLazyLoading($catalogContainer);

                                            // Re-initialize tooltips
                                            if (ENABLE_TOOLTIPS && $catalogContainer.length &&
                                                $catalogContainer[
                                                    0]) {
                                                try {
                                                    var tooltipTriggerList = [].slice.call(
                                                        $catalogContainer[0]
                                                        .querySelectorAll(
                                                            '[data-bs-toggle="tooltip"]'));
                                                    tooltipTriggerList.forEach(function(
                                                        tooltipTriggerEl) {
                                                        try {
                                                            // Destroy existing tooltip if any
                                                            const existingTooltip =
                                                                bootstrap
                                                                .Tooltip
                                                                .getInstance(
                                                                    tooltipTriggerEl);
                                                            if (existingTooltip) {
                                                                existingTooltip
                                                                    .dispose();
                                                            }
                                                            // Only create tooltip if element has valid title attribute
                                                            const title =
                                                                tooltipTriggerEl
                                                                .getAttribute(
                                                                    'title') ||
                                                                tooltipTriggerEl
                                                                .getAttribute(
                                                                    'data-bs-title') ||
                                                                tooltipTriggerEl
                                                                .getAttribute(
                                                                    'data-bs-original-title'
                                                                );
                                                            if (title &&
                                                                typeof title ===
                                                                'string' && title
                                                                .trim() !== '') {
                                                                new bootstrap.Tooltip(
                                                                    tooltipTriggerEl
                                                                );
                                                            }
                                                        } catch (tooltipError) {
                                                            console.warn(
                                                                'Error creating tooltip for element:',
                                                                tooltipTriggerEl,
                                                                tooltipError);
                                                        }
                                                    });
                                                } catch (error) {
                                                    console.warn('Error initializing tooltips:',
                                                        error);
                                                }
                                            }
                                        } else {
                                            console.error('Search response not successful:',
                                                response);
                                            $catalogContainer.html(
                                                '<div class="col-12 text-center py-5"><p class="text-danger">{{ languageText('Error loading products. Please try again.', 'Gagal memuat produk. Silakan coba lagi.') }}</p></div>'
                                            );
                                        }
                                    })
                                    .fail(function(xhr, status, error) {
                                        console.error('Error searching products:', {
                                            xhr: xhr,
                                            status: status,
                                            error: error,
                                            responseText: xhr.responseText
                                        });
                                        $catalogContainer.html(
                                            '<div class="col-12 text-center py-5"><p class="text-danger">{{ languageText('Error loading products. Please try again.', 'Gagal memuat produk. Silakan coba lagi.') }}</p></div>'
                                        );
                                    });
                            }, 500); // End of setTimeout - debounce delay
                        });

                        // Handle load more button click
                        $(document).on('click', '.load-more-btn', function() {
                            const $button = $(this);
                            const $catalogSection = $button.closest('.book-catalog-section');
                            const $catalogContainer = $catalogSection.find('.book-catalog');
                            const $searchInput = $catalogSection.find('.product-search-input');
                            const serviceId = $button.data('service-id');
                            const categoryId = $button.data('category-id');
                            const subcategoryId = $button.data('subcategory-id');
                            const offset = $button.data('offset');
                            const search = $button.data('search') || $searchInput.val().trim() || '';
                            const limit = 6;

                            // Disable button and show loading state
                            $button.prop('disabled', true);
                            const originalText = $button.html();
                            $button.html(
                                '<i class="spinner-border spinner-border-sm me-2"></i>{{ languageText('Loading...', 'Memuat...') }}'
                            );

                            // Make AJAX request
                            loadProducts($catalogContainer, serviceId, categoryId, subcategoryId, offset,
                                    limit, search,
                                    true)
                                .done(function(response) {
                                    if (response && response.success) {
                                        // Append new products to container if there are any
                                        if (response.html && response.html.trim() !== '') {
                                            $catalogContainer.append(response.html);
                                        }

                                        // Update offset and search
                                        $button.data('offset', response.next_offset);
                                        $button.data('search', search);

                                        // Hide button if no more products or no products returned
                                        if (!response.has_more || response.count === 0) {
                                            // Reset button state first, then hide
                                            $button.prop('disabled', false);
                                            $button.html(originalText);
                                            $button.closest('.load-more').fadeOut(300);
                                        } else {
                                            // Re-enable button if there are more products
                                            $button.prop('disabled', false);
                                            $button.html(originalText);
                                        }

                                        // Remove lazy loading from load more results (images load immediately)
                                        removeLazyLoading($catalogContainer);

                                        // Re-initialize tooltips for new products
                                        if (ENABLE_TOOLTIPS && response.html && response.html.trim() !==
                                            '' &&
                                            $catalogContainer
                                            .length && $catalogContainer[0]) {
                                            try {
                                                var tooltipTriggerList = [].slice.call(
                                                    $catalogContainer[0]
                                                    .querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                                                    try {
                                                        // Destroy existing tooltip if any
                                                        const existingTooltip = bootstrap
                                                            .Tooltip
                                                            .getInstance(
                                                                tooltipTriggerEl);
                                                        if (existingTooltip) {
                                                            existingTooltip.dispose();
                                                        }
                                                        // Only create tooltip if element has valid title attribute
                                                        const title = tooltipTriggerEl
                                                            .getAttribute(
                                                                'title') ||
                                                            tooltipTriggerEl.getAttribute(
                                                                'data-bs-title') ||
                                                            tooltipTriggerEl.getAttribute(
                                                                'data-bs-original-title');
                                                        if (title && typeof title ===
                                                            'string' && title
                                                            .trim() !== '') {
                                                            new bootstrap.Tooltip(
                                                                tooltipTriggerEl);
                                                        }
                                                    } catch (tooltipError) {
                                                        console.warn(
                                                            'Error creating tooltip for element:',
                                                            tooltipTriggerEl, tooltipError);
                                                    }
                                                });
                                            } catch (error) {
                                                console.warn('Error initializing tooltips:', error);
                                            }
                                        }
                                    } else {
                                        // If response is not successful, reset button
                                        $button.prop('disabled', false);
                                        $button.html(originalText);
                                    }
                                })
                                .fail(function(xhr) {
                                    console.error('Error loading more products:', xhr);
                                    $button.prop('disabled', false);
                                    $button.html(originalText);
                                    alert(
                                        '{{ languageText('Failed to load more products. Please try again.', 'Gagal memuat produk lebih banyak. Silakan coba lagi.') }}'
                                    );
                                });
                        });
                    });
                }

                // Start initialization
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initServiceDetailScript);
                } else {
                    // DOM already loaded
                    initServiceDetailScript();
                }
            })();
        </script>
    @endpush

</x-frontend.layout>
