<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Edit Pengelaman</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i
                                    class="fa fa-dashboard"></i></a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Pengelaman</li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Pengelaman</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/experience/update') }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label for="overview">Preview Deskripsi</label>
                            <textarea name="overview" id="overview" class="form-control summernote" required>{{ $item->overview }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control summernote" required>{{ $item->description }}</textarea>
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
                            <label class="fancy-checkbox">
                                <input type="checkbox" {{ $item->visible == 'yes' ? 'checked' : '' }} name="visible"
                                    data-parsley-errors-container="#error-checkbox">
                                <span>Publish ?</span>
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
