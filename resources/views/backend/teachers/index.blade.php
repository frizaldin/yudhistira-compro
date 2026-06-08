<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Teachers</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Teachers</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="{{ url('backend/teachers') }}" class="mb-3 row g-2">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, NPSN, sekolah..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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
                                        <th>Nama</th>
                                        <th>NPSN</th>
                                        <th>Email</th>
                                        <th>Sekolah</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">{{ $loop->iteration + ($collection->currentPage() - 1) * $collection->perPage() }}</td>
                                            <td>
                                                @if($item->photo)
                                                    <img style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" src="{{ asset($item->photo) }}" alt="" />
                                                @else
                                                    <span class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">{{ strtoupper(substr($item->name ?? '?', 0, 1)) }}</span>
                                                @endif
                                                <span class="c_name ms-2">{{ $item->name }}</span>
                                            </td>
                                            <td>{{ $item->npsn ?? '-' }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->school_name ?? '-' }}</td>
                                            <td>
                                                @if($item->status === 'active')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ url('backend/teachers/edit/' . $item->id) }}" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="{{ url('backend/teachers/password/' . $item->id) }}" class="btn btn-sm btn-warning" title="Ganti Password"><i class="fa fa-key"></i></a>
                                                <button type="button" class="btn btn-sm {{ $item->status === 'active' ? 'btn-secondary' : 'btn-success' }} btn-toggle-status" data-id="{{ $item->id }}" data-status="{{ $item->status }}" title="{{ $item->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fa {{ $item->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada data teacher.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{ $collection->links() }}
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script>
            document.querySelectorAll('.btn-toggle-status').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var status = this.getAttribute('data-status');
                    var label = status === 'active' ? 'Nonaktifkan' : 'Aktifkan';
                    if (!confirm('Yakin ingin ' + label.toLowerCase() + ' akun ini?')) return;
                    fetch('{{ url("backend/teachers/status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(r => r.json())
                    .then(function(res) {
                        if (res.success) {
                            location.reload();
                        } else {
                            alert(res.message || 'Gagal mengubah status.');
                        }
                    })
                    .catch(function() {
                        alert('Gagal mengubah status.');
                    });
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
