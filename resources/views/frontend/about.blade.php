<x-frontend.layout>
    <!-- HERO SECTION -->
    <section class="bg-about" style="background-image: url('{{ asset('frontend/assets/img/bg-about.png') }}')">
        <section class="hero-about">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="hero-about-content">
                            <h1>
                                {{ languageText($about->title, $about->judul) }}
                            </h1>
                            <div class="hero-about-description">
                                {!! languageText($about->description, $about->deskripsi) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-about-video">
                            <div class="video-thumbnail">
                                {{-- <div class="ratio ratio-4x3" style="width: 100%; max-height: 600px;">
                                    {!! $about->link_youtube !!}
                                </div> --}}
                                <img src="{{ asset($about->file) }}" alt="Video Thumbnail" class="img-fluid" loading="eager" />
                                <a href="#" class="play-button" data-bs-toggle="modal"
                                    data-bs-target="#videoModal">
                                    <i class="bi bi-play-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATISTICS SECTION -->
        <section class="section section-stats-about">
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

        <!-- TIMELINE SECTION -->
        <section class="section section-timeline-journey">
            <div class="container">
                <div class="timeline-journey-header">
                    <span class="timeline-label">{{ languageText('OUR TIMELINE', 'TIMELINE KAMI') }}</span>
                    <h2 class="timeline-journey-title">{{ languageText('Our Journey Map', 'Peta Perjalanan Kami') }}
                    </h2>
                </div>

                <div class="timeline-journey-wrapper">
                    <button class="timeline-nav-btn timeline-nav-prev" id="timelinePrev">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <div class="timeline-journey-container">
                        <div class="timeline-years-row">
                            @foreach ($timeline as $index => $item)
                                <div class="timeline-year-item" data-index="{{ $index }}">
                                    <button class="timeline-year-badge {{ $index === 0 ? 'active' : '' }}"
                                        data-year="{{ $item->year }}">
                                        {{ $item->year }}
                                    </button>
                                    @if (!$loop->last)
                                        <div class="timeline-connector-line"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="timeline-horizontal-line"></div>

                        <div class="timeline-cards-container">
                            @foreach ($timeline as $index => $item)
                                <div class="timeline-card {{ $index === 0 ? 'active' : '' }}"
                                    data-year="{{ $item->year }}" data-index="{{ $index }}">
                                    <div class="timeline-card-connector"></div>
                                    <div class="timeline-card-content">
                                        <div class="timeline-card-image">
                                            <img src="{{ asset($item->photo) }}"
                                                alt="{{ languageText('Timeline', 'Timeline') }} {{ $item->year }}"
                                                class="img-fluid" loading="lazy" />
                                        </div>
                                        <h3 class="timeline-card-title">
                                            {{ $item->year }}
                                        </h3>
                                        <div class="timeline-card-description">
                                            {!! languageText($item->description, $item->deskripsi) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="timeline-nav-btn timeline-nav-next" id="timelineNext">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>
    </section>


    <!-- BRANCHES SECTION -->
    <section class="section section-branches">
        <div class="container">
            <div class="section-head text-center mb-5">
                <h2>{{ languageText('Branch', 'Cabang') }} Yudhistira</h2>
            </div>
            <div class="branches-map-container">
                <div class="branches-map" id="indonesiaMap"></div>
                <div class="branch-office-card" id="branchCard">
                    <div class="branch-office-image">
                        <img src="{{ asset('frontend/assets/img/Rectangle 1.png') }}" alt="Kantor Pusat"
                            class="img-fluid" id="branchImage" loading="lazy" />
                    </div>
                    <div class="branch-office-info">
                        <h4 id="branchTitle">
                            PT Yudhistira Ghalia Indonesia (Kantor Pusat)
                        </h4>
                        <p id="branchAddress">
                            Jl. Rancamaya No.Km. 1 No. 47, RT.01/RW.02, Bojongkerto, Kec.
                            Bogor Sel, Kota Bogor, Jawa Barat 16139
                        </p>
                    </div>
                </div>
            </div>
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
    <x-slot name="css">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    </x-slot>
    <x-slot name="js">
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Initialize Leaflet map focused on Indonesia
                var map = L.map("indonesiaMap", {
                    center: [-2.5, 118.0], // Center of Indonesia
                    zoom: 5,
                    minZoom: 4,
                    maxZoom: 19,
                    maxBounds: [
                        [-11.0, 95.0], // Southwest corner
                        [6.0, 141.0], // Northeast corner
                    ],
                });

                // Add tile layer (using OpenStreetMap)
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19, // Increased to match map maxZoom for detailed street view
                }).addTo(map);

                // Branch offices data from database
                var branches = @json($branches);

                // Custom red icon for markers
                var redIcon = L.icon({
                    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png",
                    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png",
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41],
                });

                // Function to update branch card
                function updateBranchCard(branch) {
                    document.getElementById("branchTitle").textContent = branch.name;
                    document.getElementById("branchAddress").textContent = branch.address;
                    document.getElementById("branchImage").src = branch.image;
                    document.getElementById("branchImage").alt = branch.name;
                }

                // Add markers for each branch
                branches.forEach(function(branch) {
                    var marker = L.marker([branch.lat, branch.lng], {
                        icon: redIcon,
                    }).addTo(map);

                    // Add popup
                    marker.bindPopup(
                        '<div style="text-align: center;"><strong>' +
                        branch.name +
                        "</strong><br>" +
                        branch.address +
                        "</div>"
                    );

                    // Add click event to update card
                    marker.on("click", function() {
                        updateBranchCard(branch);
                        // Smooth scroll to card if needed
                        document
                            .getElementById("branchCard")
                            .scrollIntoView({
                                behavior: "smooth",
                                block: "nearest"
                            });
                    });
                });

                // Set default card to first branch (Kantor Pusat)
                if (branches.length > 0) {
                    updateBranchCard(branches[0]);
                }
            });
        </script>
    </x-slot>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ languageText($about->title, $about->judul) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
