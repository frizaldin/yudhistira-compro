<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box">
            <a href="{{ $base_url }}/{{ $learning->id }}/videos" class="btn btn-link p-0 me-2"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title d-inline">Edit Video - {{ $learning->title }}</h4>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url }}/videos/update" class="_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="form-group">
                                <label>Title Video</label>
                                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
                            </div>
                            <div class="form-group">
                                <label>File Video (MP4, MKV, WebM, AVI, MOV, FLV - max 200MB)</label>
                                <input type="file" name="video_file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,video/quicktime,video/x-flv,.mp4,.mkv,.webm,.avi,.mov,.flv">
                                @if($item->video_url)
                                    <small class="text-muted">Video saat ini: {{ basename($item->video_url) }}. Kosongkan = tidak ganti file.</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Urutan (sort_order)</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $item->sort_order }}" min="1">
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
