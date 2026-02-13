<x-backend.layouts>
    <div class="container-fluid">
        {{-- Top: back arrow + centered title "Detail Tiket" --}}
        <div class="page-title-box d-flex align-items-center mb-0">
            <a href="{{ url('backend/open-tickets') }}" class="btn btn-link text-dark p-0 me-3"
                style="font-size: 1.25rem;"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title mb-0 flex-grow-1 text-center">Detail Tiket</h4>
            <div style="width: 2.5rem;"></div>
        </div>

        <div class="card mb-1">
            <div class="card-body">
                <div class="row clearfix">
                    <div class="col-lg-12 col-xl-12">
                        {{-- Baris: #NoTiket · Tanggal · Badge Status --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="text-muted">#{{ $item->ticket_number }}</span>
                            <span class="text-muted">·</span>
                            <span
                                class="text-muted">{{ $item->datetime ? $item->datetime->locale('id')->translatedFormat('d F Y, H:i') : '-' }}</span>
                            <span class="text-muted">·</span>
                            @if ($item->status === 'new')
                                <span class="badge rounded-pill px-3 py-2"
                                    style="background-color: #f8d7da; color: #721c24;">Baru</span>
                            @elseif($item->status === 'process')
                                <span class="badge rounded-pill bg-info px-3 py-2">Diproses</span>
                            @else
                                <span class="badge rounded-pill bg-success px-3 py-2">Selesai</span>
                            @endif
                        </div>

                        {{-- Judul tiket (bold) --}}
                        <h5 class="fw-bold mb-2">{{ $item->title }}</h5>

                        {{-- Topik pesan --}}
                        @if ($item->topic)
                            <p class="text-muted mb-3">Topik Pesan: {{ $item->topic }}</p>
                        @endif

                        {{-- Isi pesan (paragraf) --}}
                        <div class="mb-4">
                            {!! nl2br(e($item->message)) !!}
                        </div>

                        {{-- Lampiran: galeri horizontal (gambar preview, file link) --}}
                        @if ($item->attachments->count() > 0)
                            <div class="d-flex flex-wrap gap-3">
                                @foreach ($item->attachments as $att)
                                    @php
                                        $ext = strtolower(pathinfo($att->path ?? '', PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if ($isImage)
                                        <a href="{{ asset($att->path) }}" target="_blank"
                                            class="d-block text-decoration-none">
                                            <img src="{{ asset($att->path) }}"
                                                alt="{{ $att->original_name ?? 'Lampiran' }}" class="rounded shadow-sm"
                                                style="max-width: 180px; max-height: 220px; object-fit: cover;">
                                        </a>
                                    @else
                                        <a href="{{ asset($att->path) }}" target="_blank"
                                            class="btn btn-outline-primary rounded shadow-sm d-inline-flex align-items-center"
                                            style="max-width: 180px;">
                                            <i class="fa fa-file me-2"></i>
                                            <span class="text-truncate">{{ $att->original_name ?? 'File' }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Chat --}}
        <div class="row clearfix mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Chat</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @forelse($item->chats->sortBy('created_at') as $chat)
                            <div class="d-flex mb-3 {{ $chat->viewpoint === 'admin' ? 'justify-content-end' : '' }}">
                                <div class="rounded p-3 {{ $chat->viewpoint === 'admin' ? 'bg-primary text-white' : 'bg-light' }} position-relative"
                                    style="max-width: 75%;">
                                    {{-- Titik tiga (dropdown) kanan atas untuk chat admin --}}
                                    @if ($chat->viewpoint === 'admin')
                                        <div class="position-absolute top-0 end-0 pe-2 pt-1">
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-sm btn-link text-white-50 p-0 border-0 shadow-none"
                                                    style="line-height: 1;" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="button" class="dropdown-item btn-edit-chat"
                                                            data-chat-id="{{ $chat->id }}"
                                                            data-message="{{ $chat->message }}">
                                                            <i class="fa fa-edit me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ url('backend/open-tickets/chats/' . $chat->id) }}"
                                                            method="post" class="d-inline"
                                                            onsubmit="return confirm('Yakin hapus chat ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa fa-trash me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <small class="d-block mb-1">
                                        <strong>{{ $chat->viewpoint === 'admin' ? 'Admin' : $chat->teacher?->name ?? 'User' }}</strong>
                                        · {{ $chat->created_at->format('d/m/Y H:i') }}
                                        @if ($chat->is_read === 'yes')
                                            <span class="badge bg-success ms-1">Dibaca</span>
                                        @endif
                                    </small>
                                    {{-- Tampilan normal --}}
                                    <div class="chat-message-{{ $chat->id }}">{{ $chat->message }}</div>
                                    @if ($chat->attachments->count() > 0)
                                        <div class="mt-2 chat-attachments-{{ $chat->id }}">
                                            @foreach ($chat->attachments as $att)
                                                <a href="{{ asset($att->file) }}" target="_blank"
                                                    class="btn btn-sm {{ $chat->viewpoint === 'admin' ? 'btn-outline-light' : 'btn-outline-secondary' }} me-1">{{ $att->file ? basename($att->file) : 'File' }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($chat->viewpoint === 'admin')
                                        <div class="chat-actions-{{ $chat->id }} d-none"></div>
                                    @endif
                                    {{-- Form edit (tersembunyi, untuk chat admin) --}}
                                    @if ($chat->viewpoint === 'admin')
                                        <div class="chat-edit-form-{{ $chat->id }} d-none mt-2">
                                            <form action="{{ url('backend/open-tickets/chats/' . $chat->id) }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="message" class="form-control form-control-sm mb-2" rows="2" required>{{ $chat->message }}</textarea>
                                                <input type="file" name="files[]"
                                                    class="form-control form-control-sm mb-2" multiple
                                                    accept="image/*,.pdf,.doc,.docx">
                                                <small class="text-white-50 d-block mb-2">Opsional: tambah file. Max
                                                    10MB.</small>
                                                <button type="submit" class="btn btn-sm btn-light me-1">Simpan</button>
                                                <button type="button" class="btn btn-sm btn-light btn-cancel-edit"
                                                    data-chat-id="{{ $chat->id }}"
                                                    data-message="{{ $chat->message }}">Batal</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Belum ada chat.</p>
                        @endforelse

                        {{-- Form balas chat (admin) --}}
                        <hr class="my-4">
                        <form action="{{ url('backend/open-tickets/' . $item->id . '/chat') }}" method="post"
                            enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <label class="form-label fw-semibold">Balas Chat</label>
                            <textarea name="message" class="form-control mb-2" rows="3" placeholder="Tulis balasan..." required></textarea>
                            <div class="mb-2">
                                <input type="file" name="files[]" class="form-control form-control-sm" multiple
                                    accept="image/*,.pdf,.doc,.docx">
                                <small class="text-muted">Opsional. Max 10MB per file.</small>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i>
                                Kirim
                                Balasan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script>
            document.querySelectorAll('.btn-edit-chat').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.dataset.chatId;
                    document.querySelector('.chat-message-' + id).classList.add('d-none');
                    var att = document.querySelector('.chat-attachments-' + id);
                    if (att) att.classList.add('d-none');
                    document.querySelector('.chat-actions-' + id).classList.add('d-none');
                    document.querySelector('.chat-edit-form-' + id).classList.remove('d-none');
                });
            });
            document.querySelectorAll('.btn-cancel-edit').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.dataset.chatId;
                    document.querySelector('.chat-message-' + id).textContent = this.dataset.message;
                    document.querySelector('.chat-message-' + id).classList.remove('d-none');
                    var att = document.querySelector('.chat-attachments-' + id);
                    if (att) att.classList.remove('d-none');
                    document.querySelector('.chat-actions-' + id).classList.remove('d-none');
                    document.querySelector('.chat-edit-form-' + id).classList.add('d-none');
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
