<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">{{ $title }}</h4>
            <div class="d-flex align-items-center">
                <a href="{{ $base_url }}" class="btn btn-secondary me-2"><i class="fa fa-arrow-left"></i> Kembali</a>
                <a href="{{ $base_url }}/{{ $learning->id }}/quiz/add" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Pertanyaan</a>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted">Quiz per video: pilih video. Quiz per learning (setelah semua video): kosongkan video = "Quiz untuk seluruh learning".</p>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 c_list">
                                <thead>
                                    <tr><th>#</th><th>Pertanyaan</th><th>Scope</th><th>Opsi (jawaban benar)</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $q)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Str::limit($q->question_text, 60) }}</td>
                                            <td>
                                                @if($q->video_learning_video_id)
                                                    <span class="badge bg-info">Per Video: {{ $q->video?->title ?? '-' }}</span>
                                                @else
                                                    <span class="badge bg-success">Per Learning (setelah semua video)</span>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($q->options as $o)
                                                    {{ $o->option_text }}{{ $o->is_correct ? ' ✓' : '' }}@if(!$loop->last), @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ $base_url }}/{{ $learning->id }}/quiz/{{ $q->id }}/edit" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                <form action="{{ $base_url }}/quiz/delete" style="display:inline-block" method="post" onsubmit="return confirm('Hapus pertanyaan ini?');">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $q->id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada pertanyaan quiz.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts>
