<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\BannerContact;
use App\Models\Benefit;
use App\Models\Blog;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\CategoryBlog;
use App\Models\CategoryEvent;
use App\Models\CategoryCatalogue;
use App\Models\Catalogue;
use App\Models\Comment;
use App\Models\Configuration;
use App\Models\Counter;
use App\Models\DigitalProduct;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Timeline;
use App\Models\Branch;
use App\Models\SecondBenefit;
use App\Models\Subcategory;
use App\Services\ProductApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{
    public function index()
    {
        $data['about'] = About::firstOrFail();
        $data['slider'] = Slider::whereVisible('yes')->get();
        $data['counter'] = Counter::whereVisible('yes')->get();
        $data['service'] = Service::whereVisible('yes')->get();
        $data['digital_product'] = DigitalProduct::get();
        $data['configuration'] = Configuration::firstOrFail();
        // return $data['config']->digital_product_description;
        $products = Product::whereVisible('yes')->take(10)->get();

        // Mark external products from database
        $data['product'] = $products->map(function ($product) {
            if (($product->type_photo ?? 'internal') === 'external') {
                $product->is_api_product = true;
            }
            return $product;
        });

        $data['blog'] = Blog::whereVisible('publish')->take(4)->orderBy('date', 'desc')->get();
        return view('frontend.index', $data);
    }

    public function about()
    {
        $data['about'] = About::firstOrFail();
        $data['counter'] = Counter::whereVisible('yes')->get();
        $data['timeline'] = Timeline::whereVisible('yes')->get();
        $data['blog'] = Blog::whereVisible('publish')->take(4)->orderBy('date', 'desc')->get();

        // Map branches data for JavaScript
        $data['branches'] = Branch::whereVisible('yes')->get()->map(function ($branch) {
            return [
                'name' => $branch->name ?? '',
                'address' => $branch->address ?? '',
                'lat' => (float) ($branch->latitude ?? 0),
                'lng' => (float) ($branch->longitude ?? 0),
                'image' => $branch->photo ? asset($branch->photo) : asset('assets/img/Rectangle 1.png'),
            ];
        })->values();

        return view('frontend.about', $data);
    }

    public function serviceDetail($url)
    {
        $data['service'] = Service::where('url', $url)->first();
        $data['benefits'] = Benefit::where('service_id', $data['service']->id)->get();
        $data['category_product'] = Category::where('service_id', $data['service']->id)->whereType('product')->where('visible', 'yes')->get();

        $allProducts = Product::with('sampleProduct')
            ->where('visible', 'yes')
            ->where('service_id', $data['service']->id)
            ->get();

        // Handle external products (fetch from API)
        $apiService = new ProductApiService();
        $allApiProducts = $apiService->getProducts();

        $externalProducts = $allProducts->filter(function ($product) {
            return ($product->type_photo ?? 'internal') === 'external';
        });

        $products = $allProducts->map(function ($product) use ($allApiProducts, $apiService) {
            // If product is external, we don't need to fetch from API here
            // The photo and link are already stored in database
            // Just mark it as external for view handling
            if (($product->type_photo ?? 'internal') === 'external') {
                $product->is_api_product = true;
            }
            return $product;
        });

        $data['products'] = $products;
        $data['blog'] = Blog::whereVisible('publish')->take(4)->orderBy('date', 'desc')->get();
        return view('frontend.service-detail', $data);
    }

    public function loadMoreProducts(Request $request)
    {
        $serviceId = $request->service_id;
        $categoryId = $request->category_id;
        $subcategoryId = $request->subcategory_id;
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 6;
        $search = $request->search ?? '';

        $query = Product::with('sampleProduct')
            ->where('visible', 'yes')
            ->where('service_id', $serviceId)
            ->where('category_id', $categoryId)
            ->where('subcategory_id', $subcategoryId);

        // Add search filter if provided
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $allProducts = $query->get();

        // Mark external products
        $products = $allProducts->map(function ($product) {
            if (($product->type_photo ?? 'internal') === 'external') {
                $product->is_api_product = true;
            }
            return $product;
        });

        $totalProducts = $products->count();
        $productsToShow = $products->slice($offset, $limit);
        
        // Check if there are more products to load
        // If no products returned or we've reached the end, has_more should be false
        $hasMore = $productsToShow->count() > 0 && ($offset + $limit) < $totalProducts;

        $html = '';
        foreach ($productsToShow as $product) {
            $html .= view('frontend.partials.product-card', [
                'product' => $product
            ])->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
            'has_more' => $hasMore,
            'next_offset' => $offset + $limit,
            'count' => $productsToShow->count(),
            'total' => $totalProducts
        ]);
    }
    public function digitalProductDetail($url)
    {
        $data['digital_product'] = DigitalProduct::where('url', $url)->first();
        $data['second_benefit'] = SecondBenefit::where('digital_product_id', $data['digital_product']->id)->get();
        $data['blog'] = Blog::whereVisible('publish')->take(4)->orderBy('date', 'desc')->get();
        // return $data;
        return view('frontend.digital-product-detail', $data);
    }

    public function promo()
    {
        $data['galleries'] = Gallery::where('visible', 'yes')->get();
        return view('frontend.promo', $data);
    }

    public function event(Request $request)
    {
        $data['events'] = Event::with('category')->where('visible', 'publish')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->where(function ($query) {
                $query->orWhere('date', '<=', now());
            })
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $data['categories'] = CategoryEvent::withCount('events')->where('visible', 'yes')->get();
        $data['recent_events'] = Event::where('visible', 'publish')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        return view('frontend.event', $data);
    }

    public function eventDetail($url)
    {
        $data['event'] = Event::with(['category', 'user'])->where('url', $url)->where('visible', 'publish')->first();
        if (!$data['event']) {
            abort(404);
        }
        $data['config'] = Configuration::firstOrFail();
        $data['categories'] = CategoryEvent::withCount('events')->where('visible', 'yes')->get();
        $data['recent_events'] = Event::with('category')
            ->where('visible', 'publish')
            ->where('id', '!=', $data['event']->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.event-detail', $data);
    }

    public function event_category($url)
    {
        $data['category'] = CategoryEvent::where('url', $url)->first();
        if (!$data['category']) {
            abort(404);
        }
        $data['events'] = Event::with('category')
            ->where('visible', 'publish')
            ->when(request('search'), function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            })
            ->where('category_id', $data['category']->id)
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $data['categories'] = CategoryEvent::withCount('events')->where('visible', 'yes')->get();
        $data['recent_events'] = Event::where('visible', 'publish')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.event', $data);
    }

    public function postEvent()
    {
        $data['categories'] = CategoryEvent::withCount('events')->where('visible', 'yes')->get();
        return view('frontend.postEvent', $data);
    }

    public function product(Request $request)
    {
        $data['products'] = Product::where('visible', 'yes')->get();
        return view('frontend.product', $data);
    }
    public function productDetail($url)
    {
        $data['config'] = Configuration::firstOrFail();
        $data['product'] = Product::where('url', $url)->firstOrFail();
        return view('frontend.detailproduct', $data);
    }

    public function gallery()
    {
        $data['config'] = Configuration::firstOrFail();
        $data['galleries'] = Gallery::paginate(10);
        return view('frontend.gallery', $data);
    }

    public function blog(Request $request)
    {
        $data['blogs'] = Blog::with('category')->where('visible', 'publish')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            })
            ->when($request->category, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->whereDate('date', '<=', date('Y-m-d'))
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $data['categories'] = CategoryBlog::withCount('blogs')->where('visible', 'yes')->get();
        $data['recent_blogs'] = Blog::where('visible', 'publish')->orderBy('created_at', 'desc')->whereDate('date', '<=', date('Y/m/d'))->take(5)->get(['photo', 'created_at', 'name', 'url']);
        return view('frontend.blog', $data);
    }
    public function articleCategory($url)
    {
        $data['category'] = CategoryBlog::where('url', $url)->first();
        $data['blogs'] = Blog::select('photo', 'name', 'created_at', 'url')->where('visible', 'publish')->when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->where('category_id', $data['category']->id)->paginate(8);
        $data['categories'] = CategoryBlog::withCount('blogs')->where('visible', 'yes')->get();
        $data['recent_blogs'] = Blog::select('photo', 'name', 'created_at', 'url')->where('visible', 'publish')->orderBy('created_at', 'desc')->take(5)->get();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.blog', $data);
    }

    public function blogDetail($url)
    {
        $data['blog'] = Blog::with(['category', 'user'])->where('url', $url)->where('visible', 'publish')->first();
        if (!$data['blog']) {
            abort(404);
        }
        $data['config'] = Configuration::firstOrFail();
        $data['categories'] = CategoryBlog::withCount('blogs')->where('visible', 'yes')->get();
        $data['recent_blogs'] = Blog::with('category')
            ->where('visible', 'publish')
            ->where('id', '!=', $data['blog']->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.blog-detail', $data);
    }

    public function catalogue(Request $request)
    {
        $searchTerm = $request->search;

        // Get all services
        $services = Service::where('visible', 'yes')->get();

        // Get all categories with type='product' (general, tidak terikat service_id)
        $allCategories = Category::where('type', 'product')
            ->where('visible', 'yes')
            ->get()
            ->keyBy('id');

        // Get all subcategories (general, tidak terikat category_id)
        $allSubcategories = Subcategory::where('visible', 'yes')
            ->get()
            ->keyBy('id');

        // Get all products (will filter manually based on service_id, category_id, subcategory_id)
        $productsQuery = Product::where('visible', 'yes');
        if ($searchTerm) {
            $productsQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('nama', 'like', '%' . $searchTerm . '%');
            });
        }
        $allProducts = $productsQuery->get();

        // Initialize API Service
        $apiService = new ProductApiService();

        // Organize data by service
        $data['services'] = $services->map(function ($service) use ($allCategories, $allSubcategories, $allProducts, $apiService, $searchTerm) {
            // Filter products for this service
            $serviceProducts = $allProducts->filter(function ($product) use ($service) {
                return $product->service_id == $service->id;
            });

            // Mark external products from database
            $serviceProducts = $serviceProducts->map(function ($product) {
                if (($product->type_photo ?? 'internal') === 'external') {
                    $product->is_api_product = true;
                }
                return $product;
            });

            // Group products by category_id and subcategory_id
            $categoryGroups = [];
            foreach ($serviceProducts as $product) {
                if (!$product->category_id || !$product->subcategory_id) {
                    continue;
                }

                $categoryId = $product->category_id;
                $subcategoryId = $product->subcategory_id;

                if (!isset($categoryGroups[$categoryId])) {
                    $categoryGroups[$categoryId] = [];
                }
                if (!isset($categoryGroups[$categoryId][$subcategoryId])) {
                    $categoryGroups[$categoryId][$subcategoryId] = collect();
                }
                $categoryGroups[$categoryId][$subcategoryId]->push($product);
            }

            // Build categories with subcategories structure
            $serviceCategories = collect();
            foreach ($categoryGroups as $categoryId => $subcategoryGroups) {
                if (!isset($allCategories[$categoryId])) {
                    continue;
                }

                $category = clone $allCategories[$categoryId];
                $categorySubcategories = collect();

                foreach ($subcategoryGroups as $subcategoryId => $products) {
                    if (!isset($allSubcategories[$subcategoryId])) {
                        continue;
                    }

                    $subcategory = clone $allSubcategories[$subcategoryId];
                    $subcategory->products = $products;
                    $categorySubcategories->push($subcategory);
                }

                if ($categorySubcategories->count() > 0) {
                    $category->subcategories = $categorySubcategories;
                    $serviceCategories->push($category);
                }
            }

            $service->categories = $serviceCategories;
            return $service;
        })->filter(function ($service) {
            // Only keep services that have categories with products
            return $service->categories->count() > 0;
        });

        // Get all categories with their catalogues
        $categoriesQuery = CategoryCatalogue::where('visible', 'yes')->orderBy('order', 'ASC');

        // Filter by category if provided
        $categoryId = request('category');
        if ($categoryId) {
            $categoriesQuery->where('id', $categoryId);
        }

        $categories = $categoriesQuery->get();

        // Get catalogues grouped by category
        $cataloguesByCategory = [];
        foreach ($categories as $category) {
            $cataloguesQuery = Catalogue::where('visible', 'yes')
                ->where('category_id', $category->id);

            if ($searchTerm) {
                $cataloguesQuery->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('judul', 'like', '%' . $searchTerm . '%');
                });
            }

            $catalogues = $cataloguesQuery->orderBy('created_at', 'desc')->get();

            // Only add category if it has catalogues
            if ($catalogues->count() > 0) {
                $cataloguesByCategory[] = [
                    'category' => $category,
                    'catalogues' => $catalogues
                ];
            }
        }

        // Get catalogues without category (only if no category filter is applied)
        if (!$categoryId) {
            $uncategorizedQuery = Catalogue::where('visible', 'yes')
                ->whereNull('category_id');

            if ($searchTerm) {
                $uncategorizedQuery->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('judul', 'like', '%' . $searchTerm . '%');
                });
            }

            $uncategorizedCatalogues = $uncategorizedQuery->orderBy('created_at', 'desc')->get();

            if ($uncategorizedCatalogues->count() > 0) {
                $cataloguesByCategory[] = [
                    'category' => null,
                    'catalogues' => $uncategorizedCatalogues
                ];
            }
        }

        $data['cataloguesByCategory'] = $cataloguesByCategory;

        // Get all categories for filter
        $data['categories'] = CategoryCatalogue::where('visible', 'yes')->orderBy('order', 'ASC')->get();

        $data['search'] = $searchTerm;
        $data['selectedCategory'] = $categoryId;
        return view('frontend.catalogue', $data);
    }

    public function contact()
    {
        $data['config'] = Configuration::first();
        $data['banner_contact'] = BannerContact::first();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.contact', $data);
    }

    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'topic' => 'required|string',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => languageText('Please fill all required fields correctly and complete the captcha.', 'Mohon isi semua field yang diperlukan dengan benar dan selesaikan captcha.')
            ], 400);
        }

        // Verify Google reCAPTCHA
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaResponse = $request->input('g-recaptcha-response');

        if (!$recaptchaSecret) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => languageText('reCAPTCHA is not configured. Please contact administrator.', 'reCAPTCHA belum dikonfigurasi. Silakan hubungi administrator.')
            ], 500);
        }

        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => languageText('reCAPTCHA verification failed. Please try again.', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.')
            ], 400);
        }

        try {
            $config = Configuration::first();
            $recipientEmail = $config->email ?? env('MAIL_FROM_ADDRESS', 'hello@example.com');

            $topicLabels = [
                'pertanyaan' => languageText('General Question', 'Pertanyaan Umum'),
                'produk' => languageText('Product Question', 'Pertanyaan Produk'),
                'pemesanan' => languageText('Order', 'Pemesanan'),
                'kerjasama' => languageText('Partnership', 'Kerjasama'),
                'lainnya' => languageText('Other', 'Lainnya'),
            ];

            $topic = $topicLabels[$request->topic] ?? $request->topic;

            $emailBody = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2>" . languageText('New Contact Form Submission', 'Pesan Baru dari Form Kontak') . "</h2>
                    <p><strong>" . languageText('Name', 'Nama') . ":</strong> " . e($request->fullName) . "</p>
                    <p><strong>" . languageText('Email', 'Email') . ":</strong> " . e($request->email) . "</p>
                    <p><strong>" . languageText('Phone', 'Telepon') . ":</strong> " . e($request->phone) . "</p>
                    <p><strong>" . languageText('Topic', 'Topik') . ":</strong> " . e($topic) . "</p>
                    <p><strong>" . languageText('Message', 'Pesan') . ":</strong></p>
                    <p>" . nl2br(e($request->message)) . "</p>
                </body>
                </html>
            ";

            // Send email
            Mail::send([], [], function ($message) use ($request, $recipientEmail, $topic, $emailBody) {
                $message->to($recipientEmail)
                    ->subject(languageText('New Contact Form: ', 'Form Kontak Baru: ') . $topic)
                    ->from(env('MAIL_FROM_ADDRESS', 'noreply@example.com'), env('MAIL_FROM_NAME', 'Yudhistira'))
                    ->html($emailBody);
            });


            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => languageText('Thank you! Your message has been sent successfully.', 'Terima kasih! Pesan Anda telah terkirim dengan sukses.')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => languageText('Failed to send message. Please try again later.', 'Gagal mengirim pesan. Silakan coba lagi nanti.')
            ], 500);
        }
    }
    public function termscondition()
    {
        $data['config'] = Configuration::first();
        $data['banner_contact'] = BannerContact::first();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.terms-conditions', $data);
    }
    public function disclaimer()
    {
        $data['config'] = Configuration::first();
        $data['banner_contact'] = BannerContact::first();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.disclaimer', $data);
    }
    public function privacypolicy()
    {
        $data['config'] = Configuration::first();
        $data['banner_contact'] = BannerContact::first();
        $data['partners'] = Partner::where('visible', 'yes')->get();
        return view('frontend.privacy-policy', $data);
    }
    public function successUpload()
    {
        return view('frontend.successUpload');
    }

    public function postComment(Request $req)
    {
        $comment = new Comment();
        $comment->rel_table_id = $req->rel_table_id;
        $comment->rel_table = $req->rel_table;
        $comment->name = $req->name;
        $comment->email = $req->email;
        $comment->message = $req->message;
        $comment->save();
        return redirect()->back();
    }

    public function eventPost(Request $req)
    {
        try {
            Event::create([
                'name' => $req->name ?? '',
                'category_id' => $req->category ?? '',
                'description' => $req->description ?? '',
                'overview' => $req->overview ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Blog') : '',
                'tags' => $req->tags ?? '',
                'meta_description' => $req->meta_description ?? '',
                'meta_keyword' => $req->meta_keyword ?? '',
                'date' => $req->date ?? '',
                'time' => $req->time ?? '',
                'location' => $req->location ?? '',
                'speaker' => $req->speaker ?? '',
                'price' => $req->price ?? '',
                'price_description' => $req->price_description ?? '',
                'link' => $req->link ?? '',
                'city' => $req->city ?? '',
                'address' => $req->address ?? '',
                'url' => Str::slug($req->name) ?? '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('success-upload')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}
