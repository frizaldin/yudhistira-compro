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
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>URL (Slug)</label>
                            <input type="text" name="url" id="url" class="form-control" readonly>
                            <small class="form-text text-muted">URL akan otomatis terisi dari Title</small>
                        </div>

                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (English)</label>
                            <textarea name="description" id="description" class="form-control summernote"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Button Text 1 (English)</label>
                                    <input type="text" name="button_text_1" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tombol Teks 1 (Indonesia)</label>
                                    <input type="text" name="tombol_teks_1" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Button Link 1</label>
                                    <input type="text" name="button_link_1" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Button Text 2 (English)</label>
                                    <input type="text" name="button_text_2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Button Teks 2 (Indonesia)</label>
                                    <input type="text" name="button_teks_2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Button Link 2</label>
                                    <input type="text" name="button_link_2" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="iframe_youtube">Iframe YouTube</label>
                            <textarea name="iframe_youtube" id="iframe_youtube" class="form-control" rows="3"
                                placeholder="Paste iframe YouTube code here"></textarea>
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

                // Auto-generate URL from title
                function slugify(text) {
                    return text.toString().toLowerCase()
                        .replace(/\s+/g, '-')           // Replace spaces with -
                        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                        .replace(/^-+/, '')             // Trim - from start of text
                        .replace(/-+$/, '');            // Trim - from end of text
                }

                $('#title').on('input', function() {
                    var title = $(this).val();
                    $('#url').val(slugify(title));
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>

