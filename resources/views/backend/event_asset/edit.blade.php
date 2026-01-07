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
                            <label>Tipe</label>
                            <select name="type" class="form-control" required id="type">
                                <option value="">Pilih Tipe</option>
                                <option value="photo" {{ $item->type == 'photo' ? 'selected' : '' }}>Photo</option>
                                <option value="video" {{ $item->type == 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                        </div>
                        <div class="form-group" id="photo"
                            style="display: {{ $item->type == 'photo' ? 'block' : 'none' }};">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control">
                            <img src="{{ asset($item->file) }}" alt="">
                        </div>
                        <div class="form-group" id="video"
                            style="display: {{ $item->type == 'video' ? 'block' : 'none' }};">
                            <label for="">Video</label>
                            <textarea name="video" class="form-control" rows="5">{{ $item->type == 'video' ? $item->file : '' }}</textarea>
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

        <script>
            $(document).ready(function() {
                $('#type').on('change', function() {
                    var type = $(this).val();
                    if (type == 'photo') {
                        $('#photo').show();
                        $('#video').hide();
                    } else if (type == 'video') {
                        $('#photo').hide();
                        $('#video').show();
                    }
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
