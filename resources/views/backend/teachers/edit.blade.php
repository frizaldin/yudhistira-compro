<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Edit Teacher</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ url('backend/teachers') }}">Teachers</a></li>
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
                    <h2>Edit Teacher - {{ $item->name }}</h2>
                </div>
                <div class="card-body">
                    <form id="form-edit-teacher" action="{{ url('backend/teachers/update') }}" class="_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NPSN <span class="text-danger">*</span></label>
                                    <input type="text" name="npsn" class="form-control" required maxlength="100" value="{{ old('npsn', $item->npsn ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}">
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required value="{{ old('email', $item->email) }}">
                                </div>
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $item->phone) }}">
                                </div>
                                <div class="form-group">
                                    <label>Domisili</label>
                                    <select class="form-control form-select" name="domisili" >
                                        @foreach($city as $row)
                                            <option value="{{$row->code}}" {{$item->domisili == $row->code ? 'selected' : ''}}>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nama Sekolah</label>
                                    <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $item->school_name) }}">
                                </div>
                                <div class="form-group">
                                    <label>Bidang Mengajar</label>
                                    <select class="form-control form-select" name="teaching_field" >
                                        @foreach($mapel as $row)
                                            <option value="{{$row->id}}" {{$item->teaching_field == $row->id ? 'selected' : ''}}>{{$row->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-select form-control" name="gender">
                                        <option value="">-- Pilih --</option>
                                        <option value="laki-laki" {{ old('gender', $item->gender) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('gender', $item->gender) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $item->birth_date?->format('Y-m-d')) }}">
                                </div>
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                    @if($item->photo)
                                        <img class="mt-2" style="max-width: 120px; border-radius: 8px;" src="{{ asset($item->photo) }}" alt="">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Maksimal Request</label>
                                    <input type="number" min="0" name="max_request" class="form-control" value="{{ old('max_request', $item->max_request) }}">
                                </div>
                                
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('backend/teachers') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
