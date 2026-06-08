<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Serial Code Member</h4>
            <div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('backend/serial-code-member') }}">Serial Code Member</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Edit Serial Code Member</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary">
                        <i class="fa fa-key me-1"></i>
                        <strong>Code:</strong> <code class="user-select-all">{{ $item->code }}</code>
                    </div>

                    <form id="basic-form" action="{{ url('backend/serial-code-member/update') }}" class="_form"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">

                        {{-- Book selection --}}
                        <div class="form-group mb-3">
                            <label>Buku <span class="text-danger">*</span></label>
                            <select id="book-select" name="book_id" class="form-select" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book['id'] }}"
                                        {{ (string) $book['id'] === (string) $item->book_id ? 'selected' : '' }}>
                                        {{ $book['title'] }}
                                    </option>
                                @endforeach
                            </select>
                            @if (empty($books))
                                <small class="text-danger">Gagal memuat data buku dari API.</small>
                            @else
                                <small class="text-muted">{{ count($books) }} buku tersedia.</small>
                            @endif
                        </div>

                        {{-- Redeem codes selection --}}
                        <div class="form-group mb-3">
                            <label>Redeem Code E-Book</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" id="search-redeem" class="form-control"
                                    placeholder="Cari serial code atau judul buku...">
                            </div>
                            <div class="border rounded p-2"
                                style="max-height:260px; overflow-y:auto; background:#fafafa;">
                                @php $selectedIds = $item->serial_code_ebook ?? []; @endphp
                                @forelse ($redeemCodes as $rc)
                                    <div class="form-check redeem-item"
                                        data-label="{{ strtolower($rc->book_title . ' ' . $rc->serial_code) }}">
                                        <input class="form-check-input" type="checkbox" name="serial_code_ebook[]"
                                            value="{{ $rc->id }}" id="rc_{{ $rc->id }}"
                                            {{ in_array($rc->id, (array) $selectedIds) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rc_{{ $rc->id }}">
                                            <code>{{ $rc->serial_code }}</code>
                                            @if ($rc->book_title)
                                                <small class="text-muted ms-1">— {{ $rc->book_title }}</small>
                                            @endif
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0 text-center">Tidak ada redeem code tersedia.</p>
                                @endforelse
                            </div>
                            <small class="text-muted">Pilih satu atau lebih redeem code.</small>
                        </div>

                        {{-- Used status --}}
                        <div class="form-group mb-3">
                            <label>Status Penggunaan</label>
                            <select name="used" class="form-select">
                                <option value="no" {{ $item->used === 'no' ? 'selected' : '' }}>Belum Dipakai
                                </option>
                                <option value="yes" {{ $item->used === 'yes' ? 'selected' : '' }}>Sudah Dipakai
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('backend/serial-code-member') }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Preview Buku Dipilih</h6>
                </div>
                <div class="card-body" id="book-preview">
                    @php $bj = $item->book_json; @endphp
                    @if ($bj)
                        @php $img = $bj['file_url'] ?? $bj['file'] ?? ''; @endphp
                        @if ($img)
                            <img src="{{ $img }}" class="img-fluid rounded mb-2" style="max-height:120px;">
                        @endif
                        <p class="mb-1"><strong>{{ $bj['title'] ?? ($bj['nama'] ?? '-') }}</strong></p>
                        <small class="text-muted">ID: {{ $item->book_id }}</small>
                    @else
                        <p class="text-muted text-center">Pilih buku terlebih dahulu.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
        <script>
            const _booksData = @json($books);

            function getSelectedBook() {
                const id = document.getElementById('book-select').value;
                return id ? (_booksData.find(b => String(b.id) === String(id)) ?? null) : null;
            }

            function updatePreview(book) {
                const preview = document.getElementById('book-preview');
                if (book) {
                    const img = book.file_url ?? book.file ?? '';
                    const title = book.title ?? book.nama ?? '-';
                    preview.innerHTML = `
                        ${img ? `<img src="${img}" class="img-fluid rounded mb-2" style="max-height:120px;">` : ''}
                        <p class="mb-1"><strong>${title}</strong></p>
                        <small class="text-muted">ID: ${book.id}</small>`;
                } else {
                    preview.innerHTML = '<p class="text-muted text-center">Pilih buku terlebih dahulu.</p>';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                let _lastVal = document.getElementById('book-select').value;

                // Polling untuk preview real-time
                setInterval(function() {
                    const val = document.getElementById('book-select').value;
                    if (val !== _lastVal) {
                        _lastVal = val;
                        updatePreview(getSelectedBook());
                    }
                }, 200);

                document.getElementById('search-redeem').addEventListener('input', function() {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll('.redeem-item').forEach(el => {
                        el.style.display = el.dataset.label.includes(q) ? '' : 'none';
                    });
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
