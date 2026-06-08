<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box"><h4 class="page-title">Edit {{ $title }}</h4></div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}" {{ $item->category_id == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title (English)</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                            </div>
                            <div class="form-group">
                                <label>Judul (Indonesia)</label>
                                <input type="text" name="judul" class="form-control" value="{{ $item->judul }}">
                            </div>
                            <div class="form-group">
                                <label>Description (English)</label>
                                <textarea name="description" class="form-control" rows="3">{{ $item->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi (Indonesia)</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                @if($item->thumbnail)<small class="text-muted">Current: <img src="{{ asset($item->thumbnail) }}" style="max-height:40px"></small>@endif
                            </div>
                            <div class="form-group">
                                <label>Point</label>
                                <input type="number" name="point" class="form-control" value="{{ $item->point }}" min="0">
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $item->sort_order }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js"><script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script></x-slot>
</x-backend.layouts>
