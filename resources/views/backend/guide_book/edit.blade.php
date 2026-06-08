<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit {{ $title }}</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">{{ $title }}</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h2>Edit {{ $title }}</h2></div>
                <div class="card-body">
                    <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->title }} / {{ $cat->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required value="{{ $item->title }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @if($item->thumbnail)
                                <img style="width: 80px; margin-top: 8px;" src="{{ asset($item->thumbnail) }}" alt="">
                            @endif
                        </div>
                        <div class="form-group">
                            <label>File PDF</label>
                            <input type="file" name="file" class="form-control" accept=".pdf,application/pdf">
                            @if($item->file)
                                <small class="text-muted">File saat ini: {{ basename($item->file) }}</small>
                            @endif
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
