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
                    <form id="basic-form" action="{{ $base_url . '/update' }}" class="_form" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="job" class="form-control" required
                                value="{{ $item->job }}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" required
                                value="{{ $item->email }}">
                        </div>

                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="file" class="form-control">
                            <img style="width: 100px;" src="{{ asset($item->file) }}" />
                        </div>

                        <div class="form-group">
                            <label for="">Grade</label>
                            <select name="grade" id="" class="form-control form-select custom-select">
                                <option value="1" {{ $item->grade == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ $item->grade == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ $item->grade == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ $item->grade == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ $item->grade == '5' ? 'selected' : '' }}>5</option>
                            </select>
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
