<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah {{ $title }}</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">{{ $title }}</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tambah {{ $title }}</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ $base_url . '/store' }}" class="_form" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Judul (English)</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ languageText($category->title, $category->judul) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group d-none">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control summernote">-</textarea>
                        </div>

                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, PNG, WEBP</small>
                        </div>

                        <div class="form-group">
                            <label>PDF</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf">
                            <small class="form-text text-muted">Format: PDF</small>
                        </div>

                        <div class="form-group d-none">
                            <label>URL</label>
                            <input type="text" name="url" class="form-control">
                            <small class="form-text text-muted">Kosongkan untuk auto-generate dari judul</small>
                        </div>

                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" name="visible" data-parsley-errors-container="#error-checkbox">
                                <span>Tampilkan ?</span>
                            </label>
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
