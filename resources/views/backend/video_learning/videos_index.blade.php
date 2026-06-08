<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">{{ $title }}</h4>
            <div class="d-flex align-items-center">
                <a href="{{ $base_url }}" class="btn btn-secondary me-2"><i class="fa fa-arrow-left"></i> Kembali</a>
                <a href="{{ $base_url }}/{{ $learning->id }}/videos/add" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Video</a>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted">Urutan video: selesaikan video 1 baru video 2 terbuka. Sort order = urutan.</p>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr><th>#</th><th>Urutan</th><th>Title</th><th>Video URL</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($videos as $v)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $v->sort_order }}</td>
                                            <td>{{ $v->title }}</td>
                                            <td>
                                                @if($v->video_url)
                                                    @php $videoSrc = str_starts_with($v->video_url, 'http') ? $v->video_url : asset($v->video_url); @endphp
                                                    <a href="{{ $videoSrc }}" target="_blank">{{ basename($v->video_url) }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ $base_url }}/{{ $learning->id }}/videos/{{ $v->id }}/edit" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <form action="{{ $base_url }}/videos/delete" style="display:inline-block" method="post" onsubmit="return confirm('Hapus video ini?');">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $v->id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada video. Klik Tambah Video.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts>
