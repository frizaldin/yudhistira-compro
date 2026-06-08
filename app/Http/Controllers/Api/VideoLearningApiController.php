<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VideoLearning;
use App\Models\VideoLearningCategory;
use App\Models\VideoLearningVideo;
use App\Models\VideoLearningVideoProgress;
use App\Models\VideoLearningQuizQuestion;
use App\Models\VideoLearningQuizOption;
use App\Models\VideoLearningQuizAnswer;
use App\Models\VideoLearningCompletion;
use App\Models\UserPoint;
use App\Models\TeacherBookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoLearningApiController extends Controller
{
    /**
     * GET /api/v1/video-learning-categories
     */
    public function categories(): JsonResponse
    {
        try {
            $items = VideoLearningCategory::orderBy('title')->get();
            return response()->json(['success' => true, 'data' => $items]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/video-learnings?category_id=1&per_page=15&status=all|incoming|following|done
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = VideoLearning::with('category')->orderBy('sort_order')->orderBy('id');

            if ($request->filled('category_id')) {
                $query->where('category_id', (int) $request->category_id);
            }

            $teacher = Auth::guard('api')->user();

            // Filter by status: all | incoming | following | done
            $statusFilter = $request->query('status', 'all');
            if ($teacher && $statusFilter !== 'all' && in_array($statusFilter, ['incoming', 'following', 'done'], true)) {
                if ($statusFilter === 'done') {
                    $doneIds = VideoLearningCompletion::where('user_id', $teacher->id)->pluck('video_learning_id')->toArray();
                    $query->whereIn('id', $doneIds);
                } elseif ($statusFilter === 'following') {
                    $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                        ->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)
                        ->pluck('reference_id')
                        ->toArray();
                    $query->whereIn('id', $followingIds);
                } else {
                    // incoming
                    $doneIds = VideoLearningCompletion::where('user_id', $teacher->id)->pluck('video_learning_id')->toArray();
                    $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                        ->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)
                        ->pluck('reference_id')
                        ->toArray();
                    $query->whereNotIn('id', $doneIds)->whereNotIn('id', $followingIds);
                }
            }

            $perPage = (int) $request->query('per_page', 15);
            $perPage = min(max($perPage, 1), 100);
            $paginator = $query->paginate($perPage);
            $collection = collect($paginator->items());

            // Status per item (mapping): done | following | incoming
            $doneIds = [];
            $followingIds = [];
            if ($teacher && $collection->isNotEmpty()) {
                $ids = $collection->pluck('id')->toArray();
                $doneIds = VideoLearningCompletion::where('user_id', $teacher->id)
                    ->whereIn('video_learning_id', $ids)
                    ->pluck('video_learning_id')
                    ->toArray();
                $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                    ->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)
                    ->whereIn('reference_id', $ids)
                    ->pluck('reference_id')
                    ->toArray();
            }

            $data = $collection->map(function ($item) use ($doneIds, $followingIds) {
                if (in_array($item->id, $doneIds)) {
                    $status = 'done';
                } elseif (in_array($item->id, $followingIds)) {
                    $status = 'following';
                } else {
                    $status = 'incoming';
                }
                $item->thumbnail = $item->thumbnail ? asset($item->thumbnail) : null;
                $item->status = $status;
                return $item;
            })->values();

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/video-learnings/{id}
     * Detail + progress user: is_unlocked, is_completed per video.
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $teacher = Auth::guard('api')->user();
            $learning = VideoLearning::with(['category', 'videos', 'quizQuestions.options'])
                ->find($id);

            if (!$learning) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Video learning tidak ditemukan.',
                ], 404);
            }

            $completedVideoIds = [];
            if ($teacher) {
                $completedVideoIds = VideoLearningVideoProgress::where('user_id', $teacher->id)
                    ->whereIn('video_learning_video_id', $learning->videos->pluck('id'))
                    ->pluck('video_learning_video_id')
                    ->toArray();
            }

            $videosWithProgress = $learning->videos->map(function ($video, $index) use ($completedVideoIds) {
                $prevVideosCompleted = true;
                if ($index > 0) {
                    $prevIds = $this->getPreviousVideoIds($video);
                    $prevVideosCompleted = count(array_intersect($prevIds, $completedVideoIds)) === count($prevIds);
                }
                $url = $video->video_url;
                if ($url && !str_starts_with($url, 'http')) {
                    $url = asset($url);
                }
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'video_url' => $url,
                    'sort_order' => $video->sort_order,
                    'is_unlocked' => $prevVideosCompleted,
                    'is_completed' => in_array($video->id, $completedVideoIds),
                ];
            });

            $quizQuestions = $learning->quizQuestions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'video_learning_video_id' => $q->video_learning_video_id,
                    'question_text' => $q->question_text,
                    'sort_order' => $q->sort_order,
                    'options' => $q->options->map(fn($o) => [
                        'id' => $o->id,
                        'option_text' => $o->option_text,
                        'sort_order' => $o->sort_order,
                    ]),
                ];
            });

            $isFullyCompleted = false;
            $completion = null;
            if ($teacher) {
                $completion = VideoLearningCompletion::where('user_id', $teacher->id)
                    ->where('video_learning_id', $id)
                    ->first();
                $isFullyCompleted = (bool) $completion;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $learning->id,
                    'category_id' => $learning->category_id,
                    'category' => $learning->category ? ['id' => $learning->category->id, 'title' => $learning->category->title] : null,
                    'title' => $learning->title,
                    'judul' => $learning->judul,
                    'description' => $learning->description,
                    'deskripsi' => $learning->deskripsi,
                    'thumbnail' => $learning->thumbnail ? asset($learning->thumbnail) : null,
                    'point' => $learning->point,
                    'videos' => $videosWithProgress,
                    'quiz_questions' => $quizQuestions,
                    'is_completed' => $isFullyCompleted,
                    'completed_at' => $completion?->completed_at?->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/video-learnings/complete-video
     * Body: { "video_learning_id": 1, "video_learning_video_id": 1 }
     * Tandai video selesai ditonton (unlock video berikutnya).
     */
    public function completeVideo(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
            }

            $request->validate([
                'video_learning_id' => 'required|integer|exists:video_learnings,id',
                'video_learning_video_id' => 'required|integer|exists:video_learning_videos,id',
            ]);

            $id = (int) $request->video_learning_id;
            $videoId = (int) $request->video_learning_video_id;
            $video = VideoLearningVideo::where('video_learning_id', $id)->find($videoId);
            if (!$video) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Video tidak ditemukan di learning ini.',
                ], 404);
            }

            $learning = VideoLearning::with('videos')->find($id);
            $prevIds = $this->getPreviousVideoIds($video);
            $completedIds = VideoLearningVideoProgress::where('user_id', $teacher->id)
                ->whereIn('video_learning_video_id', $learning->videos->pluck('id'))
                ->pluck('video_learning_video_id')
                ->toArray();

            if (count($prevIds) > 0 && count(array_intersect($prevIds, $completedIds)) !== count($prevIds)) {
                return response()->json([
                    'code' => 403,
                    'success' => false,
                    'message' => 'Selesaikan video sebelumnya terlebih dahulu.',
                ], 403);
            }

            VideoLearningVideoProgress::firstOrCreate(
                [
                    'user_id' => $teacher->id,
                    'video_learning_video_id' => $videoId,
                ],
                ['completed_at' => now()]
            );

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil ditandai selesai.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/video-learnings/quiz
     * Body: { "video_learning_id": 1, "answers": [ { "question_id": 1, "option_id": 3 }, ... ] }
     * Simpan jawaban quiz; jika semua video selesai + semua pertanyaan dijawab, beri point.
     */
    public function submitQuiz(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
            }

            $request->validate([
                'video_learning_id' => 'required|integer|exists:video_learnings,id',
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|integer|exists:video_learning_quiz_questions,id',
                'answers.*.option_id' => 'required|integer|exists:video_learning_quiz_options,id',
            ]);

            $id = (int) $request->video_learning_id;
            $learning = VideoLearning::with('videos', 'quizQuestions')->find($id);
            if (!$learning) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Video learning tidak ditemukan.',
                ], 404);
            }

            $questionIds = $learning->quizQuestions->pluck('id')->toArray();
            $completedVideoIds = VideoLearningVideoProgress::where('user_id', $teacher->id)
                ->whereIn('video_learning_video_id', $learning->videos->pluck('id'))
                ->pluck('video_learning_video_id')
                ->toArray();
            $allVideosCompleted = $learning->videos->count() === 0 || count($completedVideoIds) >= $learning->videos->count();

            DB::beginTransaction();
            try {
                foreach ($request->answers as $answer) {
                    $questionId = (int) $answer['question_id'];
                    $optionId = (int) $answer['option_id'];
                    if (!in_array($questionId, $questionIds, true)) {
                        continue;
                    }
                    $option = VideoLearningQuizOption::where('video_learning_quiz_question_id', $questionId)
                        ->find($optionId);
                    if (!$option) {
                        continue;
                    }
                    $isCorrect = (bool) $option->is_correct;
                    VideoLearningQuizAnswer::updateOrCreate(
                        [
                            'user_id' => $teacher->id,
                            'video_learning_quiz_question_id' => $questionId,
                        ],
                        [
                            'video_learning_quiz_option_id' => $optionId,
                            'is_correct' => $isCorrect,
                        ]
                    );
                }

                $answeredCount = VideoLearningQuizAnswer::where('user_id', $teacher->id)
                    ->whereIn('video_learning_quiz_question_id', $questionIds)
                    ->count();
                $allQuestionsAnswered = $answeredCount >= count($questionIds);

                $pointAwarded = 0;
                if ($allVideosCompleted && $allQuestionsAnswered && $learning->point > 0) {
                    $existing = VideoLearningCompletion::where('user_id', $teacher->id)
                        ->where('video_learning_id', $id)
                        ->first();
                    if (!$existing) {
                        VideoLearningCompletion::create([
                            'user_id' => $teacher->id,
                            'video_learning_id' => $id,
                            'point_awarded' => $learning->point,
                            'completed_at' => now(),
                        ]);
                        UserPoint::create([
                            'user_id' => $teacher->id,
                            'type'    => 'IN',
                            'point'   => $learning->point,
                            'source'  => 'video_learning',
                            'origin'  => [
                                'id'    => $learning->id,
                                'title' => $learning->title,
                                'judul' => $learning->judul,
                            ],
                        ]);
                        $pointAwarded = $learning->point;
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Jawaban berhasil disimpan.',
                    'point_awarded' => $pointAwarded,
                    'all_completed' => $allVideosCompleted && $allQuestionsAnswered,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getPreviousVideoIds(VideoLearningVideo $video): array
    {
        return VideoLearningVideo::where('video_learning_id', $video->video_learning_id)
            ->where('sort_order', '<', $video->sort_order)
            ->orderBy('sort_order')
            ->pluck('id')
            ->toArray();
    }
}
