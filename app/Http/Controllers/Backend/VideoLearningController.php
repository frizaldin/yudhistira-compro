<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VideoLearning;
use App\Models\VideoLearningCategory;
use App\Models\VideoLearningVideo;
use App\Models\VideoLearningQuizQuestion;
use App\Models\VideoLearningQuizOption;
use Illuminate\Http\Request;

class VideoLearningController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Video Learning';
        $this->base_url = url('backend/video-learnings');
    }

    public function index()
    {
        $data['collection'] = VideoLearning::when(request('search'), function ($q, $search) {
            return $q->where('title', 'like', '%' . $search . '%')->orWhere('judul', 'like', '%' . $search . '%');
        })->when(request('category_id'), function ($q, $id) {
            return $q->where('category_id', $id);
        })->with('category')->orderBy('sort_order')->orderBy('id', 'desc')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = VideoLearningCategory::orderBy('title')->get();
        return view('backend.video_learning.index', $data);
    }

    public function add()
    {
        $data['categories'] = VideoLearningCategory::orderBy('title')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.add', $data);
    }

    public function store(Request $req)
    {
        try {
            VideoLearning::create([
                'category_id' => $req->category_id,
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'thumbnail' => $req->file('thumbnail') ? $this->uploadNormal($req->file('thumbnail'), 'video-learning') : null,
                'point' => (int) ($req->point ?? 0),
                'sort_order' => (int) ($req->sort_order ?? 0),
            ]);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = VideoLearning::with('category')->findOrFail($id);
        $data['categories'] = VideoLearningCategory::orderBy('title')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $item = VideoLearning::findOrFail($req->id);
            $item->update([
                'category_id' => $req->category_id ?? $item->category_id,
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'point' => (int) ($req->point ?? 0),
                'sort_order' => (int) ($req->sort_order ?? 0),
            ]);
            if ($req->file('thumbnail')) {
                $item->update(['thumbnail' => $this->uploadNormal($req->file('thumbnail'), 'video-learning')]);
            }
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            VideoLearning::findOrFail($req->id)->delete();
            return ['code' => 200, 'success' => true];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    // --- Videos (urutan 1,2,3...) ---
    public function videosIndex($id)
    {
        $data['learning'] = VideoLearning::findOrFail($id);
        $data['videos'] = VideoLearningVideo::where('video_learning_id', $id)->orderBy('sort_order')->get();
        $data['title'] = 'Video - ' . $data['learning']->title;
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.videos_index', $data);
    }

    public function videoAdd($id)
    {
        $data['learning'] = VideoLearning::findOrFail($id);
        $data['title'] = 'Tambah Video';
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.video_add', $data);
    }

    public function videoStore(Request $req)
    {
        try {
            $req->validate([
                'video_file' => 'required|file|mimes:mp4,mkv,webm,avi,mov,flv|max:204800', // max 200MB
            ]);
            $learning = VideoLearning::findOrFail($req->video_learning_id);
            $maxOrder = VideoLearningVideo::where('video_learning_id', $learning->id)->max('sort_order') ?? 0;
            $path = $this->uploadVideo($req->file('video_file'), 'video_learning');
            VideoLearningVideo::create([
                'video_learning_id' => $learning->id,
                'title' => $req->title,
                'video_url' => $path,
                'sort_order' => $maxOrder + 1,
            ]);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $learning->id . '/videos'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function videoEdit($learningId, $videoId)
    {
        $data['learning'] = VideoLearning::findOrFail($learningId);
        $data['item'] = VideoLearningVideo::where('video_learning_id', $learningId)->findOrFail($videoId);
        $data['title'] = 'Edit Video';
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.video_edit', $data);
    }

    public function videoUpdate(Request $req)
    {
        try {
            $item = VideoLearningVideo::findOrFail($req->id);
            $data = [
                'title' => $req->title,
                'sort_order' => (int) ($req->sort_order ?? $item->sort_order),
            ];
            if ($req->hasFile('video_file')) {
                $req->validate(['video_file' => 'file|mimes:mp4,mkv,webm,avi,mov,flv|max:204800']);
                if ($item->video_url && file_exists(public_path($item->video_url))) {
                    @unlink(public_path($item->video_url));
                }
                $data['video_url'] = $this->uploadVideo($req->file('video_file'), 'video_learning');
            }
            $item->update($data);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $item->video_learning_id . '/videos'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function videoDelete(Request $req)
    {
        try {
            $item = VideoLearningVideo::findOrFail($req->id);
            $learningId = $item->video_learning_id;
            if ($item->video_url && file_exists(public_path($item->video_url))) {
                @unlink(public_path($item->video_url));
            }
            $item->delete();
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $learningId . '/videos'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    // --- Quiz (pertanyaan: per video atau per learning) ---
    public function quizIndex($id)
    {
        $data['learning'] = VideoLearning::with('videos')->findOrFail($id);
        $data['questions'] = VideoLearningQuizQuestion::where('video_learning_id', $id)->with('options', 'video')->orderBy('sort_order')->get();
        $data['title'] = 'Quiz - ' . $data['learning']->title;
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.quiz_index', $data);
    }

    public function quizAdd($id)
    {
        $data['learning'] = VideoLearning::with('videos')->findOrFail($id);
        $data['title'] = 'Tambah Pertanyaan Quiz';
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.quiz_add', $data);
    }

    public function quizStore(Request $req)
    {
        try {
            $learning = VideoLearning::findOrFail($req->video_learning_id);
            $q = VideoLearningQuizQuestion::create([
                'video_learning_id' => $learning->id,
                'video_learning_video_id' => $req->video_learning_video_id ?: null,
                'question_text' => $req->question_text,
                'sort_order' => (int) ($req->sort_order ?? 0),
            ]);
            foreach (['option_1', 'option_2', 'option_3', 'option_4'] as $key) {
                if (empty($req->$key)) {
                    continue;
                }
                VideoLearningQuizOption::create([
                    'video_learning_quiz_question_id' => $q->id,
                    'option_text' => $req->$key,
                    'is_correct' => (int) ($req->correct_option === $key),
                    'sort_order' => (int) str_replace('option_', '', $key),
                ]);
            }
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $learning->id . '/quiz'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function quizEdit($learningId, $questionId)
    {
        $data['learning'] = VideoLearning::with('videos')->findOrFail($learningId);
        $data['item'] = VideoLearningQuizQuestion::where('video_learning_id', $learningId)->with('options')->findOrFail($questionId);
        $data['title'] = 'Edit Pertanyaan Quiz';
        $data['base_url'] = $this->base_url;
        return view('backend.video_learning.quiz_edit', $data);
    }

    public function quizUpdate(Request $req)
    {
        try {
            $q = VideoLearningQuizQuestion::findOrFail($req->id);
            $q->update([
                'video_learning_video_id' => $req->video_learning_video_id ?: null,
                'question_text' => $req->question_text,
                'sort_order' => (int) ($req->sort_order ?? 0),
            ]);
            $optionIds = $q->options->pluck('id')->toArray();
            foreach (['option_1', 'option_2', 'option_3', 'option_4'] as $idx => $key) {
                $optId = $optionIds[$idx] ?? null;
                $text = $req->$key ?? '';
                $isCorrect = (int) ($req->correct_option === $key);
                if ($optId) {
                    VideoLearningQuizOption::where('id', $optId)->update([
                        'option_text' => $text,
                        'is_correct' => $isCorrect,
                        'sort_order' => $idx + 1,
                    ]);
                } elseif ($text !== '') {
                    VideoLearningQuizOption::create([
                        'video_learning_quiz_question_id' => $q->id,
                        'option_text' => $text,
                        'is_correct' => $isCorrect,
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $q->video_learning_id . '/quiz'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function quizDelete(Request $req)
    {
        try {
            $q = VideoLearningQuizQuestion::findOrFail($req->id);
            $learningId = $q->video_learning_id;
            $q->delete();
            return ['code' => 200, 'success' => true, 'url' => $this->base_url . '/' . $learningId . '/quiz'];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}
