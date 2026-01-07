<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Counter</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">Counter</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Counter</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/counter/update') }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}"
                                required>
                        </div>
                        <div class="form-group d-none">
                            <label>Subtitle (English)</label>
                            <input type="text" name="subtitle" class="form-control"
                                value="{{ $item->subtitle ?? '' }}">
                        </div>
                        <div class="form-group d-none">
                            <label>Subjudul (Indonesia)</label>
                            <input type="text" name="subjudul" class="form-control"
                                value="{{ $item->subjudul ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Angka</label>
                            <input type="text" name="number" class="form-control price"
                                value="{{ $item->number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Item (English)</label>
                            <input type="text" name="item" class="form-control" value="{{ $item->item ?? '' }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Satuan (Indonesia)</label>
                            <input type="text" name="satuan" class="form-control" value="{{ $item->satuan ?? '' }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="photo" class="form-control">
                            @if ($item->file)
                                <img style="width: 100px; margin-top: 10px;" src="{{ asset($item->file) }}" />
                            @endif
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
