<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah Artikel Guru</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Artikel Guru</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tambah Artikel Guru</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/blog-teacher-hubs/store') }}" class="_form"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}">{{ $item->judul . ' - ' . $item->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul (English)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="overview">Overview / Pratinjau (English)</label>
                            <textarea name="overview" id="overview" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pratinjau">Pratinjau (Indonesia)</label>
                            <textarea name="pratinjau" id="pratinjau" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi (English)</label>
                            <textarea name="description" id="description" class="form-control summernote" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tag</label>
                            <textarea name="tags" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="photo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="visible" class="form-control" required>
                                <option value="no">Sembunyikan</option>
                                <option value="yes">Tampilkan</option>
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
                $('.summernote').each(function() {
                    CKEDITOR.replace(this, {
                        height: 300
                    });
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
