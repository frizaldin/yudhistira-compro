<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit {{ $title }}</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">{{ $title }}</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit {{ $title }}</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Digital Product</label>
                            <select name="digital_product_id" class="form-control">
                                <option value="">Pilih Digital Product</option>
                                @foreach ($digital_products as $digital_product)
                                    <option value="{{ $digital_product->id }}"
                                        {{ $item->digital_product_id == $digital_product->id ? 'selected' : '' }}>
                                        {{ $digital_product->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul (English)</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $item->title }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul }}">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi (English)</label>
                            <textarea name="description" id="description" class="form-control summernote" required>{{ $item->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi (Indonesia)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control summernote">{{ $item->deskripsi }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control">
                            @if ($item->file)
                                <img style="width: 100px; margin-top: 10px;" src="{{ asset($item->file) }}" />
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="order" value="{{ $item->order }}" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" name="visible" {{ $item->visible == 'yes' ? 'checked' : '' }}
                                    data-parsley-errors-container="#error-checkbox">
                                <span>Tampilkan ?</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <link rel="stylesheet" href="{{ asset('backend/assets/vendor/summernote/dist/summernote.css') }}" />
        <script src="{{ asset('backend/assets/vendor/summernote/dist/summernote.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
