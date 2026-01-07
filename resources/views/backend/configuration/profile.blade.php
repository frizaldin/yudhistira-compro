<x-backend.layouts>
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Profile</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('backend/dashboard') }}"><i
                                    class="fa fa-dashboard"></i></a>
                        </li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Profile</li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs-new">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Overview">Profile</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Settings">Password</a></li>
                    </ul>
                </div>
            </div>

            <div class="tab-content padding-0">

                <div class="tab-pane active" id="Overview">
                    <div class="card">
                        <div class="card-header">
                            <h2>Edit Akun</h2>
                        </div>
                        <div class="card-body">
                            <form id="basic-form" action="{{ url('backend/configuration/profile/update') }}"
                                class="_form" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" required
                                        value="{{ $item->name }}">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ $item->email }}">
                                </div>
                                <div class="form-group">
                                    <label>Photo Profile</label>
                                    <input type="file" name="photo" class="form-control">
                                    <img style="width: 75px;" class="mt-3" src="{{ asset($item->photo) }}" />
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="Settings">
                    <div class="card">
                        <div class="card-header">
                            <h2>Edit Password</h2>
                        </div>
                        <div class="card-body">
                            <form id="basic-form" action="{{ url('backend/configuration/profile/change-password') }}"
                                class="_form" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required>
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
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script>
    </x-slot>
</x-backend.layouts>
