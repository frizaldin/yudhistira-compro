<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Edit Event Guru</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Event Guru</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Event Guru</h2>
                </div>
                <div class="card-body">
                    <form id="basic-form" action="{{ url('backend/event-teacher-hubs/update') }}" class="_form"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="form-group">
                            <label>Title (English)</label>
                            <input type="text" name="title" class="form-control" required value="{{ $item->title }}">
                        </div>
                        <div class="form-group">
                            <label>Judul (Indonesia)</label>
                            <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @if ($item->photo)
                                <img style="width: 100px; margin-top: 8px;" src="{{ asset($item->photo) }}" alt="" />
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ $item->date ?? '' }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <input type="time" name="start_time" class="form-control"
                                        value="{{ $item->start_time ? substr($item->start_time, 0, 5) : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Waktu Selesai</label>
                                    <input type="time" name="end_time" class="form-control"
                                        value="{{ $item->end_time ? substr($item->end_time, 0, 5) : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Point</label>
                            <input type="text" name="point" class="form-control" value="{{ $item->point ?? '' }}"
                                placeholder="Contoh: 100">
                        </div>
                        <div class="form-group">
                            <label>Link Meeting</label>
                            <input type="url" name="link_meeting" class="form-control"
                                value="{{ $item->link_meeting ?? '' }}" placeholder="https://...">
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
