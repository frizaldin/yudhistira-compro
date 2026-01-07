<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah {{ $title }}</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">{{ $title }}</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tambah {{ $title }}</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ $base_url . '/store' }}" class="_form" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Judul</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select name="type" id="type" class="form-select custom-select">
                                        <option value="">Pilih Tipe</option>
                                        <option value="custom">Custom</option>
                                        <option value="halaman">Halaman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Target</label>
                                    <select name="target" id="target" class="form-select custom-select">
                                        <option value="">Pilih Target</option>
                                        <option value="_SELF">_SELF</option>
                                        <option value="_BLANK">_BLANK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="wrapper">

                                </div>
                            </div>

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
                $('#type').on('change', function() {
                    if ($(this).val() == 'custom') {
                        $('.wrapper').html(`
                            <div class="form-group">
                                <label>Link</label>
                                <input name="link" class="form-control" required />
                            </div>
                        `)
                    } else {
                        $('.wrapper').html(`
                            <div class="form-group">
                                <label>Link</label>
                                <select name="link" id="link" class="form-control form-select" required>
                                    <option value="">Pilih Halaman</option>
                                </select>
                            </div>
                        `)

                        console.log('Runn');


                        $.each({!! $page !!}, function(index, value) {
                            $('#link').append('<option value="' + value.link + '">' + value.title +
                                '</option>');
                        });
                    }
                })
            })
        </script>
    </x-slot>
</x-backend.layouts>
