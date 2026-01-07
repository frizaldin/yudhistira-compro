<x-frontend.layout>
    <!-- USER PROFILE SECTION -->
    <section class="section section-profile-dashboard">
        <div class="container">
            <div class="profile-card">
                <div class="profile-header-actions">
                    <button class="btn btn-edit">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <form action="{{ route('teacher.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Log Out
                        </button>
                    </form>
                </div>
                <div class="profile-content">
                    <div class="profile-avatar">
                        <img src="{{ $teacher->photo ? asset($teacher->photo) : asset('frontend/assets/img/user-default.png') }}"
                            alt="Profile Picture" class="avatar-img" />
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $teacher->name }}</h2>
                        <div class="profile-details">
                            <div class="profile-detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">{{ $teacher->email }}</span>
                            </div>
                            @if ($teacher->domisili)
                                <div class="profile-detail-item">
                                    <span class="detail-label">Domisili:</span>
                                    <span class="detail-value">{{ ucfirst($teacher->domisili) }}</span>
                                </div>
                            @endif
                            @if ($teacher->phone)
                                <div class="profile-detail-item">
                                    <span class="detail-label">No. Telpon:</span>
                                    <span class="detail-value">{{ $teacher->phone }}</span>
                                </div>
                            @endif
                            @if ($teacher->gender)
                                <div class="profile-detail-item">
                                    <span class="detail-label">Jenis Kelamin:</span>
                                    <span class="detail-value">{{ ucfirst($teacher->gender) }}</span>
                                </div>
                            @endif
                            @if ($teacher->school_name)
                                <div class="profile-detail-item">
                                    <span class="detail-label">Nama Sekolah:</span>
                                    <span class="detail-value">{{ $teacher->school_name }}</span>
                                </div>
                            @endif
                            @if ($teacher->birth_date)
                                <div class="profile-detail-item">
                                    <span class="detail-label">Tanggal Lahir:</span>
                                    <span
                                        class="detail-value">{{ \Carbon\Carbon::parse($teacher->birth_date)->format('d F Y') }}</span>
                                </div>
                            @endif
                            @if ($teacher->teaching_field)
                                <div class="profile-detail-item">
                                    <span class="detail-label">Bidang Mengajar:</span>
                                    <span
                                        class="detail-value">{{ ucfirst(str_replace('-', ' ', $teacher->teaching_field)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFORMASI KHUSUS GURU SECTION -->
    <section class="section section-dashboard-info">
        <div class="container">
            <h2 class="dashboard-section-title">Informasi Khusus Guru</h2>
            <div class="dashboard-articles-slider">
                @forelse($blogs as $blog)
                    <div class="slide">
                        <article class="article-card">
                            <div class="article-thumb">
                                <img src="{{ $blog->photo ? asset($blog->photo) : asset('frontend/assets/img/Rectangle 1.png') }}"
                                    alt="{{ $blog->name }}" class="img-fluid" />
                            </div>
                            <div class="article-meta">
                                @if ($blog->category)
                                    <span class="badge">{{ $blog->category->name }}</span>
                                @endif
                                <span class="date">{{ \Carbon\Carbon::parse($blog->date)->format('d M Y') }}</span>
                            </div>
                            <h3>{{ $blog->name }}</h3>
                            <p>{{ Str::limit($blog->overview ?? $blog->description, 100) }}</p>
                            <a href="{{ url('blog/' . $blog->url) }}" class="btn-link">Baca Selengkapnya <i
                                    class="bi bi-arrow-right"></i></a>
                        </article>
                    </div>
                @empty
                    <p>Tidak ada artikel tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- INFORMASI PELATIHAN SECTION -->
    <section class="section section-dashboard-info">
        <div class="container">
            <h2 class="dashboard-section-title">Informasi Pelatihan</h2>
            <div class="dashboard-articles-slider">
                @forelse($events as $event)
                    <div class="slide">
                        <article class="article-card">
                            <div class="article-thumb">
                                <img src="{{ $event->photo ? asset($event->photo) : asset('frontend/assets/img/Rectangle 1.png') }}"
                                    alt="{{ $event->name }}" class="img-fluid" />
                            </div>
                            <div class="article-meta">
                                @if ($event->category)
                                    <span class="badge">{{ $event->category->name }}</span>
                                @endif
                                <span class="date">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                            </div>
                            <h3>{{ $event->name }}</h3>
                            <p>{{ Str::limit($event->overview ?? $event->description, 100) }}</p>
                            <a href="{{ url('event/' . $event->url) }}" class="btn-link">Baca Selengkapnya <i
                                    class="bi bi-arrow-right"></i></a>
                        </article>
                    </div>
                @empty
                    <p>Tidak ada event tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- E-BOOK YUDHISTIRA SECTION -->
    <section class="section section-dashboard-ebook">
        <div class="container">
            <h2 class="dashboard-section-title">E-Book Yudhistira</h2>
            <div class="dashboard-books-slider">
                @forelse($products as $product)
                    <article class="book-card">
                        <div class="book-image d-flex justify-content-center align-items-center">
                            <img src="{{ $product->photo ? asset($product->photo) : asset('frontend/assets/img/Coursebook Social Insight 3.png') }}"
                                alt="{{ $product->name }}" class="img-fluid" />
                        </div>
                        <h4>{{ $product->name }}</h4>
                        <a href="{{ url('product/' . $product->url) }}" class="btn btn-outline-primary">Belanja
                            Sekarang</a>
                    </article>
                @empty
                    <p>Tidak ada produk tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>
    <x-slot name="js">
        <script>
            $(document).ready(function() {
                // Initialize sliders if they exist
                if ($('.dashboard-articles-slider').length > 0) {
                    $('.dashboard-articles-slider').slick({
                        dots: false,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        arrows: true,
                        prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-arrow-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="bi bi-arrow-right"></i></button>',
                        responsive: [{
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                },
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: false,
                                },
                            },
                        ],
                    });
                }

                if ($('.dashboard-books-slider').length > 0) {
                    $('.dashboard-books-slider').slick({
                        dots: false,
                        infinite: true,
                        speed: 300,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: false,
                        arrows: true,
                        prevArrow: '<button type="button" class="slick-prev"><i class="bi bi-arrow-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="bi bi-arrow-right"></i></button>',
                        responsive: [{
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 1,
                                },
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                },
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: false,
                                },
                            },
                        ],
                    });
                }
            });
        </script>
    </x-slot>
</x-frontend.layout>
