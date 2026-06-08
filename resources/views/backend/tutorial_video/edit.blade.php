<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box"><h4 class="page-title">Edit {{ $title }}</h4></div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="form-group">
                                <label>Title (English)</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                            </div>
                            <div class="form-group">
                                <label>Judul (Indonesia)</label>
                                <input type="text" name="judul" class="form-control" value="{{ $item->judul }}">
                            </div>
                            <div class="form-group">
                                <label>File Video (MP4, MKV, WebM, AVI, MOV, FLV - max 200MB)</label>
                                <input type="file" name="file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,video/quicktime,video/x-flv,.mp4,.mkv,.webm,.avi,.mov,.flv">
                                @if($item->file)
                                    <small class="text-muted">Saat ini: {{ basename($item->file) }}. Kosongkan = tidak ganti.</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Thumbnail (gambar)</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                @if($item->thumbnail)
                                    <small class="text-muted">Saat ini: <img src="{{ asset($item->thumbnail) }}" style="max-height:40px" alt=""> Kosongkan = tidak ganti.</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $item->sort_order }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js"><script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script></x-slot>
</x-backend.layouts>
