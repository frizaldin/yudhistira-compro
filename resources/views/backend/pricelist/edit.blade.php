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
                            <label for="type">Tipe</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Pilih Tipe</option>
                                <option value="import" @selected($item->type == 'import')>Impor</option>
                                <option value="export" @selected($item->type == 'export')>Ekspor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $item->title }}">
                        </div>
                        <div class="form-group">
                            <label>Sub Judul</label>
                            <input type="text" name="subtitle" class="form-control" required
                                value="{{ $item->subtitle }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" required>{{ $item->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" name="price" class="form-control price" required
                                value="{{ $item->price }}">
                        </div>

                        <label>Benefit</label>
                        <div class="row wrapper ">
                            @forelse (json_decode($item->benefit) ?? [] as $row)
                                <div class="col-md-12 mb-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-10">
                                                <input type="text" name="benefit[]" value="{{ $row }}"
                                                    class="form-control" required value="">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-danger"
                                                    onclick="deleteBenefit($(this))">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 mb-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-10">
                                                <input type="text" name="benefit[]" class="form-control" required
                                                    value="">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-danger"
                                                    onclick="deleteBenefit($(this))">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-success addBenefit mb-3">Tambah Benefit</button>


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

        <script>
            $(document).ready(function() {
                $('.addBenefit').on('click', function() {
                    $('.wrapper').append(`
                        <div class="col-md-12 mb-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-10">
                                                <input type="text" name="benefit[]" class="form-control" required
                                                    value="">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-danger"
                                                    onclick="deleteBenefit($(this))">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    `)
                })
            })

            function deleteBenefit(e) {
                e.parent().parent().parent().parent().remove()
            }
        </script>
    </x-slot>
</x-backend.layouts>
