<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box">
            <a href="{{ $base_url }}/{{ $learning->id }}/videos" class="btn btn-link p-0 me-2"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title d-inline">Tambah Video - {{ $learning->title }}</h4>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url }}/videos/store" class="_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="video_learning_id" value="{{ $learning->id }}">
                            <div class="form-group">
                                <label>Title Video</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>File Video (MP4, MKV, WebM, AVI, MOV, FLV - max 200MB)</label>
                                <input type="file" name="video_file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,video/quicktime,video/x-flv,.mp4,.mkv,.webm,.avi,.mov,.flv" required>
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
