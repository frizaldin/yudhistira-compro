<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Reward Guru</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Reward Guru</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Reward Guru</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/teacher-rewards/update') }}" class="_form"
                        method="post" enctype="multipart/form-data">
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
                            <label>Foto</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @if ($item->photo)
                                <img style="width: 100px; margin-top: 8px;" src="{{ asset($item->photo) }}" alt="" />
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Point</label>
                            <input type="text" name="point" class="form-control" value="{{ $item->point ?? '' }}"
                                placeholder="Contoh: 100">
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
