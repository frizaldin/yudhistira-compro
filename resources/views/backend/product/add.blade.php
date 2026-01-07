<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Tambah Produk</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item">Produk</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tambah Produk</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/product/store') }}" class="_form" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Subcategory</label>
                            <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                                <option value="">Pilih Subcategory</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        data-category-id="{{ $subcategory->category_id ?? '' }}"
                                        data-service-id="{{ $subcategory->category->service_id ?? '' }}">
                                        @if ($subcategory->category && $subcategory->category->service)
                                            {{ languageText($subcategory->category->service->title, $subcategory->category->service->judul) }}
                                            -
                                        @endif
                                        @if ($subcategory->category)
                                            {{ languageText($subcategory->category->title, $subcategory->category->judul) }}
                                            -
                                        @endif
                                        {{ languageText($subcategory->title, $subcategory->judul) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Service dan Category akan terisi otomatis</small>
                        </div>
                        <div class="form-group">
                            <label>Tipe Sample</label>
                            <select name="type_sample" id="type_sample" class="form-control" required>
                                <option value="internal">Master Data</option>
                                <option value="external">Link Eksternal</option>
                            </select>
                        </div>
                        <div class="form-group internal">
                            <label>Sample Product</label>
                            <select name="sample_product_id" id="sample_product_id" class="form-control">
                                <option value="">-- Pilih Sample Product --</option>
                                @foreach ($sample_products as $sample_product)
                                    <option value="{{ $sample_product->id }}">
                                        {{ languageText($sample_product->name, $sample_product->nama) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group external d-none">
                            <label>Sample Product</label>
                            <input name="link_sample" class="form-control" id="link_sample" type="text">
                        </div>
                        <input type="hidden" name="service_id" id="service_id" value="">
                        <input type="hidden" name="category_id" id="category_id" value="">
                        <div class="form-group">
                            <label>Sumber Data Product</label>
                            <select name="product_source" id="product_source" class="form-control" required>
                                <option value="manual">Buat Produk Manual</option>
                                <option value="external">Ambil dari Yudhistira Eccomerce</option>
                            </select>
                        </div>

                        <!-- Manual Product Fields -->
                        <div id="manual_product_fields">
                            <div class="form-group">
                                <label>Name (English)</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama (Indonesia)</label>
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Link</label>
                                <input type="text" name="link" id="link" class="form-control"
                                    placeholder="https://example.com">
                            </div>
                        </div>

                        <!-- External API Fields -->
                        <div id="external_product_fields" class="d-none">
                            <div class="form-group">
                                <label>Pilih Product dari API</label>
                                <select name="api_product" id="api_product" class="form-control">
                                    <option value="">Loading products...</option>
                                </select>
                                <small class="form-text text-muted">Memuat daftar product dari API...</small>
                            </div>
                            <input type="hidden" name="external_photo" id="external_photo">
                            <input type="hidden" name="external_category" id="external_category" value="">
                        </div>
                        <div class="form-group d-none">
                            <label for="overview">Preview Deskripsi</label>
                            <textarea name="overview" id="overview" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group d-none">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group d-none">
                            <label>Tag</label>
                            <textarea name="tags" class="form-control"></textarea>
                        </div>
                        <div class="form-group d-none">
                            <label>Meta Deskripsi</label>
                            <textarea name="meta_description" class="form-control"></textarea>
                        </div>
                        <div class="form-group d-none">
                            <label>Meta Keyword</label>
                            <textarea name="meta_keyword" class="form-control"></textarea>
                        </div>
                        <div class="form-group" id="manual_photo_group">
                            <label>Thumbnail</label>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <input type="hidden" name="type_photo" id="type_photo" value="internal">
                        <div class="form-group d-none">
                            <label>Icon</label>
                            <input type="file" name="icon" class="form-control">
                        </div>
                        <div class="form-group d-none">
                            <label>Jadwal Posting</label>
                            <input type="datetime-local" name="date" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" name="visible"
                                    data-parsley-errors-container="#error-checkbox">
                                <span>Tampilkan ?</span>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submit_btn">Simpan</button>
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
                // Inisialisasi CKEditor 4 untuk semua textarea dengan class summernote
                $('.summernote').each(function() {
                    CKEDITOR.replace(this, {
                        height: 300
                    });
                });

                var apiProducts = [];

                // Load products from API
                function loadApiProducts() {
                    $('#api_product').html('<option value="">Loading...</option>');

                    $.ajax({
                        url: '{{ url('backend/product/fetch-api-products') }}',
                        method: 'GET',
                        success: function(response) {
                            $('#api_product').html('<option value="">Pilih Product</option>');
                            apiProducts = [];

                            if (response.success && response.data && response.data.length > 0) {
                                response.data.forEach(function(product) {
                                    var title = product.title || '';
                                    var option = $('<option></option>')
                                        .attr('value', JSON.stringify(product))
                                        .text(title);
                                    $('#api_product').append(option);
                                    apiProducts.push(product);
                                });
                                $('#external_product_fields small').text(
                                    'Pilih product dari daftar di atas');
                            } else {
                                $('#api_product').html(
                                    '<option value="">Tidak ada product ditemukan</option>');
                                $('#external_product_fields small').text('Tidak ada product ditemukan');
                            }
                        },
                        error: function() {
                            $('#api_product').html('<option value="">Error loading products</option>');
                            $('#external_product_fields small').text('Error saat memuat products');
                        }
                    });
                }

                // Toggle antara manual dan external
                $('#product_source').on('change', function() {
                    var source = $(this).val();
                    if (source === 'external') {
                        $('#manual_product_fields').addClass('d-none');
                        $('#external_product_fields').removeClass('d-none');
                        $('#manual_photo_group').addClass('d-none');
                        $('#type_photo').val('external');
                        $('#name, #nama, #link').removeAttr('required');
                        $('#subcategory_id').removeAttr('required');

                        // Load products when switching to external
                        if (apiProducts.length === 0) {
                            loadApiProducts();
                        }
                    } else {
                        $('#manual_product_fields').removeClass('d-none');
                        $('#external_product_fields').addClass('d-none');
                        $('#manual_photo_group').removeClass('d-none');
                        $('#type_photo').val('internal');
                        $('#name, #nama').attr('required', 'required');
                        $('#subcategory_id').attr('required', 'required');
                        // Clear external fields
                        $('#api_product').val('').trigger('change');
                        $('#name, #nama, #link').val('');
                        $('#external_photo, #external_category').val('');
                    }
                });

                // Auto-fill fields when product selected
                $('#api_product').on('change', function() {
                    var selectedValue = $(this).val();
                    if (!selectedValue) {
                        $('#photo_preview').remove();
                        return;
                    }

                    try {
                        var product = JSON.parse(selectedValue);

                        // Fill form fields
                        $('#name').val(product.title || '');
                        $('#nama').val(product.title || '');
                        $('#link').val(product.url_goto_website || '');
                        $('#external_photo').val(product.file_url || product.file || '');
                        $('#external_category').val('');

                        // Show preview
                        if (product.file_url || product.file) {
                            var photoUrl = product.file_url || product.file;
                            if (!$('#photo_preview').length) {
                                $('<div id="photo_preview" class="mt-2"><label>Preview Photo:</label><br><img src="' +
                                    photoUrl +
                                    '" style="max-width: 200px; max-height: 200px;" class="img-thumbnail"></div>'
                                ).insertAfter('#external_product_fields');
                            } else {
                                $('#photo_preview img').attr('src', photoUrl);
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing product data:', e);
                    }
                });

                // Auto-fill service_id and category_id when subcategory is selected
                $('#subcategory_id').on('change', function() {
                    var selectedOption = $(this).find(':selected');
                    var categoryId = selectedOption.data('category-id') || '';
                    var serviceId = selectedOption.data('service-id') || '';

                    $('#category_id').val(categoryId);
                    $('#service_id').val(serviceId);
                });
                
                $('#type_sample').on('change', function(){
                    console.log('asdasd')
                    if($(this).val() == 'internal'){
                        $('.internal').removeClass('d-none');
                        $('.external').addClass('d-none');
                    }else{
                        $('.external').removeClass('d-none');
                        $('.internal').addClass('d-none');
                    }
                })
            });
        </script>
    </x-slot>
</x-backend.layouts>
