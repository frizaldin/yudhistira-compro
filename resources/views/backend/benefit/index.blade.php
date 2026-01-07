<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">{{ $title }}@if($service) - {{ $service->title }}@endif</h4>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <a href="{{ $service_id ? $base_url . '/' . $service_id . '/add' : $base_url . '/add' }}" class="btn btn-primary"><i class="fa fa-plus mr-1"></i>
                        Tambah Data</a>
                </div>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a>
                        </li><!--end nav-item-->
                        @if($service)
                        <li class="breadcrumb-item"><a href="{{ url('backend/service') }}">Layanan</a></li>
                        @endif
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                                        <th>
                                            #
                                        </th>
                                        <th>Foto</th>
                                        <th>Judul</th>
                                        @if(!$service_id)
                                        <th>Service</th>
                                        @endif
                                        <th>Ditampilkan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($collection as $item)
                                        <tr id="data-{{ $item->id }}">
                                            <td style="width: 50px;">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <img style="width: 75px;" src="{{ asset($item->file) }}" />
                                            </td>
                                            <td>
                                                {{ $item->title }}
                                            </td>
                                            @if(!$service_id)
                                            <td>
                                                {{ $item->service ? $item->service->title : '-' }}
                                            </td>
                                            @endif
                                            <td>
                                                <span
                                                    class="badge {{ $item->visible == 'yes' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->visible == 'yes' ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                            <td style="width: 15%;">
                                                <a href="{{ $base_url . '/edit/' . $item->id }}"
                                                    class="btn btn-sm btn-info" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <form action="{{ $base_url . '/delete' }}" style="display:inline-block"
                                                    class="deleteForm-{{ $item->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button"
                                                        onclick="return confirmation('{{ $item->id }}')"
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
        <script>
            $('.sparkbar').sparkline('html', {
                type: 'bar'
            });
        </script>
    </x-slot>
</x-backend.layouts>
