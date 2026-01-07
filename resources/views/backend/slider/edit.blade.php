<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Slider</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">Slider</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Slider</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/slider/update') }}" enctype="multipart/form-data"
                        class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $item->title ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}">
                        </div>
                        <div class="form-group d-none">
                            <label>Subjudul</label>
                            <input type="text" name="subtitle" class="form-control" required
                                value="{{ $item->subtitle }}">
                        </div>
                        <div class="form-group d-none">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" required>{{ $item->description }}</textarea>
                        </div>
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Link Tombol </label>
                                    <input type="text" name="button_link_1" class="form-control" 
                                        value="{{ $item->button_link_1 }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Button Text 1 (English)</label>
                                    <input type="text" name="button_text_1" class="form-control" 
                                        value="{{ $item->button_text_1 ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tombol Teks 1 (Indonesia)</label>
                                    <input type="text" name="tombol_teks_1" class="form-control"
                                        value="{{ $item->tombol_teks_1 ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label>Link Tombol 2</label>
                                    <input type="text" name="button_link_2" class="form-control" 
                                        value="{{ $item->button_link_2 }}">
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <div class="form-group">
                                    <label>Teks Tombol 2</label>
                                    <input type="text" name="button_text_2" class="form-control" 
                                        value="{{ $item->button_text_2 }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="photo" class="form-control">
                            <img style="width: 100px;" src="{{ asset($item->photo) }}" />
                        </div>
                        <div class="form-group">
                            <label>Background</label>
                            <input type="file" name="background" class="form-control">
                            @if ($item->background)
                                <div class="mt-2">
                                    <img style="width: 200px; max-height: 100px; object-fit: cover;"
                                        src="{{ asset($item->background) }}" alt="Background Preview" />
                                </div>
                            @endif
                            <small class="text-muted">Upload background image untuk slider (opsional)</small>
                        </div>
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="order" class="form-control" value="{{ $item->order }}"
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
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
