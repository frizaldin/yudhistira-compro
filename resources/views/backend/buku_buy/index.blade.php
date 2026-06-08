<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Katalog Buku BuyBuku</h4>
            <div class="d-flex align-items-center">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Katalog Buku</li>
                </ol>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Search Bar --}}
                        <form method="GET" action="">
                            <div class="row g-2 align-items-end mb-4">
                                <div class="col-md-9">
                                    <label class="form-label fw-semibold mb-1">Cari Buku</label>
                                    <input name="search" type="text" class="form-control"
                                           placeholder="Ketik judul, ISBN, atau pengarang..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="iconoir-search me-1"></i> Cari
                                    </button>
                                </div>
                                @if(request('search'))
                                    <div class="col-md-1">
                                        <a href="{{ url('backend/buku-buy') }}" class="btn btn-outline-secondary w-100">Reset</a>
                                    </div>
                                @endif
                            </div>
                        </form>

                        {{-- Error --}}
                        @if($error)
                            <div class="alert alert-danger">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="iconoir-warning-triangle fs-5 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $error }}</p>
                                    </div>
                                </div>
                            </div>

                        {{-- Empty --}}
                        @elseif(empty($products))
                            <div class="text-center py-5 text-muted">
                                <i class="iconoir-book-stack d-block mb-3" style="font-size:3rem;"></i>
                                <p class="mt-3 mb-0">Tidak ada data buku ditemukan.</p>
                            </div>

                        {{-- Grid --}}
                        @else
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
                                @foreach($products as $book)
                                    @php
                                        $title  = $book['title']        ?? $book['name']   ?? '-';
                                        $author = $book['penulis']      ?? $book['author'] ?? null;
                                        $pub    = $book['penerbit']     ?? $book['publisher'] ?? null;
                                        $isbn   = $book['isbn']         ?? $book['kode_buku'] ?? null;
                                        $sku    = $book['sku']          ?? null;
                                        $price  = $book['harga']        ?? $book['price']  ?? null;
                                        $stock  = $book['stok']         ?? $book['stock']  ?? null;
                                        $cover  = $book['cover_image']  ?? $book['image']  ?? $book['cover'] ?? $book['file_url'] ?? $book['file'] ?? null;
                                        $desc   = $book['description']  ?? $book['deskripsi'] ?? null;
                                    @endphp
                                    <div class="col">
                                        <div class="card h-100 border shadow-sm buku-card">
                                            {{-- Cover --}}
                                            <div class="position-relative"
                                                 style="height:220px;overflow:hidden;background:#f8f9fa;border-radius:8px 8px 0 0;">
                                                @if($cover)
                                                    <img src="{{ $cover }}" alt="{{ $title }}"
                                                         class="w-100 h-100" style="object-fit:cover;"
                                                         onerror="this.style.display='none'">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                        <i class="iconoir-book" style="font-size:3rem;"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Body --}}
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title fw-bold mb-1 lh-sm"
                                                    style="font-size:.88rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                    {{ $title }}
                                                </h6>
                                                @if($author)
                                                    <p class="text-muted mb-1" style="font-size:.78rem;">
                                                        <i class="iconoir-user me-1"></i>{{ $author }}
                                                    </p>
                                                @endif
                                                @if($pub)
                                                    <p class="text-muted mb-1" style="font-size:.78rem;">
                                                        <i class="iconoir-building me-1"></i>{{ $pub }}
                                                    </p>
                                                @endif
                                                @if($isbn)
                                                    <p class="text-muted mb-2" style="font-size:.75rem;">
                                                        ISBN: {{ $isbn }}
                                                    </p>
                                                @endif

                                                <div class="mt-auto">
                                                    @if(!is_null($price))
                                                        <p class="fw-bold text-success mb-1" style="font-size:.92rem;">
                                                            Rp {{ number_format($price, 0, ',', '.') }}
                                                        </p>
                                                    @endif
                                                    @if(!is_null($stock))
                                                        <span class="badge {{ $stock > 0 ? 'bg-success' : 'bg-danger' }} mb-2"
                                                              style="font-size:.72rem;">
                                                            {{ $stock > 0 ? 'Stok: '.$stock : 'Habis' }}
                                                        </span>
                                                    @endif
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-sm btn-outline-primary flex-fill btn-detail"
                                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                                data-title="{{ $title }}"
                                                                data-author="{{ $author }}"
                                                                data-publisher="{{ $pub }}"
                                                                data-isbn="{{ $isbn }}"
                                                                data-sku="{{ $sku }}"
                                                                data-price="{{ $price }}"
                                                                data-stock="{{ $stock }}"
                                                                data-cover="{{ $cover }}"
                                                                data-description="{{ $desc }}">
                                                            <i class="iconoir-eye me-1"></i> Detail
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary flex-fill btn-dokumen"
                                                                data-bs-toggle="modal" data-bs-target="#dokumenModal"
                                                                data-product-id="{{ $book['id'] ?? '' }}"
                                                                data-product-title="{{ $title }}"
                                                                data-json-product='{{ json_encode($book) }}'>
                                                            <i class="iconoir-attachment me-1"></i> Dokumen
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($meta)
                                @php
                                    $curPage  = $meta['current_page'] ?? 1;
                                    $lastPage = $meta['last_page']    ?? 1;
                                    $total    = $meta['total']        ?? count($products);
                                    $perPage  = $meta['per_page']     ?? count($products);
                                    $from     = (($curPage - 1) * $perPage) + 1;
                                    $to       = min($curPage * $perPage, $total);
                                @endphp
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <small class="text-muted">
                                        Menampilkan {{ $from }}–{{ $to }} dari {{ $total }} buku
                                    </small>
                                    @if($lastPage > 1)
                                        <nav>
                                            <ul class="pagination pagination-sm mb-0">
                                                {{-- Prev --}}
                                                <li class="page-item {{ $curPage <= 1 ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                       href="{{ request()->fullUrlWithQuery(['page' => $curPage - 1]) }}">
                                                        &laquo;
                                                    </a>
                                                </li>
                                                {{-- Halaman --}}
                                                @for($p = max(1, $curPage - 2); $p <= min($lastPage, $curPage + 2); $p++)
                                                    <li class="page-item {{ $p == $curPage ? 'active' : '' }}">
                                                        <a class="page-link"
                                                           href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">
                                                            {{ $p }}
                                                        </a>
                                                    </li>
                                                @endfor
                                                {{-- Next --}}
                                                <li class="page-item {{ $curPage >= $lastPage ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                       href="{{ request()->fullUrlWithQuery(['page' => $curPage + 1]) }}">
                                                        &raquo;
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    @endif
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="detailModalLabel">Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <img id="modal-cover" src="" alt="Cover Buku" class="img-fluid rounded shadow-sm"
                                 style="max-height:280px; object-fit:cover; display:none;">
                            <div id="modal-no-cover" class="text-muted mt-3">
                                <i class="iconoir-book" style="font-size:4rem;"></i>
                                <p class="mt-2 small">Tidak ada cover</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h5 id="modal-title" class="fw-bold mb-3"></h5>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr><td class="text-muted fw-semibold" style="width:130px;">Penulis</td><td id="modal-author">-</td></tr>
                                    <tr><td class="text-muted fw-semibold">Penerbit</td><td id="modal-publisher">-</td></tr>
                                    <tr><td class="text-muted fw-semibold">ISBN</td><td id="modal-isbn">-</td></tr>
                                    <tr><td class="text-muted fw-semibold">SKU</td><td id="modal-sku">-</td></tr>
                                    <tr><td class="text-muted fw-semibold">Harga</td><td id="modal-price" class="fw-bold text-success">-</td></tr>
                                    <tr><td class="text-muted fw-semibold">Stok</td><td id="modal-stock">-</td></tr>
                                </tbody>
                            </table>
                            <p class="text-muted fw-semibold mb-1">Deskripsi:</p>
                            <p id="modal-description" class="small" style="max-height:140px; overflow-y:auto;"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen Modal --}}
    <div class="modal fade" id="dokumenModal" tabindex="-1" aria-labelledby="dokumenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="dokumenModalLabel">Dokumen Buku</h5>
                        <small id="dokumen-book-title" class="text-muted"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    {{-- Form tambah dokumen --}}
                    <div class="card border mb-4">
                        <div class="card-header bg-light fw-semibold">
                            <i class="iconoir-add-page me-1"></i> Tambah Dokumen Baru
                        </div>
                        <div class="card-body">
                            <form id="form-dokumen">
                                @csrf
                                <input type="hidden" id="dok-product-id" name="product_id">
                                <input type="hidden" id="dok-json-product" name="json_product">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tipe Lampiran</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                   id="type-link" value="link" checked>
                                            <label class="form-check-label" for="type-link">
                                                <i class="iconoir-link me-1"></i> Link / Google Drive
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type"
                                                   id="type-file" value="file">
                                            <label class="form-check-label" for="type-file">
                                                <i class="iconoir-upload me-1"></i> Upload File
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3" id="wrap-link">
                                    <label class="form-label fw-semibold">URL Link / Google Drive</label>
                                    <input type="url" name="link" id="dok-link" class="form-control"
                                           placeholder="https://drive.google.com/...">
                                </div>

                                <div class="mb-3" id="wrap-file" style="display:none;">
                                    <label class="form-label fw-semibold">Upload File</label>
                                    <input type="file" name="file" id="dok-file" class="form-control"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                                    <small class="text-muted">Maks 20MB. Format: PDF, Word, Excel, PPT, Gambar, ZIP</small>
                                </div>

                                <button type="submit" id="btn-simpan-dok" class="btn btn-primary">
                                    <i class="iconoir-save me-1"></i> Simpan Dokumen
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Daftar dokumen --}}
                    <h6 class="fw-semibold mb-3">Daftar Dokumen</h6>
                    <div id="dok-loading" class="text-center py-3 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Memuat...
                    </div>
                    <div id="dok-empty" class="text-center py-3 text-muted" style="display:none;">
                        <i class="iconoir-attachment d-block mb-2" style="font-size:2rem;"></i>
                        Belum ada dokumen untuk buku ini.
                    </div>
                    <div id="dok-list"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="js">
        <style>
            .buku-card { transition: box-shadow .2s, transform .2s; }
            .buku-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.13) !important; transform: translateY(-2px); }
        </style>
        <script>
        $(function () {

            /* ─── Detail Modal ─── */
            $('#detailModal').on('show.bs.modal', function (e) {
                var btn = $(e.relatedTarget);

                $('#modal-title').text(btn.data('title') || '-');
                $('#modal-author').text(btn.data('author') || '-');
                $('#modal-publisher').text(btn.data('publisher') || '-');
                $('#modal-isbn').text(btn.data('isbn') || '-');
                $('#modal-sku').text(btn.data('sku') || '-');
                $('#modal-description').text(btn.data('description') || '-');

                var price = btn.data('price');
                $('#modal-price').text(price ? 'Rp ' + parseInt(price).toLocaleString('id-ID') : '-');

                var stock = btn.data('stock');
                if (stock !== '' && stock !== undefined && stock !== null) {
                    $('#modal-stock').html(parseInt(stock) > 0
                        ? '<span class="badge bg-success">Stok: ' + stock + '</span>'
                        : '<span class="badge bg-danger">Habis</span>');
                } else {
                    $('#modal-stock').text('-');
                }

                var cover = btn.data('cover');
                if (cover) {
                    $('#modal-cover').attr('src', cover).show();
                    $('#modal-no-cover').hide();
                } else {
                    $('#modal-cover').hide();
                    $('#modal-no-cover').show();
                }
            });

            /* ─── Dokumen Modal ─── */
            var currentProductId = null;

            $('#dokumenModal').on('show.bs.modal', function (e) {
                var btn = $(e.relatedTarget);
                currentProductId = btn.data('product-id');

                $('#dokumen-book-title').text(btn.data('product-title') || '');
                $('#dok-product-id').val(currentProductId);
                $('#dok-json-product').val(btn.attr('data-json-product'));

                // Reset form
                $('#form-dokumen')[0].reset();
                $('#wrap-link').show();
                $('#wrap-file').hide();
                $('input[name="type"][value="link"]').prop('checked', true);

                loadDokumen(currentProductId);
            });

            // Toggle link/file input
            $('input[name="type"]').on('change', function () {
                if ($(this).val() === 'link') {
                    $('#wrap-link').show();
                    $('#wrap-file').hide();
                } else {
                    $('#wrap-link').hide();
                    $('#wrap-file').show();
                }
            });

            // Load daftar dokumen via AJAX
            function loadDokumen(productId) {
                $('#dok-loading').show();
                $('#dok-empty').hide();
                $('#dok-list').empty();

                $.get('{{ url("backend/buku-buy/documents") }}/' + productId, function (res) {
                    $('#dok-loading').hide();
                    var docs = res.data || [];

                    if (!docs.length) {
                        $('#dok-empty').show();
                        return;
                    }

                    $.each(docs, function (i, doc) {
                        var icon  = doc.type === 'link'
                            ? '<i class="iconoir-link text-primary me-2"></i>'
                            : '<i class="iconoir-document text-success me-2"></i>';
                        var label = doc.type === 'link'
                            ? '<a href="' + doc.attachment + '" target="_blank" class="text-break">' + doc.attachment + '</a>'
                            : '<a href="' + doc.attachment + '" target="_blank">' + (doc.filename || 'File') + '</a>';

                        $('#dok-list').append(
                            '<div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" data-doc-id="' + doc.id + '">'
                            + '<div class="d-flex align-items-center flex-grow-1 me-2" style="min-width:0;">'
                            + icon + '<span class="text-truncate">' + label + '</span>'
                            + '</div>'
                            + '<div class="d-flex align-items-center gap-2 flex-shrink-0">'
                            + '<small class="text-muted">' + doc.created_at + '</small>'
                            + '<button class="btn btn-sm btn-outline-danger btn-hapus-dok" data-id="' + doc.id + '">'
                            + '<i class="iconoir-trash"></i></button>'
                            + '</div>'
                            + '</div>'
                        );
                    });
                }).fail(function () {
                    $('#dok-loading').hide();
                    $('#dok-list').html('<div class="alert alert-warning">Gagal memuat dokumen.</div>');
                });
            }

            // Simpan dokumen baru
            $('#form-dokumen').on('submit', function (e) {
                e.preventDefault();

                var btn  = $('#btn-simpan-dok').prop('disabled', true).text('Menyimpan...');
                var type = $('input[name="type"]:checked').val();
                var fd   = new FormData(this);
                fd.set('type', type);

                $.ajax({
                    url       : '{{ url("backend/buku-buy/documents") }}',
                    method    : 'POST',
                    data      : fd,
                    processData: false,
                    contentType: false,
                    headers   : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success   : function (res) {
                        btn.prop('disabled', false).html('<i class="iconoir-save me-1"></i> Simpan Dokumen');
                        $('#form-dokumen')[0].reset();
                        $('#wrap-link').show();
                        $('#wrap-file').hide();
                        loadDokumen(currentProductId);
                    },
                    error     : function (xhr) {
                        btn.prop('disabled', false).html('<i class="iconoir-save me-1"></i> Simpan Dokumen');
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal menyimpan dokumen.';
                        alert(msg);
                    }
                });
            });

            // Hapus dokumen
            $(document).on('click', '.btn-hapus-dok', function () {
                if (!confirm('Yakin hapus dokumen ini?')) return;
                var id  = $(this).data('id');
                var row = $(this).closest('[data-doc-id]');

                $.ajax({
                    url    : '{{ url("backend/buku-buy/documents") }}/' + id,
                    method : 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        row.fadeOut(300, function () { $(this).remove(); });
                        if (!$('#dok-list [data-doc-id]').length) $('#dok-empty').show();
                    },
                    error  : function () { alert('Gagal menghapus dokumen.'); }
                });
            });

        });
        </script>
    </x-slot>
</x-backend.layouts>
