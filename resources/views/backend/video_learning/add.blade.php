<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box"><h4 class="page-title">Tambah {{ $title }}</h4></div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url . '/store' }}" class="_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title (English)</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Judul (Indonesia)</label>
                                <input type="text" name="judul" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Description (English)</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi (Indonesia)</label>
                                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Point (diberi setelah selesai semua video + quiz)</label>
                                <input type="number" name="point" class="form-control" value="0" min="0">
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
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
