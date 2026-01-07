<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit {{ $title }}</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">{{ $title }}</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit {{ $title }}</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Judul Utama</label>
                            <input type="text" name="main_title" readonly value="{{ $item->main_title }}"
                                class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Judul 1</label>
                            <input type="text" name="title_1" value="{{ $item->title_1 }}" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi 1</label>
                            <textarea name="description_1" id="description_1" class="form-control summernote">{{ $item->description_1 }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo 1</label>
                            <input type="file" name="file_1" class="form-control">
                            <img src="{{ asset($item->file_1) }}" style="width: 100px" alt="">
                        </div>


                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title_2" value="{{ $item->title_2 }}" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description_2" id="description_2" class="form-control summernote">{{ $item->description_2 }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="file_2" class="form-control">
                            <img src="{{ asset($item->file_2) }}" style="width: 100px" alt="">
                        </div>

                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title_3" value="{{ $item->title_3 }}" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description_3" id="description_3" class="form-control summernote">{{ $item->description_3 }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="file_3" class="form-control">
                            <img src="{{ asset($item->file_3) }}" style="width: 100px" alt="">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <link rel="stylesheet" href="{{ asset('backend/assets/vendor/summernote/dist/summernote.css') }}" />
        <script src="{{ asset('backend/assets/vendor/summernote/dist/summernote.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
