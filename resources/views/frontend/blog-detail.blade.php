<x-frontend.layout>
    <section class="section section-blog-detail">
        <div class="container">
            <div class="row g-5">
                <!-- LEFT COLUMN: ARTICLE CONTENT -->
                <div class="col-lg-8">
                    <article class="blog-detail-article">
                        <h1 class="blog-detail-title">
                            {{ languageText($blog->name, $blog->nama) }}
                        </h1>

                        <div class="blog-detail-meta">
                            @if ($blog->category)
                                <span
                                    class="badge badge-dark">{{ languageText($blog->category->title, $blog->category->judul) }}</span>
                            @endif
                            <span class="blog-detail-date">
                                @php
                                    $dateToFormat = $blog->date ? $blog->date : $blog->created_at;
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
                            @if ($blog->user)
                                <div class="blog-detail-author">
                                    @if ($blog->user->photo)
                                        <img src="{{ asset($blog->user->photo) }}" alt="{{ $blog->user->name }}"
                                            class="author-avatar" loading="lazy" />
                                    @else
                                        <img src="{{ asset('frontend/assets/img/user-default.png') }}"
                                            alt="{{ $blog->user->name }}" class="author-avatar" loading="lazy" />
                                    @endif
                                    <span class="author-name">{{ $blog->user->name }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($blog->photo)
                            <div class="blog-detail-image">
                                <img src="{{ asset($blog->photo) }}" alt="{{ languageText($blog->name, $blog->nama) }}"
                                    class="img-fluid" loading="eager" />
                            </div>
                        @endif

                        <div class="blog-detail-content">
                            {!! languageText($blog->description, $blog->deskripsi) !!}
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
                                        <a href="https://wa.me/?text={{ urlencode(languageText($blog->name, $blog->nama) . ' - ' . url()->current()) }}"
                                            target="_blank" class="share-btn" aria-label="Share on WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                            target="_blank" class="share-btn" aria-label="Share on Facebook">
                                            <i class="bi bi-facebook"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode(languageText($blog->name, $blog->nama)) }}"
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
                        <h2 class="sidebar-title">{{ languageText('Other Articles', 'Berita Lainnya') }}</h2>
                        <div class="sidebar-articles">
                            @forelse($recent_blogs as $recent_blog)
                                <article class="sidebar-article-card">
                                    <div class="sidebar-article-thumb">
                                        <a href="{{ url('blog/' . $recent_blog->url) }}">
                                            <img src="{{ asset($recent_blog->photo) }}"
                                                alt="{{ languageText($recent_blog->name, $recent_blog->nama) }}"
                                                class="img-fluid" loading="lazy" />
                                        </a>
                                    </div>
                                    <div class="sidebar-article-content">
                                        <div class="article-meta">
                                            @if ($recent_blog->category)
                                                <span
                                                    class="badge badge-dark">{{ languageText($recent_blog->category->title, $recent_blog->category->judul) }}</span>
                                            @endif
                                            <span
                                                class="date">{{ date('d M Y', strtotime($recent_blog->date ?? $recent_blog->created_at)) }}</span>
                                        </div>
                                        <h3>
                                            <a href="{{ url('blog/' . $recent_blog->url) }}">
                                                {{ languageText($recent_blog->name, $recent_blog->nama) }}
                                            </a>
                                        </h3>
                                    </div>
                                </article>
                            @empty
                                <p class="text-muted">
                                    {{ languageText('No other articles found', 'Tidak ada berita lainnya') }}.</p>
                            @endforelse
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</x-frontend.layout>
