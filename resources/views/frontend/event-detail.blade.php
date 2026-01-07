<x-frontend.layout>
    <section class="section section-blog-detail">
        <div class="container">
            <div class="row g-5">
                <!-- LEFT COLUMN: ARTICLE CONTENT -->
                <div class="col-lg-8">
                    <article class="blog-detail-article">
                        <h1 class="blog-detail-title">
                            {{ languageText($event->name, $event->nama) }}
                        </h1>

                        <div class="blog-detail-meta">
                            @if ($event->category)
                                <span
                                    class="badge badge-dark">{{ languageText($event->category->title, $event->category->judul) }}</span>
                            @endif
                            <span class="blog-detail-date">
                                @php
                                    $dateToFormat = $event->date ? $event->date : $event->created_at;
                                    $carbonDate = \Carbon\Carbon::parse($dateToFormat);
                                    $carbonDate->locale('id');
                                    $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    $monthNames = [
                                        'Januari',
                                        'Februari',
                                        'Maret',
                                        'April',
                                        'Mei',
                                        'Juni',
                                        'Juli',
                                        'Agustus',
                                        'September',
                                        'Oktober',
                                        'November',
                                        'Desember',
                                    ];
                                    echo $dayNames[$carbonDate->dayOfWeek] .
                                        ', ' .
                                        $carbonDate->day .
                                        ' ' .
                                        $monthNames[$carbonDate->month - 1] .
                                        ' ' .
                                        $carbonDate->year;
                                @endphp
                            </span>
                            @if ($event->user)
                                <div class="blog-detail-author">
                                    @if ($event->user->photo)
                                        <img src="{{ asset($event->user->photo) }}" alt="{{ $event->user->name }}"
                                            class="author-avatar" loading="lazy" />
                                    @else
                                        <img src="{{ asset('frontend/assets/img/user-default.png') }}"
                                            alt="{{ $event->user->name }}" class="author-avatar" loading="lazy" />
                                    @endif
                                    <span class="author-name">{{ $event->user->name }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($event->photo)
                            <div class="blog-detail-image">
                                <img src="{{ asset($event->photo) }}"
                                    alt="{{ languageText($event->name, $event->nama) }}" class="img-fluid" loading="eager" />
                            </div>
                        @endif

                        <div class="blog-detail-content">
                            {!! languageText($event->description, $event->deskripsi) !!}
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">

                                @php
                                    $tags = $blog->tags ? explode(', ', strip_tags($blog->tags)) : [];
                                @endphp
                                @if (!empty($tags))
                                    <div class="blog-detail-tags">
                                        <h3 class="tags-title">Tags</h3>
                                        <div class="tags-list">
                                            @foreach ($tags as $tag)
                                                @if (trim($tag))
                                                    <a href="" class="tag-item">{{ trim($tag) }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="blog-detail-share">
                                    <span class="share-label">Share</span>
                                    <div class="share-buttons">
                                        <a href="https://wa.me/?text={{ urlencode(languageText($event->name, $event->nama) . ' - ' . url()->current()) }}"
                                            target="_blank" class="share-btn" aria-label="Share on WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                            target="_blank" class="share-btn" aria-label="Share on Facebook">
                                            <i class="bi bi-facebook"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode(languageText($event->name, $event->nama)) }}"
                                            target="_blank" class="share-btn" aria-label="Share on X">
                                            <i class="bi bi-twitter"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- RIGHT COLUMN: SIDEBAR -->
                <div class="col-lg-4">
                    <aside class="blog-sidebar">
                        <h2 class="sidebar-title">{{ languageText('Other Events', 'Event Lainnya') }}</h2>
                        <div class="sidebar-articles">
                            @forelse($recent_events as $recent_event)
                                <article class="sidebar-article-card">
                                    <div class="sidebar-article-thumb">
                                        <a href="{{ url('event/' . $recent_event->url) }}">
                                            <img src="{{ asset($recent_event->photo) }}"
                                                alt="{{ languageText($recent_event->name, $recent_event->nama) }}"
                                                class="img-fluid" />
                                        </a>
                                    </div>
                                    <div class="sidebar-article-content">
                                        <div class="article-meta">
                                            @if ($recent_event->category)
                                                <span
                                                    class="badge badge-dark">{{ languageText($recent_event->category->title, $recent_event->category->judul) }}</span>
                                            @endif
                                            <span
                                                class="date">{{ date('d M Y', strtotime($recent_event->date ?? $recent_event->created_at)) }}</span>
                                        </div>
                                        <h3>
                                            <a href="{{ url('event/' . $recent_event->url) }}">
                                                {{ languageText($recent_event->name, $recent_event->nama) }}
                                            </a>
                                        </h3>
                                    </div>
                                </article>
                            @empty
                                <p class="text-muted">
                                    {{ languageText('No other events found', 'Tidak ada event lainnya') }}.</p>
                            @endforelse
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</x-frontend.layout>
