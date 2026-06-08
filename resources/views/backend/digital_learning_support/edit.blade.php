<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Digital Learning Support</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Digital Learning Support</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>Edit Digital Learning Support</h2></div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/digital-learning-supports/update') }}" class="_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required value="{{ $item->title }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Tipe Video</label>
                            <select name="video_tipe" id="video_tipe" class="form-control" required>
                                <option value="internal" {{ $item->video_tipe === 'internal' ? 'selected' : '' }}>Internal (Upload File)</option>
                                <option value="external" {{ $item->video_tipe === 'external' ? 'selected' : '' }}>External (Embed)</option>
                            </select>
                        </div>
                        <div class="form-group" id="field_file" style="display:{{ $item->video_tipe === 'internal' ? 'block' : 'none' }};">
                            <label>File Video</label>
                            <input type="file" name="file" class="form-control" accept="video/*">
                            @if($item->file)
                                <small class="text-muted">File saat ini: {{ basename($item->file) }}</small>
                            @endif
                        </div>
                        <div class="form-group" id="field_embed" style="display:{{ $item->video_tipe === 'external' ? 'block' : 'none' }};">
                            <label>Embed (URL atau kode iframe)</label>
                            <textarea name="embed" class="form-control" rows="3">{{ $item->embed ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
        <script>
            $('#video_tipe').on('change', function () {
                var v = $(this).val();
        
                $('#field_file').toggle(v === 'internal');
                $('#field_embed').toggle(v === 'external');
            });
        </script>
    </x-slot>
</x-backend.layouts>
