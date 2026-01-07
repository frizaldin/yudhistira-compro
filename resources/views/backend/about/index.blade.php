<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit About</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item active">About</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit About</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/about/update') }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $item->title ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" required
                                value="{{ $item->judul ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Overview (English)</label>
                            <textarea name="overview" id="overview" class="form-control summernote">{{ $item->overview ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Pratinjau (Indonesia)</label>
                            <textarea name="pratinjau" id="pratinjau" class="form-control summernote">{{ $item->pratinjau ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Description (English)</label>
                            <textarea name="description" id="description" class="form-control summernote">{{ $item->description ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote">{{ $item->deskripsi ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Embed Video</label>
                            <textarea name="link_youtube" id="link_youtube" class="form-control" placeholder="Masukan Embed Video">{{ $item->link_youtube ?? '' }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control">
                            @if ($item->file)
                                <img style="width: 100px; margin-top: 10px;" src="{{ asset($item->file) }}" />
                            @endif
                        </div>

                        <div class="form-group d-none">
                            <label>Legalitas</label>
                            <div id="legality-container" class="row px-3">
                                @forelse (json_decode($item->legality) ?? [] as $key => $value)
                                    <div class="row mb-2 col-2 p-4">
                                        <img src="{{ asset($value) }}" style="width: 100%" alt="">
                                    </div>
                                @empty
                                    <div class="row col-md-12">
                                        <div class="col-md-10">
                                            <input type="file" name="legality[]" class="form-control" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-legality">Hapus</button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-primary mt-2" id="add-legality">Ganti
                                Legalitas</button>
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
