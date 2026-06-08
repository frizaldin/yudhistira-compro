<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogTeacherHub;
use App\Models\CategoryTeacherHub;
use App\Models\AnnouncementTeacherHub;
use App\Models\CategoryAnnouncementTeacherHub;
use App\Models\EventTeacherHub;
use App\Models\EventTeacherHubQuestion;
use App\Models\EventQuestionAnswer;
use App\Models\UserPoint;
use App\Models\TeacherReward;
use App\Models\DigitalLearningSupport;
use Illuminate\Support\Facades\Auth;
use App\Models\CategoryGuideBook;
use App\Models\GuideBook;
use App\Models\CategoryEventTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class TeacherHubApiController extends Controller
{
    /**
     * Format photo URL ke full path
     */
    private function formatPhoto(?string $path): ?string
    {
        return $path ? asset($path) : null;
    }

    /**
     * GET /api/v1/blog-teacher-hubs
     */
    public function blogTeacherHubs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = BlogTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'name' => $item->name,
                'nama' => $item->nama,
                'photo' => $this->formatPhoto($item->photo),
                'overview' => $item->overview,
                'pratinjau' => $item->pratinjau,
                'description' => $item->description,
                'deskripsi' => $item->deskripsi,
                'tags' => $item->tags,
                'url' => $item->url,
                'date' => $item->date,
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
     * GET /api/v1/blog-teacher-hubs/{id}
     */
    public function blogTeacherHubDetail(int $id): JsonResponse
    {
        $item = BlogTeacherHub::where('visible', 'yes')->with('category')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
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
                'name' => $item->name,
                'nama' => $item->nama,
                'photo' => $this->formatPhoto($item->photo),
                'overview' => $item->overview,
                'pratinjau' => $item->pratinjau,
                'description' => $item->description,
                'deskripsi' => $item->deskripsi,
                'tags' => $item->tags,
                'url' => $item->url,
                'date' => $item->date,
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/category-teacher-hubs
     */
    public function categoryTeacherHubs(Request $request): JsonResponse
    {
        $query = CategoryTeacherHub::where('visible', 'yes')->orderBy('order');

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
                    'file' => $this->formatPhoto($item->file),
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
     * GET /api/v1/announcement-teacher-hubs
     */
    public function announcementTeacherHubs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = AnnouncementTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'name' => $item->name,
                'nama' => $item->nama,
                'photo' => $this->formatPhoto($item->photo),
                'overview' => $item->overview,
                'pratinjau' => $item->pratinjau,
                'description' => $item->description,
                'deskripsi' => $item->deskripsi,
                'tags' => $item->tags,
                'url' => $item->url,
                'date' => $item->date,
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
     * GET /api/v1/announcement-teacher-hubs/{id}
     */
    public function announcementTeacherHubDetail(int $id): JsonResponse
    {
        $item = AnnouncementTeacherHub::where('visible', 'yes')->with('category')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
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
                'name' => $item->name,
                'nama' => $item->nama,
                'photo' => $this->formatPhoto($item->photo),
                'overview' => $item->overview,
                'pratinjau' => $item->pratinjau,
                'description' => $item->description,
                'deskripsi' => $item->deskripsi,
                'tags' => $item->tags,
                'url' => $item->url,
                'date' => $item->date,
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/category-announcement-teacher-hubs
     */
    public function categoryAnnouncementTeacherHubs(Request $request): JsonResponse
    {
        $query = CategoryAnnouncementTeacherHub::where('visible', 'yes')->orderBy('order');

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
                    'file' => $this->formatPhoto($item->file),
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
     * GET /api/v1/event-teacher-hubs/{id}/questions
     * List pertanyaan untuk satu event.
     */
    public function eventTeacherHubQuestions(int $id): JsonResponse
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
     * GET /api/v1/event-teacher-hubs
     */
    public function eventTeacherHubs(Request $request): JsonResponse
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

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
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
     * Find / detail satu event teacher hub.
     */
    public function eventTeacherHubDetail(int $id): JsonResponse
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
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/blog-and-event-teacher-hubs
     */
    public function blogAndEventTeacherHubs(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 20);
        $search = $request->get('search');

        // Blog
        $blogQuery = BlogTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $blogQuery->where('category_id', $request->category_id);
        }

        if ($search) {
            $blogQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $blogItems = $blogQuery->get();
        $blogs = $blogItems->map(function ($item) {
            return array_merge(
                [
                    'type' => 'blog',
                    'id' => $item->id,
                    'category_id' => $item->category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'title' => $item->category->title,
                        'judul' => $item->category->judul,
                    ] : null,
                    'name' => $item->name,
                    'nama' => $item->nama,
                    'photo' => $this->formatPhoto($item->photo),
                    'overview' => $item->overview,
                    'pratinjau' => $item->pratinjau,
                    'description' => $item->description,
                    'deskripsi' => $item->deskripsi,
                    'tags' => $item->tags,
                    'url' => $item->url,
                    'date' => $item->date,
                    'created_at' => $item->created_at?->toIso8601String(),
                ],
                ['_sort_date' => $item->date ? strtotime($item->date) : 0]
            );
        });

        // Event
        $eventQuery = EventTeacherHub::with('category')->orderBy('date', 'desc');

        if ($search) {
            $eventQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $eventItems = $eventQuery->get();
        $events = $eventItems->map(function ($item) {
            return array_merge(
                [
                    'type' => 'event',
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
                    'created_at' => $item->created_at?->toIso8601String(),
                ],
                ['_sort_date' => $item->date ? strtotime($item->date) : 0]
            );
        });

        // Gabung dan urut by date desc, buang _sort_date
        $merged = $blogs->concat($events)
            ->sortByDesc('_sort_date')
            ->values()
            ->map(function ($item) {
                unset($item['_sort_date']);
                return $item;
            });

        $total = $merged->count();
        $data = $merged->take($perPage)->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * GET /api/v1/category-blog-and-event-teacher-hubs
     */
    public function categoryBlogAndEventTeacherHubs(Request $request): JsonResponse
    {
        // Ambil kategori blog
        $blogCategories = CategoryTeacherHub::orderBy('id')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'type' => 'blog',
                    'title' => $cat->title,
                    'judul' => $cat->judul,
                ];
            });

        // Ambil kategori event
        $eventCategories = CategoryEventTeacherHub::orderBy('id')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'type' => 'event',
                    'title' => $cat->title,
                    'judul' => $cat->judul,
                ];
            });

        // Merge dan return
        $categories = $blogCategories->concat($eventCategories)->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * GET /api/v1/teacher-rewards
     */
    public function teacherRewards(Request $request): JsonResponse
    {
        $query = TeacherReward::orderBy('id');

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
                    'photo' => $this->formatPhoto($item->photo),
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'point' => $item->point,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/digital-learning-supports
     */
    public function digitalLearningSupports(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = DigitalLearningSupport::with('user')->orderBy('id', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'judul' => $item->judul,
                'video_tipe' => $item->video_tipe,
                'file' => $item->video_tipe === 'internal' ? $this->formatPhoto($item->file) : null,
                'embed' => $item->video_tipe === 'external' ? $item->embed : null,
                'created_by' => $item->created_by,
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
     * GET /api/v1/digital-learning-supports/{id}
     */
    public function digitalLearningSupportDetail(int $id): JsonResponse
    {
        $item = DigitalLearningSupport::with('user')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'title' => $item->title,
                'judul' => $item->judul,
                'video_tipe' => $item->video_tipe,
                'file' => $item->video_tipe === 'internal' ? $this->formatPhoto($item->file) : null,
                'embed' => $item->video_tipe === 'external' ? $item->embed : null,
                'created_by' => $item->created_by,
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/category-guide-books
     */
    public function categoryGuideBooks(Request $request): JsonResponse
    {
        $query = CategoryGuideBook::where('visible', 'yes')->orderBy('order');

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
     * GET /api/v1/guide-books
     */
    public function guideBooks(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = GuideBook::with('category')->orderBy('id', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'thumbnail' => $this->formatPhoto($item->thumbnail),
                'title' => $item->title,
                'judul' => $item->judul,
                'file' => $this->formatPhoto($item->file),
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
     * GET /api/v1/guide-books/{id}
     */
    public function guideBookDetail(int $id): JsonResponse
    {
        $item = GuideBook::with('category')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
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
                'thumbnail' => $this->formatPhoto($item->thumbnail),
                'title' => $item->title,
                'judul' => $item->judul,
                'file' => $this->formatPhoto($item->file),
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/v1/category-event-teacher-hubs
     */
    public function categoryEventTeacherHubs(Request $request): JsonResponse
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
    public function submitEventQuestionAnswer(Request $request): JsonResponse
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

            return response()->json([
                'success' => true,
                'message' => 'Semua jawaban berhasil disimpan.' . ($pointValue > 0 ? " Anda mendapat {$pointValue} point." : ''),
                'data' => [
                    'point_earned' => $pointValue,
                ],
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
}
