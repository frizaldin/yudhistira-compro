<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah Digital Learning Support</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Digital Learning Support</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>Tambah Digital Learning Support</h2></div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/digital-learning-supports/store') }}" class="_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tipe Video</label>
                            <select name="video_tipe" id="video_tipe" class="form-control" required>
                                <option value="internal">Internal (Upload File)</option>
                                <option value="external">External (Embed)</option>
                            </select>
                        </div>
                        <div class="form-group" id="field_file">
                            <label>File Video</label>
                            <input type="file" name="file" class="form-control" accept="video/*">
                        </div>
                        <div class="form-group" id="field_embed" style="display:none;">
                            <label>Embed (URL atau kode iframe)</label>
                            <textarea name="embed" class="form-control" rows="3" placeholder="URL atau kode embed (iframe)"></textarea>
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
