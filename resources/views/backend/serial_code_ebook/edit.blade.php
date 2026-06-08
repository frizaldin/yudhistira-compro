<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Serial Code E-Book</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('backend/serial-code-ebook') }}">Serial Code E-Book</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Serial Code</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/serial-code-ebook/update') }}" class="_form"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group mb-3">
                            <label>Judul Buku</label>
                            <input type="text" name="book_title" class="form-control" maxlength="255"
                                value="{{ $item->book_title ?? '' }}" placeholder="Masukkan judul buku">
                        </div>
                        <div class="form-group mb-3">
                            <label>Serial Code <span class="text-danger">*</span></label>
                            <input type="text" name="serial_code" class="form-control" maxlength="255"
                                value="{{ $item->serial_code ?? '' }}" placeholder="Contoh: XXXX-XXXX-XXXX" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('backend/serial-code-ebook') }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
