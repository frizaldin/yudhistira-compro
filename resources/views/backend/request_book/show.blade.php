<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-flex align-items-center mb-0">
            <a href="{{ url('backend/request-books') }}" class="btn btn-link text-dark p-0 me-3"
                style="font-size: 1.25rem;"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title mb-0 flex-grow-1 text-center">Detail Request Buku</h4>
            <div style="width: 2.5rem;"></div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-3 mt-2">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="text-muted">#{{ $item->number }}</span>
                    <span class="text-muted">·</span>
                    <span
                        class="text-muted">{{ $item->date ? $item->date->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    <span class="text-muted">·</span>
                    @if ($item->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($item->status === 'processed')
                        <span class="badge bg-success">Diproses</span>
                    @else
                        <span class="badge bg-secondary">Ditolak</span>
                    @endif
                </div>
                <p class="mb-1"><strong>Guru:</strong> {{ $item->teacher?->name ?? '-' }}
                    ({{ $item->teacher?->email ?? '-' }})</p>

            </div>
        </div>

        <h4>Buku yang diminta :</h4>
        <div class="row">
            @foreach ($item->details as $detail)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                    
                            <div class="d-flex align-items-center">
                
                                <!-- Image kecil -->
                                <img src="{{ $detail['book']['file_url'] }}"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:8px;"
                                     alt="{{ $detail['book']['title'] }}">
                
                                <!-- Text -->
                                <div class="ms-3" style="overflow:hidden;">
                                    <div style="font-size:14px; font-weight:500; line-height:1.2; margin-bottom:5px">
                                        {{ $detail['book']['title'] }}
                                    </div>
                
                                    <div style="font-size:12px; color:#777;">
                                        ISBN : {{ $detail['book']['isbn'] ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Isi kode buku & status</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('backend/request-books/update/' . $item->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>File Kode Buku</label>
                        <input type="file" name="code_book" class="form-control" accept=".pdf">
                        @if($item->code_book)
                            <small class="text-muted">File saat ini: {{ basename($item->code_book) }}</small>
                        @endif
                        <small class="text-muted">Diisi admin setelah kode digital siap diberikan ke guru.</small>
                    </div>
                   
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Kosongkan jika belum ada">{{ old('note', $item->note) }}</textarea>
                    </div>
                    @if ($item->status == 'pending')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="processed" {{ $item->status === 'processed' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-backend.layouts>
