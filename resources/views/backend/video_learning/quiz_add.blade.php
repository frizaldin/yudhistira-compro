<x-backend.layouts>
    <div class="container-fluid">
        <div class="page-title-box">
            <a href="{{ $base_url }}/{{ $learning->id }}/quiz" class="btn btn-link p-0 me-2"><i class="fa fa-arrow-left"></i></a>
            <h4 class="page-title d-inline">Tambah Pertanyaan Quiz - {{ $learning->title }}</h4>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="basic-form" action="{{ $base_url }}/quiz/store" class="_form" method="post">
                            @csrf
                            <input type="hidden" name="video_learning_id" value="{{ $learning->id }}">
                            <div class="form-group">
                                <label>Scope</label>
                                <select name="video_learning_video_id" class="form-control">
                                    <option value="">Quiz untuk seluruh learning (setelah semua video selesai)</option>
                                    @foreach($learning->videos as $v)
                                        <option value="{{ $v->id }}">Per Video: {{ $v->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pertanyaan</label>
                                <textarea name="question_text" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Opsi 1 (jawaban)</label>
                                <input type="text" name="option_1" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Opsi 2</label>
                                <input type="text" name="option_2" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Opsi 3</label>
                                <input type="text" name="option_3" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Opsi 4</label>
                                <input type="text" name="option_4" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jawaban benar</label>
                                <select name="correct_option" class="form-control">
                                    <option value="option_1">Opsi 1</option>
                                    <option value="option_2">Opsi 2</option>
                                    <option value="option_3">Opsi 3</option>
                                    <option value="option_4">Opsi 4</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="js"><script type="text/javascript" src="{{ asset('js/post/post.js') }}"></script></x-slot>
</x-backend.layouts>
