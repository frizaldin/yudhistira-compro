<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-flex align-items-center mb-0">
            <a href="{{ url('backend/creative-teachers') }}" class="btn btn-link text-dark p-0 me-3"
                style="font-size: 1.25rem;"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title mb-0 flex-grow-1 text-center">Detail Creative Teacher</h4>
            <div style="width: 2.5rem;"></div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="text-muted">#{{ $item->number }}</span>
                    <span class="text-muted">·</span>
                    <span
                        class="text-muted">{{ $item->datetime ? $item->datetime->locale('id')->translatedFormat('d F Y, H:i') : '-' }}</span>
                    <span class="text-muted">·</span>
                    @if ($item->status === 'new')
                        <span class="badge bg-secondary">New</span>
                    @elseif($item->status === 'under review')
                        <span class="badge bg-info">Under Review</span>
                    @elseif($item->status === 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-success">Accepted</span>
                    @endif
                </div>

                <h5 class="fw-bold mb-2">{{ $item->title }}</h5>
                @if ($item->topic)
                    <p class="text-muted mb-2">Topik: {{ $item->topic }}</p>
                @endif
                @if ($item->message)
                    <div class="mb-3">{!! nl2br(e($item->message)) !!}</div>
                @endif
                @if ($item->link_upload)
                    <p class="mb-0">
                        <strong>Link Upload:</strong>
                        <a href="{{ $item->link_upload }}" target="_blank" class="ms-1">{{ $item->link_upload }}</a>
                    </p>
                @endif

                <hr class="my-3">
                <p class="text-muted mb-0">
                    <strong>Guru:</strong> {{ $item->teacher?->name ?? '-' }}
                    @if ($item->teacher?->email)
                        &middot; {{ $item->teacher->email }}
                    @endif
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('backend/creative-teachers/' . $item->id . '/status') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="new" {{ $item->status === 'new' ? 'selected' : '' }}>New</option>
                                <option value="under review" {{ $item->status === 'under review' ? 'selected' : '' }}>
                                    Under Review</option>
                                <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="accepted" {{ $item->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Point (opsional)</label>
                            <input type="number" name="point" class="form-control" min="0" placeholder="0" value="{{ old('point') }}">
                            <small class="text-muted">Isi jika status Accepted & ingin beri point ke user.</small>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan Status</button>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan point (opsional)</label>
                            <input type="text" name="note" class="form-control" placeholder="Contoh: Creative Teacher #{{ $item->number }} (accepted)" value="{{ old('note') }}">
                            <small class="text-muted">Catatan untuk user_points (asal point).</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-backend.layouts>
