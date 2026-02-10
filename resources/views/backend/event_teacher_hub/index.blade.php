<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Event Guru</h4>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <a href="{{ url('backend/event-teacher-hubs/add') }}" class="btn btn-primary"><i class="fa fa-plus mr-1"></i>
                        Tambah Data</a>
                </div>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Event Guru</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Point</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">{{ $loop->iteration }}</td>
                                            <td>
                                                @if($item->photo)
                                                    <img style="width: 100px;" src="{{ asset($item->photo) }}" alt="" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->title }} <br>
                                                <small class="text-muted">{{ $item->judul }}</small>
                                            </td>
                                            <td>{{ $item->date ? date('d M Y', strtotime($item->date)) : '-' }}</td>
                                            <td>
                                                @if($item->start_time || $item->end_time)
                                                    {{ $item->start_time ? date('H:i', strtotime($item->start_time)) : '-' }}
                                                    -
                                                    {{ $item->end_time ? date('H:i', strtotime($item->end_time)) : '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $item->point ?? '-' }}</td>
                                            <td>{{ $item->user?->name ?? '-' }}</td>
                                            <td style="width: 10%;">
                                                <a href="{{ url('backend/event-teacher-hubs/edit/' . $item->id) }}"
                                                    class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>
                                                <form action="{{ url('backend/event-teacher-hubs/delete') }}"
                                                    style="display:inline-block" class="deleteForm-{{ $item->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" onclick="return confirmation('{{ $item->id }}')"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                {{ $collection->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    <x-slot name="js">
        <script type="text/javascript" src="{{ asset('js/delete/delete.js') }}"></script>
    </x-slot>
</x-backend.layouts>
