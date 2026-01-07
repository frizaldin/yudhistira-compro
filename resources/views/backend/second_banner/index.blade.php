<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Edit Banner </h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i
                                    class="fa fa-dashboard"></i></a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Banner </li>
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
                    <h2>Edit Banner </h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/second_banner/update') }}" class="_form"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $item->title }}">
                        </div>
                        <div class="form-group d-none">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control summernote">{{ $item->description }}</textarea>
                        </div>


                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control">
                            <img style="width: 100px;" src="{{ asset($item->file) }}" />
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" name="icon" class="form-control">
                            <img style="width: 100px;" src="{{ asset($item->icon) }}" />
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
                var legalityIndex = {{ count(json_decode($item->legality) ?? []) }};

                $('#add-legality').on('click', function() {
                    legalityIndex++;
                    $('#legality-container').append(`
                        <div class="row col-md-12">
                            <div class="col-md-10">
                                <input type="file" name="legality[]" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-legality">Hapus</button>
                            </div>
                        </div>
                    `);
                });

                $(document).on('click', '.remove-legality', function() {
                    $(this).closest('.row').remove();
                });
            });
        </script>

    </x-slot>
</x-backend.layouts>
