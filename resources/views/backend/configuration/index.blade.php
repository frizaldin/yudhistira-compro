<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Konfigurasi</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i
                                    class="fa fa-dashboard"></i></a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Konfigurasi</li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Konfigurasi</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/configuration/update') }}" class="_form"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="card">
                            <div class="card-header">
                                <h3>Informasi Website</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $item->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $item->title }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Logo</label>
                                            <input type="file" accept="image/*" name="logo" class="form-control">
                                            <img src="{{ asset($item->logo) }}" class="mt-3" alt=""
                                                width="100">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Favicon</label>
                                            <input type="file" accept="image/*" name="favicon" class="form-control">
                                            <img src="{{ asset($item->favicon) }}" class="mt-3" alt=""
                                                width="50">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Logo Footer</label>
                                            <input type="file" accept="image/*" name="logo_footer"
                                                class="form-control">
                                            <img src="{{ asset($item->logo_footer) }}" class="mt-3" alt=""
                                                width="100">
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-none">
                                        <label for="">Disclaimer</label>
                                        <textarea name="disclaimer"rows="5" class="form-control">{{ $item->disclaimer }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi Singkat Footer (English)</label>
                                            <textarea name="short_description" class="form-control" rows="5">{{ $item->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi Singkat Footer (Indonesia)</label>
                                            <textarea name="deskripsi_singkat" class="form-control" rows="5">{{ $item->deskripsi_singkat }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-none">
                                        <div class="form-group">
                                            <label>Teks Konsultasi</label>
                                            <textarea name="text_consult" class="form-control">{{ $item->text_consult }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3>Informasi Kontak</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Embed Peta</label>
                                            <textarea name="map" class="form-control" required>{{ $item->map }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="address" class="form-control" required>{{ $item->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $item->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>No. Telepon</label>
                                            <input type="text" name="notelp" class="form-control"
                                                value="{{ $item->notelp }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>No. Whatsapp</label>
                                            <input type="text" name="wa" class="form-control"
                                                value="{{ $item->wa }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3>Informasi Meta</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Meta Kata Kunci</label>
                                            <input type="text" name="meta_keyword" class="form-control"
                                                value="{{ $item->meta_keyword }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Meta Deskripsi</label>
                                            <textarea name="meta_description" class="form-control" required>{{ $item->meta_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Meta Abstrak</label>
                                            <textarea name="meta_abstract" class="form-control" required>{{ $item->meta_abstract }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Meta SEO</label>
                                            <textarea name="meta_seo" class="form-control" required>{{ $item->meta_seo }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pemilik Website</label>
                                            <input type="text" name="meta_author" class="form-control"
                                                value="{{ $item->meta_author }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Meta Judul</label>
                                            <input type="text" name="meta_title" class="form-control"
                                                value="{{ $item->meta_title }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Meta Pixel</label>
                                    <textarea name="meta_pixel" class="form-control">{{ $item->meta_pixel }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Google Analytics</label>
                                    <textarea name="google_analytics" class="form-control">{{ $item->google_analytics }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Tawkto</label>
                                    <textarea name="tawkto" class="form-control">{{ $item->tawkto }}</textarea>
                                </div>

                            </div>
                        </div>

                        <!-- Digital Product Section -->
                        <div class="card">
                            <div class="card-header">
                                <h3>Digital Product</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul (Indonesia)</label>
                                            <input type="text" name="digital_product_judul" class="form-control"
                                                value="{{ $item->digital_product_judul ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul (English)</label>
                                            <input type="text" name="digital_product_title" class="form-control"
                                                value="{{ $item->digital_product_title ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description (English)</label>
                                            <textarea name="digital_product_description" class="form-control" rows="5">{{ $item->digital_product_description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Deskripsi (Indonesia)</label>
                                            <textarea name="digital_product_deskripsi" class="form-control" rows="5">{{ $item->digital_product_deskripsi ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Section -->
                        <div class="card">
                            <div class="card-header">
                                <h3>Service</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title (English)</label>
                                            <input type="text" name="service_title" class="form-control"
                                                value="{{ $item->service_title ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul (Indonesia)</label>
                                            <input type="text" name="service_judul" class="form-control"
                                                value="{{ $item->service_judul ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description (English)</label>
                                            <textarea name="service_description" class="form-control" rows="5">{{ $item->service_description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi (Indonesia)</label>
                                            <textarea name="service_deskripsi" class="form-control" rows="5">{{ $item->service_deskripsi ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Section -->
                        <div class="card">
                            <div class="card-header">
                                <h3>Contact</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title (English)</label>
                                            <input type="text" name="contact_title" class="form-control"
                                                value="{{ $item->contact_title ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul (Indonesia)</label>
                                            <input type="text" name="contact_judul" class="form-control"
                                                value="{{ $item->contact_judul ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description (English)</label>
                                            <textarea name="contact_description" class="form-control" rows="5">{{ $item->contact_description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi (Indonesia)</label>
                                            <textarea name="contact_deskripsi" class="form-control" rows="5">{{ $item->contact_deskripsi ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
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
