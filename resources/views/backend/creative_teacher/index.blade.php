<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Creative Teacher</h4>
            <div class="d-flex align-items-center">
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Creative Teacher</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ url('backend/creative-teachers') }}" class="mb-3 row g-2">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="Cari no, judul, guru..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="under review" {{ request('status') === 'under review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No.</th>
                                        <th>Guru</th>
                                        <th>Judul</th>
                                        <th>Topik</th>
                                        <th>Link Upload</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">{{ $loop->iteration + ($collection->currentPage() - 1) * $collection->perPage() }}</td>
                                            <td><strong>{{ $item->number }}</strong></td>
                                            <td>{{ $item->teacher?->name ?? '-' }}<br><small class="text-muted">{{ $item->teacher?->email ?? '' }}</small></td>
                                            <td>{{ Str::limit($item->title, 40) }}</td>
                                            <td>{{ $item->topic ?? '-' }}</td>
                                            <td>
                                                @if($item->link_upload)
                                                    <a href="{{ $item->link_upload }}" target="_blank" class="text-primary">Link <i class="fa fa-external-link"></i></a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status === 'new')
                                                    <span class="badge bg-secondary">New</span>
                                                @elseif($item->status === 'under review')
                                                    <span class="badge bg-info">Under Review</span>
                                                @elseif($item->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-success">Accepted</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->datetime ? $item->datetime->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                <a href="{{ url('backend/creative-teachers/' . $item->id) }}" class="btn btn-sm btn-info" title="Lihat"><i class="fa fa-eye"></i> Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{ $collection->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</x-backend.layouts>
