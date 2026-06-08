<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Request Buku Digital</h4>
            <div class="d-flex align-items-center">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Request Buku Digital</li>
                </ol>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ url('backend/request-books') }}" class="mb-3 row g-2">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="No. request, kode, nama/email guru..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Diproses</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                                        <th>No. Request</th>
                                        <th>Guru</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Buku</th>
                                        <th>Status</th>
                                        <th>Kode Buku</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr>
                                            <td style="width: 50px;">{{ $loop->iteration + ($collection->currentPage() - 1) * $collection->perPage() }}</td>
                                            <td><strong>{{ $item->number }}</strong></td>
                                            <td>{{ $item->teacher?->name ?? '-' }}<br><small class="text-muted">{{ $item->teacher?->email ?? '' }}</small></td>
                                            <td>{{ $item->date ? $item->date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $item->details->count() }}</td>
                                            <td>
                                                @if ($item->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($item->status === 'processed')
                                                    <span class="badge bg-success">Diproses</span>
                                                @else
                                                    <span class="badge bg-secondary">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->code_book ? Str::limit($item->code_book, 24) : '—' }}</td>
                                            <td>
                                                <a href="{{ url('backend/request-books/' . $item->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Belum ada permintaan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $collection->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts>
