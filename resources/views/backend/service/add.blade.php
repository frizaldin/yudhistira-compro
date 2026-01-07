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
                    <form id="basic-form" action="{{ $base_url . '/store' }}" class="_form" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required>
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
                        <div class="form-group">
                            <label for="detail_information">Detail Information (English)</label>
                            <textarea name="detail_information" id="detail_information" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="detail_informasi">Detail Informasi (Indonesia)</label>
                            <textarea name="detail_informasi" id="detail_informasi" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_keyword">Meta Keyword</label>
                            <textarea name="meta_keyword" id="meta_keyword" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_description">Meta Deskripsi</label>
                            <textarea name="meta_description" id="meta_description" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="meta_tag">Label</label>
                            <textarea name="meta_tag" id="meta_tag" class="form-control summernote"></textarea>
                        </div>
                        <div class="row d-none">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="button_text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button Link</label>
                                    <input type="text" name="button_link" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Background Portrait</label>
                            <input type="file" name="bg_potrait" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Background Landscape</label>
                            <input type="file" name="bg_landscape" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>PDF Katalog</label>
                            <input type="file" name="pdf" class="form-control" accept="application/pdf">
                            <small class="text-muted">Upload file PDF katalog (format: PDF)</small>
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
