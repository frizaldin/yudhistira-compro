<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah Reward Guru</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Reward Guru</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tambah Reward Guru</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/teacher-rewards/store') }}" class="_form"
                        method="post" enctype="multipart/form-data">
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
                            <label>Foto</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Point</label>
                            <input type="text" name="point" class="form-control" placeholder="Contoh: 100">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
