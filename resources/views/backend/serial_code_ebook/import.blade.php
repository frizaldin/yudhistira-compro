<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Import Serial Code E-Book</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('backend/serial-code-ebook') }}">Serial Code E-Book</a>
                    </li>
                    <li class="breadcrumb-item active">Import</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Import dari File CSV / Excel</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong><i class="fa fa-info-circle me-1"></i> Format File:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Format yang didukung: <strong>CSV (.csv)</strong> dan <strong>Excel (.xlsx)</strong>.
                            </li>
                            <li>Baris pertama adalah <strong>header</strong> (akan dilewati otomatis).</li>
                            <li>Kolom A: <code>Book Title</code> — Judul buku (boleh kosong).</li>
                            <li>Kolom B: <code>Serial Number</code> — Serial code <strong>(wajib diisi)</strong>.</li>
                            <li>Kolom C: <code>Created At</code> — Tanggal dibuat dari file <strong>(mengikuti waktu di
                                    Excel/CSV)</strong>. Jika kosong, pakai waktu sekarang.</li>
                            <li>Baris yang kolom <code>Serial Number</code>-nya kosong akan dilewati.</li>
                        </ul>
                        <div class="mt-2">
                            <a href="{{ url('backend/serial-code-ebook/template') }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-download me-1"></i> Download Template CSV
                            </a>
                        </div>
                    </div>

                    <form id="basic-form" action="{{ url('backend/serial-code-ebook/import') }}" class="_form"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Pilih File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".csv,.xlsx" required>
                            <small class="text-muted">Format yang didukung: .csv, .xlsx</small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-upload me-1"></i> Import
                        </button>
                        <a href="{{ url('backend/serial-code-ebook') }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contoh Format CSV</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded" style="font-size: 13px;">Book Title,Serial Number,Created At
Informatika 3 SMA Kelas XII,FJ4U-QFPH-8SYX-WVIZ,2026-05-07 13:48:34
Matematika Kelas 7,A1B2-C3D4-E5F6,2026-05-08 09:00:00
Bahasa Indonesia,G7H8-I9J0-K1L2,</pre>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}" ignoreConfirmation="true"></script>
    </x-slot>
</x-backend.layouts>
