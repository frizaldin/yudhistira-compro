<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">{{ $title }}</h4>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <a href="{{ $base_url . '/add' }}" class="btn btn-primary"><i class="fa fa-plus mr-1"></i> Tambah</a>
                </div>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" class="mb-3 row g-2">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto"><button type="submit" class="btn btn-primary">Cari</button></div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Thumbnail</th>
                                        <th>Kategori</th>
                                        <th>Title</th>
                                        <th>Judul</th>
                                        <th>Point</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($collection as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($collection->currentPage() - 1) * $collection->perPage() }}</td>
                                            <td>@if($item->thumbnail)<img style="width:50px" src="{{ asset($item->thumbnail) }}" alt="">@else - @endif</td>
                                            <td>{{ $item->category?->title ?? '-' }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->judul ?? '-' }}</td>
                                            <td>{{ $item->point }}</td>
                                            <td>
                                                <a href="{{ $base_url }}/{{ $item->id }}/videos" class="btn btn-sm btn-secondary" title="Video">Video</a>
                                                <a href="{{ $base_url }}/{{ $item->id }}/quiz" class="btn btn-sm btn-warning" title="Quiz">Quiz</a>
                                                <a href="{{ $base_url . '/edit/' . $item->id }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <form action="{{ $base_url . '/delete' }}" style="display:inline-block" class="deleteForm-{{ $item->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" onclick="return confirmation('{{ $item->id }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">{{ $collection->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>
    <x-slot name="js"><script type="text/javascript" src="{{ asset('js/delete/delete.js') }}"></script></x-slot>
</x-backend.layouts>
