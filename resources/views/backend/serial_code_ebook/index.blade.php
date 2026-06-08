<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Serial Code E-Book</h4>
            <div class="d-flex align-items-center gap-2">
                <div>
                    <a href="{{ url('backend/serial-code-ebook/add') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i> Tambah Data
                    </a>
                </div>
                <div>
                    <a href="{{ url('backend/serial-code-ebook/import') }}" class="btn btn-success">
                        <i class="fa fa-upload mr-1"></i> Import CSV
                    </a>
                </div>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Serial Code E-Book</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ url('backend/serial-code-ebook') }}" class="mb-3">
                            <div class="input-group" style="max-width: 400px;">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari judul buku atau serial code..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ url('backend/serial-code-ebook') }}" class="btn btn-outline-danger">
                                        <i class="fa fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul Buku</th>
                                        <th>Serial Code</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">
                                                {{ ($collection->currentPage() - 1) * $collection->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->book_title ?? '-' }}</td>
                                            <td>
                                                <code>{{ $item->serial_code ?? '-' }}</code>
                                            </td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                                            <td style="width: 10%;">
                                                <a href="{{ url('backend/serial-code-ebook/edit/' . $item->id) }}"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ url('backend/serial-code-ebook/delete') }}"
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
                                            <td colspan="5" class="text-center text-muted">Tidak ada data.</td>
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
