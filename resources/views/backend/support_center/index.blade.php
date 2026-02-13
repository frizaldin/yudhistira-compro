<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Open Tiket</h4>
            <div class="d-flex align-items-center">
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Open Tiket</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ url('backend/support-centers') }}" class="mb-3 row g-2">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="Cari no tiket, judul, guru..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="process" {{ request('status') === 'process' ? 'selected' : '' }}>Process</option>
                                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
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
                                        <th>No. Tiket</th>
                                        <th>Guru</th>
                                        <th>Judul</th>
                                        <th>Topik</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">{{ $loop->iteration + ($collection->currentPage() - 1) * $collection->perPage() }}</td>
                                            <td><strong>{{ $item->ticket_number }}</strong></td>
                                            <td>{{ $item->teacher?->name ?? '-' }}<br><small class="text-muted">{{ $item->teacher?->email ?? '' }}</small></td>
                                            <td>{{ Str::limit($item->title, 40) }}</td>
                                            <td>{{ $item->topic ?? '-' }}</td>
                                            <td>
                                                @if($item->status === 'new')
                                                    <span class="badge bg-warning">New</span>
                                                @elseif($item->status === 'process')
                                                    <span class="badge bg-info">Process</span>
                                                @else
                                                    <span class="badge bg-success">Done</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->datetime ? $item->datetime->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                <a href="{{ url('backend/support-centers/' . $item->id) }}" class="btn btn-sm btn-info" title="Lihat"><i class="fa fa-eye"></i> Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Belum ada tiket.</td>
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
