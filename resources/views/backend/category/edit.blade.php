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
                        <div class="form-group d-none">
                            <label>Tipe Kategori</label>
                            <select name="type" id="type" class="form-control form-select">
                                <option value="blog" {{ $item->type == 'blog' ? 'selected' : '' }}>Blog</option>
                                <option value="event" {{ $item->type == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="product" {{ $item->type == 'product' ? 'selected' : '' }}>Product
                                </option>
                            </select>
                        </div>
                        <div class="form-group" id="service_group">
                            <label>Service</label>
                            <select name="service_id" id="service_id" class="form-control">
                                <option value="">Pilih Service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                        {{ languageText($service->title, $service->judul) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hanya untuk kategori type "Product"</small>
                        </div>
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
                        <div class="form-group">
                            <label>Urutan</label>
                            <input type="number" name="order" class="form-control" value="{{ $item->order }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" name="file" class="form-control">
                            <img src="{{ asset($item->file) }}" alt="" width="100" style="width: 100px;">
                        </div>
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" {{ $item->visible == 'yes' ? 'checked' : '' }} name="visible"
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
        <script>
            $(document).ready(function() {
                // Toggle service dropdown berdasarkan type
                function toggleServiceField() {
                    var type = $('#type').val();
                    if (type === 'product') {
                        $('#service_group').show();
                        $('#service_id').attr('required', 'required');
                    } else {
                        $('#service_group').hide();
                        $('#service_id').removeAttr('required');
                    }
                }

                $('#type').on('change', toggleServiceField);
                toggleServiceField(); // Initialize on page load
            });
        </script>
    </x-slot>
</x-backend.layouts>
