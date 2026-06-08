<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Serial Code Member</h4>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('backend/serial-code-member/add') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Tambah Data
                </a>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Serial Code Member</li>
                </ol>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ url('backend/serial-code-member') }}" class="mb-3">
                            <div class="input-group" style="max-width: 400px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari kode member..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ url('backend/serial-code-member') }}"
                                        class="btn btn-outline-danger">
                                        <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Judul Buku</th>
                                        <th>Redeem Codes</th>
                                        <th>Status</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td>
                                                {{ ($collection->currentPage() - 1) * $collection->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <code class="user-select-all">{{ $item->code }}</code>
                                            </td>
                                            <td>
                                                {{ $item->book_json['title'] ?? '-' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ is_array($item->serial_code_ebook) ? count($item->serial_code_ebook) : 0 }} kode
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->used === 'yes')
                                                    <span class="badge bg-success">Sudah Dipakai</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum Dipakai</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                                            <td style="width:120px;">
                                                <a href="{{ url('backend/serial-code-member/edit/' . $item->id) }}"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ url('backend/serial-code-member/delete') }}"
                                                    style="display:inline-block"
                                                    class="deleteForm-{{ $item->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button"
                                                        onclick="return confirmation('{{ $item->id }}')"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{ $collection->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/delete/delete.js') }}"></script>
    </x-slot>
</x-backend.layouts>
