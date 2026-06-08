<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Dashboard</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <div class="row clearfix row-deck">
            @if (auth()->user()->authorities_id == 1)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Pengunjung Hari Ini</p>
                                    <h4 class="mt-1 mb-0 fw-medium">
                                        {{ number_format(\App\Models\Visitor::whereDate('created_at', today())->count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-info rounded mx-auto">
                                        <i class="iconoir-users fs-22 align-self-center mb-0 text-info"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Pengunjung Bulan Ini</p>
                                    <h4 class="mt-1 mb-0 fw-medium">
                                        {{ number_format(\App\Models\Visitor::whereMonth('created_at', today()->month)->count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-info rounded mx-auto">
                                        <i class="iconoir-calendar fs-22 align-self-center mb-0 text-info"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Pengunjung Tahun Ini</p>
                                    <h4 class="mt-1 mb-0 fw-medium">
                                        {{ number_format(\App\Models\Visitor::whereYear('created_at', today()->year)->count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-info rounded mx-auto">
                                        <i class="iconoir-calendar fs-22 align-self-center mb-0 text-info"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Total Pengunjung</p>
                                    <h4 class="mt-1 mb-0 fw-medium">{{ number_format(\App\Models\Visitor::count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-info rounded mx-auto">
                                        <i class="iconoir-users fs-22 align-self-center mb-0 text-info"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Slider</p>
                                    <h4 class="mt-1 mb-0 fw-medium">{{ number_format(\App\Models\Slider::count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-warning rounded mx-auto">
                                        <i class="iconoir-image fs-22 align-self-center mb-0 text-warning"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Produk</p>
                                    <h4 class="mt-1 mb-0 fw-medium">{{ number_format(\App\Models\Product::count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-warning rounded mx-auto">
                                        <i class="iconoir-box fs-22 align-self-center mb-0 text-warning"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Kategori</p>
                                    <h4 class="mt-1 mb-0 fw-medium">{{ number_format(\App\Models\Category::count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-success rounded mx-auto">
                                        <i class="iconoir-list fs-22 align-self-center mb-0 text-success"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">User</p>
                                    <h4 class="mt-1 mb-0 fw-medium">
                                        {{ number_format(\App\Models\User::where('authorities_id', 1)->count()) }}</h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-primary rounded mx-auto">
                                        <i class="iconoir-user fs-22 align-self-center mb-0 text-primary"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
                <div class="col-12"></div>
                @foreach (\App\Models\User::where('authorities_id', '!=', 1)->get() as $row)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card bg-corner-img">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-9">
                                        <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Artikel
                                            {{ $row->name }}</p>
                                        <h4 class="mt-1 mb-0 fw-medium">
                                            {{ number_format(\App\Models\Blog::where('created_by', $row->id)->count()) }}
                                        </h4>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3 align-self-center">
                                        <div
                                            class="d-flex justify-content-center align-items-center thumb-md border-dashed border-warning rounded mx-auto">
                                            <i class="iconoir-document fs-22 align-self-center mb-0 text-warning"></i>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                    </div>
                @endforeach
            @elseif (auth()->user()->authorities_id == 5)
                {{-- Statistik Teacher Hub (hanya untuk authority_id == 5) --}}
                @foreach ($teacher_hub_stats ?? [] as $stat)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card bg-corner-img">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-9">
                                        <p class="text-muted text-uppercase mb-0 fw-normal fs-13">{{ $stat['label'] }}
                                        </p>
                                        <h4 class="mt-1 mb-0 fw-medium">{{ number_format($stat['count']) }}</h4>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div
                                            class="d-flex justify-content-center align-items-center thumb-md border-dashed border-{{ $stat['color'] }} rounded mx-auto">
                                            <i
                                                class="{{ $stat['icon'] }} fs-22 align-self-center mb-0 text-{{ $stat['color'] }}"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Tabel ranking guru (hanya untuk authority_id == 5) --}}
                <div class="col-12 mt-4">
                    <h5 class="mb-3">Ranking Guru</h5>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Guru dengan Point Terbanyak</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 c_list">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th class="text-end">Total Point</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($top_teachers_by_points ?? [] as $idx => $t)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $t->name }}</td>
                                                <td><small class="text-muted">{{ $t->email }}</small></td>
                                                <td class="text-end fw-medium">
                                                    {{ number_format($t->total_points ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Guru Paling Aktif Ikut Event</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 c_list">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th class="text-end">Jumlah Event</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($top_teachers_by_events ?? [] as $idx => $t)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $t->name }}</td>
                                                <td><small class="text-muted">{{ $t->email }}</small></td>
                                                <td class="text-end fw-medium">
                                                    {{ number_format($t->events_count ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Guru Paling Aktif Video Learning</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 c_list">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th class="text-end">Video Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($top_teachers_by_video_learnings ?? [] as $idx => $t)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $t->name }}</td>
                                                <td><small class="text-muted">{{ $t->email }}</small></td>
                                                <td class="text-end fw-medium">
                                                    {{ number_format($t->video_completions_count ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Guru Paling Aktif Bersuara</h6>
                            <p class="text-muted small mb-0 mt-1">Open Ticket + Support Center + Creative Teacher</p>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 c_list">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($top_teachers_by_engagement ?? [] as $idx => $t)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $t->name }}</td>
                                                <td><small class="text-muted">{{ $t->email }}</small></td>
                                                <td class="text-end fw-medium">
                                                    {{ number_format($t->engagement_count ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card bg-corner-img">
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">
                                <div class="col-9">
                                    <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Artikel</p>
                                    <h4 class="mt-1 mb-0 fw-medium">
                                        {{ number_format(\App\Models\Blog::where('created_by', auth()->user()->authorities_id)->count()) }}
                                    </h4>
                                </div>
                                <!--end col-->
                                <div class="col-3 align-self-center">
                                    <div
                                        class="d-flex justify-content-center align-items-center thumb-md border-dashed border-warning rounded mx-auto">
                                        <i class="iconoir-document fs-22 align-self-center mb-0 text-warning"></i>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                </div>
            @endif


        </div>

    </div>
</x-backend.layouts>
