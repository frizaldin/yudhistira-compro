<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\EventTeacherHub;
use App\Models\EventTeacherHubQuestion;
use App\Models\EventQuestionAnswer;
use App\Models\EventCertificate;
use App\Models\VideoLearningQuizQuestion;
use App\Models\VideoLearningQuizAnswer;
use App\Models\UserPoint;
use App\Models\TeacherBookmark;
use App\Models\CategoryEventTeacherHub;
use App\Services\EventCertificateGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class EventTeacherHubApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/event-teacher-hubs
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = EventTeacherHub::with('category')->orderBy('date', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }

        $teacher = Auth::guard('api')->user();

        // Filter by status: all | incoming | outgoing | following | done
        $today = Carbon::today()->format('Y-m-d');
        $statusFilter = $request->get('status', 'all');
        if ($teacher && $statusFilter !== 'all' && in_array($statusFilter, ['incoming', 'outgoing', 'following', 'done'], true)) {
            if ($statusFilter === 'done') {
                $doneIds = EventCertificate::where('user_id', $teacher->id)->pluck('event_id')->toArray();
                $query->whereIn('id', $doneIds);
            } elseif ($statusFilter === 'following') {
                $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                    ->where('type', TeacherBookmark::TYPE_EVENT)
                    ->pluck('reference_id')
                    ->toArray();
                $query->whereIn('id', $followingIds);
            } elseif ($statusFilter === 'incoming') {
                $doneIds = EventCertificate::where('user_id', $teacher->id)->pluck('event_id')->toArray();
                $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                    ->where('type', TeacherBookmark::TYPE_EVENT)
                    ->pluck('reference_id')
                    ->toArray();
                $query->whereNotIn('id', $doneIds)->whereNotIn('id', $followingIds)->where('date', '>=', $today);
            } elseif ($statusFilter === 'outgoing') {
                $doneIds = EventCertificate::where('user_id', $teacher->id)->pluck('event_id')->toArray();
                $followingIds = TeacherBookmark::where('user_id', $teacher->id)
                    ->where('type', TeacherBookmark::TYPE_EVENT)
                    ->pluck('reference_id')
                    ->toArray();
                $query->whereNotIn('id', $doneIds)->whereNotIn('id', $followingIds)->where('date', '<', $today);
            }
        }

        $items = $query->paginate($perPage);
        $collection = $items->getCollection();

        $doneEventIds = [];
        $followingEventIds = [];
        if ($teacher && $collection->isNotEmpty()) {
            $eventIds = $collection->pluck('id')->toArray();
            $doneEventIds = EventCertificate::where('user_id', $teacher->id)
                ->whereIn('event_id', $eventIds)
                ->pluck('event_id')
                ->toArray();
            $followingEventIds = TeacherBookmark::where('user_id', $teacher->id)
                ->where('type', TeacherBookmark::TYPE_EVENT)
                ->whereIn('reference_id', $eventIds)
                ->pluck('reference_id')
                ->toArray();
        }

        $data = $collection->map(function ($item) use ($doneEventIds, $followingEventIds, $today) {
            if (in_array($item->id, $doneEventIds)) {
                $status = 'done';
            } elseif (in_array($item->id, $followingEventIds)) {
                $status = 'following';
            } else {
                $status = ($item->date && $item->date < $today) ? 'outgoing' : 'incoming';
            }
            return [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'photo' => $this->formatPhoto($item->photo),
                'title' => $item->title,
                'judul' => $item->judul,
                'date' => $item->date,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'point' => $item->point,
                'link_meeting' => $item->link_meeting,
                'completion_type' => $item->completion_type ?? 'quiz',
                'status' => $status,
                'created_at' => $item->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/event-teacher-hubs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $item = EventTeacherHub::with('category')->find($id);
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'photo' => $this->formatPhoto($item->photo),
                'title' => $item->title,
                'judul' => $item->judul,
                'date' => $item->date,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'point' => $item->point,
                'link_meeting' => $item->link_meeting,
                'completion_type' => $item->completion_type ?? 'quiz',
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/event-teacher-hubs/{id}/questions
     */
    public function questions(int $id): JsonResponse
    {
        $event = EventTeacherHub::find($id);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.'], 404);
        }
        $questions = EventTeacherHubQuestion::where('event_id', $id)->orderBy('id')->get();
        $data = $questions->map(function ($q) {
            return [
                'id' => $q->id,
                'event_id' => $q->event_id,
                'title' => $q->title,
                'judul' => $q->judul,
            ];
        });
        return response()->json([
            'success' => true,
            'data' => [
                'event' => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'judul' => $event->judul,
                    'point' => $event->point,
                ],
                'questions' => $data,
            ],
        ]);
    }

    /**
     * GET /api/v1/category-event-teacher-hubs
     */
    public function categories(Request $request): JsonResponse
    {
        $query = CategoryEventTeacherHub::where('visible', 'yes')->orderBy('order');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'url' => $item->url,
                    'order' => $item->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * POST /api/v1/event-teacher-hubs/answer
     */
    public function submitAnswer(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'event_id' => 'required|integer|exists:event_teacher_hubs,id',
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|integer',
                'answers.*.answer' => 'required|string|max:5000',
            ]);

            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harap Login Terlebih Dahulu',
                ], 402);
            }

            $eventId = (int) $request->event_id;
            $event = EventTeacherHub::find($eventId);
            $questionIds = EventTeacherHubQuestion::where('event_id', $eventId)->pluck('id')->sort()->values()->toArray();
            $totalQuestions = count($questionIds);

            if ($totalQuestions === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event ini belum memiliki pertanyaan.',
                ], 400);
            }

            $submitted = collect($request->answers);
            $submittedIds = $submitted->pluck('question_id')->map(fn($id) => (int) $id)->unique()->sort()->values()->toArray();

            if (count($submitted) !== $totalQuestions || count($submittedIds) !== $totalQuestions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua pertanyaan harus dijawab (tidak boleh ada yang kosong atau duplikat). Jumlah pertanyaan untuk event ini: ' . $totalQuestions,
                ], 400);
            }
            if ($submittedIds !== $questionIds) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pertanyaan tidak valid. Pastikan mengirim jawaban untuk semua pertanyaan event ini.',
                ], 400);
            }

            $alreadyAnswered = EventQuestionAnswer::where('event_id', $eventId)->where('user_id', $teacher->id)->count();
            if ($alreadyAnswered >= $totalQuestions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah submit jawaban untuk event ini.',
                ], 400);
            }

            foreach ($request->answers as $item) {
                $qid = (int) $item['question_id'];
                if (!in_array($qid, $questionIds, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pertanyaan tidak termasuk event ini.',
                    ], 400);
                }
                EventQuestionAnswer::create([
                    'event_id' => $eventId,
                    'question_id' => $qid,
                    'user_id' => $teacher->id,
                    'answer' => $item['answer'],
                ]);
            }

            $pointValue = (int) ($event->point ?? 0);
            if ($pointValue > 0) {
                UserPoint::create([
                    'user_id' => $teacher->id,
                    'type'    => 'IN',
                    'point'   => $pointValue,
                    'source'  => 'webinar',
                    'origin'  => [
                        'id'    => $event->id,
                        'title' => $event->title,
                        'judul' => $event->judul,
                        'date'  => $event->date,
                    ],
                ]);
            }

            EventCertificate::firstOrCreate(
                ['event_id' => $eventId, 'user_id' => $teacher->id]
            );

            $responseData = [
                'point_earned' => $pointValue,
                'certificate_available' => true,
            ];

            try {
                $generator = app(EventCertificateGeneratorService::class);
                $pdf = $generator->generatePdf($event, $teacher);
                $responseData['certificate_pdf_base64'] = base64_encode($pdf);
                $responseData['certificate_filename'] = 'sertifikat-event-' . $event->id . '.pdf';
            } catch (RuntimeException $e) {
                // Template/font belum diset; sertifikat tetap bisa diunduh lewat GET .../certificate nanti
            }

            return response()->json([
                'success' => true,
                'message' => 'Semua jawaban berhasil disimpan.' . ($pointValue > 0 ? " Anda mendapat {$pointValue} point." : '') . ' Sertifikat tersedia.',
                'data' => $responseData,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            $payload = [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
            if (config('app.debug')) {
                $payload['file'] = $e->getFile();
                $payload['line'] = $e->getLine();
            }
            return response()->json($payload, 500);
        }
    }

    /**
     * POST /api/v1/event-teacher-hubs/complete-via-link
     */
    public function completeViaLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => 'required|string',
            ]);

            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harap Login Terlebih Dahulu',
                ], 402);
            }

            $event = EventTeacherHub::where('completion_token', $request->token)
                ->where('completion_type', 'link')
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link penyelesaian tidak valid atau sudah kadaluarsa.',
                ], 404);
            }

            $hasCertificate = EventCertificate::where('event_id', $event->id)
                ->where('user_id', $teacher->id)
                ->exists();

            if ($hasCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah menyelesaikan event ini.',
                ], 400);
            }

            $pointValue = (int) ($event->point ?? 0);
            if ($pointValue > 0) {
                UserPoint::create([
                    'user_id' => $teacher->id,
                    'type'    => 'IN',
                    'point'   => $pointValue,
                    'source'  => 'webinar',
                    'origin'  => [
                        'id'    => $event->id,
                        'title' => $event->title,
                        'judul' => $event->judul,
                        'date'  => $event->date,
                    ],
                ]);
            }

            EventCertificate::firstOrCreate(
                ['event_id' => $event->id, 'user_id' => $teacher->id]
            );

            $responseData = [
                'point_earned' => $pointValue,
                'certificate_available' => true,
            ];

            try {
                $generator = app(EventCertificateGeneratorService::class);
                $pdf = $generator->generatePdf($event, $teacher);
                $responseData['certificate_pdf_base64'] = base64_encode($pdf);
                $responseData['certificate_filename'] = 'sertifikat-event-' . $event->id . '.pdf';
            } catch (RuntimeException $e) {
                // Template/font belum diset
            }

            return response()->json([
                'success' => true,
                'message' => 'Event berhasil diselesaikan.' . ($pointValue > 0 ? " Anda mendapat {$pointValue} point." : '') . ' Sertifikat tersedia.',
                'data' => $responseData,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            $payload = [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
            if (config('app.debug')) {
                $payload['file'] = $e->getFile();
                $payload['line'] = $e->getLine();
            }
            return response()->json($payload, 500);
        }
    }

    /**
     * GET /api/v1/event-teacher-hubs/certificate?id={event_id}
     * Unduh sertifikat event.
     * Sertifikat di-generate jika user sudah menjawab semua pertanyaan kuis.
     * Jika record EventCertificate belum ada tapi kuis sudah selesai → otomatis dibuat.
     */
    public function certificate(Request $request): JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        }

        $id = $request->query('id');
        if ($id === null || $id === '') {
            return response()->json([
                'success' => false,
                'message' => 'Parameter id (event_id) wajib diisi.',
            ], 422);
        }
        $id = (int) $id;
        if ($id < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter id tidak valid.',
            ], 422);
        }

        $event = EventTeacherHub::find($id);
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan.',
            ], 404);
        }

        // ── Cek kelayakan ──────────────────────────────────────────────────────
        // Cukup cek salah satu: punya EventCertificate ATAU sudah jawab semua soal.
        $hasCertificate = EventCertificate::where('event_id', $id)
            ->where('user_id', $teacher->id)
            ->exists();

        if (!$hasCertificate) {
            if (($event->completion_type ?? 'quiz') === 'link') {
                return response()->json([
                    'success' => false,
                    'message' => "Sertifikat hanya tersedia setelah Anda menyelesaikan event melalui link penyelesaian.",
                ], 403);
            }

            if ($request->type == 'webinar') {
                $totalQuestions = EventTeacherHubQuestion::where('event_id', $id)->count();
                $answeredCount  = EventQuestionAnswer::where('event_id', $id)
                    ->where('user_id', $teacher->id)
                    ->count();

                if ($totalQuestions > 0 && $answeredCount >= $totalQuestions) {
                    EventCertificate::firstOrCreate([
                        'event_id' => $id,
                        'user_id'  => $teacher->id,
                    ]);
                } else {
                    $progress = $totalQuestions > 0
                        ? "({$answeredCount}/{$totalQuestions} pertanyaan terjawab)"
                        : '(event tidak memiliki pertanyaan)';

                    return response()->json([
                        'success' => false,
                        'message' => "Sertifikat hanya tersedia setelah menyelesaikan semua pertanyaan kuis event ini. {$progress}",
                    ], 403);
                }
            } elseif ($request->type == 'video_learning') {
                $videoLearningId = (int) $request->video_learning_id;

                $questionIds    = VideoLearningQuizQuestion::where('video_learning_id', $videoLearningId)
                    ->pluck('id');
                $totalQuestions = $questionIds->count();
                $answeredCount  = VideoLearningQuizAnswer::where('user_id', $teacher->id)
                    ->whereIn('video_learning_quiz_question_id', $questionIds)
                    ->count();

                if ($totalQuestions > 0 && $answeredCount >= $totalQuestions) {
                    EventCertificate::firstOrCreate([
                        'event_id' => $id,
                        'user_id'  => $teacher->id,
                    ]);
                } else {
                    $progress = $totalQuestions > 0
                        ? "({$answeredCount}/{$totalQuestions} pertanyaan terjawab)"
                        : '(video learning tidak memiliki pertanyaan kuis)';

                    return response()->json([
                        'success' => false,
                        'message' => "Sertifikat hanya tersedia setelah menyelesaikan semua pertanyaan kuis video learning ini. {$progress}",
                    ], 403);
                }
            }
        }

        // ── Generate PDF ───────────────────────────────────────────────────────
        try {
            $generator = app(EventCertificateGeneratorService::class);
            $pdf = $generator->generatePdf($event, $teacher);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        $filename = 'sertifikat-event-' . $event->id . '-' . preg_replace('/[^a-z0-9_-]/i', '-', $teacher->name) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, $filename, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
