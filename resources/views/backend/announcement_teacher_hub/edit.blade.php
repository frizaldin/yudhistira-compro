<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Pengumuman Guru</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Pengumuman Guru</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Pengumuman Guru</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/announcement-teacher-hubs/update') }}" class="_form"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}" @selected($cat->id == $item->category_id)>
                                        {{ $cat->judul . ' - ' . $cat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul (English)</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="nama" class="form-control" value="{{ $item->nama ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="overview">Overview / Pratinjau (English)</label>
                            <textarea name="overview" id="overview" class="form-control summernote">{{ $item->overview ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="pratinjau">Pratinjau (Indonesia)</label>
                            <textarea name="pratinjau" id="pratinjau" class="form-control summernote">{{ $item->pratinjau ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi (English)</label>
                            <textarea name="description" id="description" class="form-control summernote" required>{{ $item->description ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote">{{ $item->deskripsi ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tag</label>
                            <textarea name="tags" class="form-control">{{ $item->tags ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Thumbnail</label>
                            <input type="file" name="photo" class="form-control">
                            @if ($item->photo)
                                <img src="{{ asset($item->photo) }}" alt="" width="100"
                                    style="width: 100px; margin-top: 8px;">
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ $item->date ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="visible" class="form-control" required>
                                <option value="no" {{ $item->visible == 'no' ? 'selected' : '' }}>Sembunyikan
                                </option>
                                <option value="yes" {{ $item->visible == 'yes' ? 'selected' : '' }}>Tampilkan
                                </option>
                            </select>
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
                $('.summernote').each(function() {
                    CKEDITOR.replace(this, {
                        height: 300
                    });
                });
            });
        </script>
    </x-slot>
</x-backend.layouts>
