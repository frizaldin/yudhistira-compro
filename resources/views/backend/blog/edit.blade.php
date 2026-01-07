<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Artikel</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">Artikel</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Artikel</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/blog/update') }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($category as $category)
                                    <option value="{{ $category->id }}" @selected($category->id == $item->category_id)>
                                        {{ $category->judul . ' - ' . $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama (English)</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label>Nama (Indonesia)</label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="overview">Preview Deskripsi (English)</label>
                            <textarea name="overview" id="overview" class="form-control summernote" required>{{ $item->overview }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="pratinjau">Pratinjau (Indonesia)</label>
                            <textarea name="pratinjau" id="pratinjau" class="form-control summernote">{{ $item->pratinjau ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi (English)</label>
                            <textarea name="description" id="description" class="form-control summernote" required>{{ $item->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote">{{ $item->deskripsi ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tag</label>
                            <textarea name="tags" class="form-control summernote" required>{{ $item->tags }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Meta Deskripsi</label>
                            <textarea name="meta_description" class="form-control summernote" required>{{ $item->meta_description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Meta Keyword</label>
                            <textarea name="meta_keyword" class="form-control summernote" required>{{ $item->meta_keyword }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="photo" class="form-control">
                            <img src="{{ asset($item->photo) }}" alt="" width="100" style="width: 100px;">
                        </div>
                        <div class="form-group">
                            <label>Jadwal Posting</label>
                            <input type="datetime-local" name="date" class="form-control" required
                                value="{{ $item->date }}">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="visible" class="form-control" required>
                                <option value="draft" {{ $item->visible == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ $item->visible == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="publish" {{ $item->visible == 'publish' ? 'selected' : '' }}>Publish</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Inisialisasi CKEditor 4 untuk semua textarea dengan class summernote
                $('.summernote').each(function() {
                    CKEDITOR.replace(this, {
                        height: 300
                    });
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
