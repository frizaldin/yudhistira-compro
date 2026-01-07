<?php

use App\Models\CategoryMaterial;
use App\Models\Material;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Blog;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/logout', [\App\Http\Controllers\Backend\AuthController::class, 'logout']);

// Route login untuk middleware auth redirect
Route::get('/login', function () {
    return redirect('/backend/login');
})->name('login');

// Language switching routes
Route::get('/en', function () {
    session(['language' => 'en']);
    return redirect()->back()->with('language_changed', 'en');
})->name('language.en');

Route::get('/id', function () {
    session(['language' => 'id']);
    return redirect()->back()->with('language_changed', 'id');
})->name('language.id');


Route::middleware(['visitor'])->controller(\App\Http\Controllers\Frontend\PagesController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');

    Route::get('/service', 'service')->name('service');
    Route::get('/service/{url}', 'serviceDetail')->name('service');
    Route::post('/service/load-more-products', 'loadMoreProducts')->name('service.loadMoreProducts');

    Route::get('/digital-product', 'digital-product')->name('digital-product');
    Route::get('/digital-product/{url}', 'digitalProductDetail')->name('digital-product');

    Route::get('/event', 'event');
    Route::get('/event/category', 'event_category');
    Route::get('/event/{url}', 'eventDetail')->name('event');

    Route::get('/terms-conditions', 'termscondition');
    Route::get('/disclaimer', 'disclaimer');
    Route::get('/privacy-policy', 'privacypolicy');

    Route::get('/blog', 'blog')->name('article');
    Route::get('/blog/{url}', 'blogDetail')->name('blog');
    Route::get('/blog/category/{url}', 'articleCategory')->name('Blog Category');


    Route::get('/gallery', 'gallery')->name('gallery');

    Route::get('/catalogue', 'catalogue')->name('catalogue');

    Route::get('/product', 'product')->name('product');
    Route::get('/product/{url}', 'productDetail')->name('product');
    Route::get('/contact', 'contact')->name('kontak');
    Route::post('/contact', 'submitContact')->name('submitContact');

    Route::post('/post-comment', 'postComment')->name('postComment');

    Route::get('/post-event', 'postEvent')->name('postEvent');
    Route::get('/success-upload', 'successUpload')->name('successUpload');
    Route::post('/postEvent', 'eventPost')->name('eventPost');
});

// Teacher Hub Routes (outside visitor middleware)
Route::prefix('teacher-hub')->name('teacher.')->controller(\App\Http\Controllers\Frontend\TeacherHubController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'postLogin')->name('postLogin');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'postRegister')->name('postRegister');
    Route::post('/logout', 'logout')->name('logout');
    Route::middleware('auth:teacher')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });
});

// Route::controller(\App\Http\Controllers\Backend\AuthController::class)->group(function () {
//     Route::get('/login', 'index');
// });
Route::prefix('backend')->group(function () {
    // Auth
    Route::controller(\App\Http\Controllers\Backend\AuthController::class)->group(function () {
        Route::get('/login', 'index');
        Route::post('_login', 'login')->middleware('throttle:5,1');
    });

    Route::middleware('auth')->group(function () {
        Route::controller(\App\Http\Controllers\Backend\DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
        });

        Route::prefix('configuration')->name('configuration')->controller(\App\Http\Controllers\Backend\ConfigurationController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
            Route::get('/profile', 'profile');
            Route::post('/profile/update', 'profileUpdate');
            Route::post('/profile/change-password', 'changePasswordProfile');
            Route::get('/profile-more', 'profileMore');
            Route::post('/profile-more/update', 'profileMoreUpdate');
            Route::post('/profile-more/change-password', 'changePasswordProfileMore');
        });

        Route::prefix('product')->name('product')->controller(\App\Http\Controllers\Backend\ProductController::class)->group(function () {
            Route::get('/fetch-api-products', 'fetchApiProducts');
            Route::get('/fetch-categories', 'fetchCategories');
            Route::get('/fetch-subcategories', 'fetchSubcategories');
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('blog')->name('blog')->controller(\App\Http\Controllers\Backend\BlogController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('timeline')->name('timeline')->controller(\App\Http\Controllers\Backend\TimelineController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('branches')->name('branches')->controller(\App\Http\Controllers\Backend\BranchController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('events')->name('events')->controller(\App\Http\Controllers\Backend\EventController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('event_assets')->name('event_assets')->controller(\App\Http\Controllers\Backend\EventAssetController::class)->group(function () {
            Route::get('/{id}', 'index');
            Route::get('/add/{id}', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('counter')->name('counter')->controller(\App\Http\Controllers\Backend\CounterController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('biz_list')->name('biz_list')->controller(\App\Http\Controllers\Backend\BizListController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('slider')->name('slider')->controller(\App\Http\Controllers\Backend\SliderController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('slider_second')->name('slider_second')->controller(\App\Http\Controllers\Backend\SliderSecondController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('technology')->name('technology')->controller(\App\Http\Controllers\Backend\TechnologyController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('social_media')->name('social_media')->controller(\App\Http\Controllers\Backend\SocialMediaController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('marketing')->name('marketing')->controller(\App\Http\Controllers\Backend\MarketingController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });

        Route::prefix('superiority')->name('superiority')->controller(\App\Http\Controllers\Backend\SuperiorityController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('step')->name('step')->controller(\App\Http\Controllers\Backend\StepController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('portfolio')->name('portfolio')->controller(\App\Http\Controllers\Backend\PortfolioController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('team')->name('team')->controller(\App\Http\Controllers\Backend\TeamController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('subsidiary')->name('subsidiary')->controller(\App\Http\Controllers\Backend\SubsidiaryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('concept')->name('concept')->controller(\App\Http\Controllers\Backend\ConceptController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('foreword')->name('foreword')->controller(\App\Http\Controllers\Backend\ForewordController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('service')->name('service')->controller(\App\Http\Controllers\Backend\ServiceController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('digital-product')->name('digital_product')->controller(\App\Http\Controllers\Backend\DigitalProductController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('second-benefit')->name('second_benefit')->controller(\App\Http\Controllers\Backend\SecondBenefitController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{digital_product_id}', 'index');
            Route::get('/{digital_product_id}/add', 'add');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('third-benefit')->name('third_benefit')->controller(\App\Http\Controllers\Backend\ThirdBenefitController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{relation_id}', 'index');
            Route::get('/{relation_id}/add', 'add');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('subcategory')->name('subcategory')->controller(\App\Http\Controllers\Backend\SubcategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('testimonial')->name('testimonial')->controller(\App\Http\Controllers\Backend\TestimonialController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('partner')->name('partner')->controller(\App\Http\Controllers\Backend\PartnerController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('gallery')->name('gallery')->controller(\App\Http\Controllers\Backend\GalleryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('pricelist')->name('pricelist')->controller(\App\Http\Controllers\Backend\PricelistController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('faq')->name('faq')->controller(\App\Http\Controllers\Backend\PricelistController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('category')->name('category')->controller(\App\Http\Controllers\Backend\CategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('category-blog')->name('category_blog')->controller(\App\Http\Controllers\Backend\CategoryBlogController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('category-event')->name('category_event')->controller(\App\Http\Controllers\Backend\CategoryEventController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('category-catalogue')->name('category_catalogue')->controller(\App\Http\Controllers\Backend\CategoryCatalogueController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('benefit')->name('benefit')->controller(\App\Http\Controllers\Backend\BenefitController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{service_id}', 'index');
            Route::get('/{service_id}/add', 'add');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('page')->name('page')->controller(\App\Http\Controllers\Backend\PageController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('menu')->name('menu')->controller(\App\Http\Controllers\Backend\MenuController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('submenu')->name('submenu')->controller(\App\Http\Controllers\Backend\SubmenuController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('about')->name('about')->controller(\App\Http\Controllers\Backend\AboutController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });

        Route::prefix('headline')->name('headline')->controller(\App\Http\Controllers\Backend\HeadlineController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });

        Route::prefix('second_banner')->name('second_banner')->controller(\App\Http\Controllers\Backend\SecondBannerController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });
        Route::prefix('banner_contact')->name('banner_contact')->controller(\App\Http\Controllers\Backend\BannerContactController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });

        Route::prefix('banner_superiority')->name('banner_superiority')->controller(\App\Http\Controllers\Backend\BannerSuperiorityController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });
        Route::prefix('banner_testimonial')->name('banner_testimonial')->controller(\App\Http\Controllers\Backend\BannerTestimonialController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });
        Route::prefix('banner')->name('banner')->controller(\App\Http\Controllers\Backend\BannerController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/update', 'update');
        });

        Route::prefix('authority')->name('authority')->controller(\App\Http\Controllers\Backend\AuthorityController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('user')->name('user')->controller(\App\Http\Controllers\Backend\UserController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
            Route::get('/password/{id}', 'password');
            Route::post('/change-password', 'changePassword');
        });
        Route::prefix('catalogue')->name('catalogue')->controller(\App\Http\Controllers\Backend\CatalogueController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
        Route::prefix('sample-product')->name('sample_product')->controller(\App\Http\Controllers\Backend\SampleProductController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/add', 'add');
            Route::post('/store', 'store');
            Route::post('/delete', 'delete');
            Route::get('/edit/{id}', 'edit');
            Route::post('/update', 'update');
        });
    });
});


Route::get('/sitemap.xml', function () {
    $data['utama'] = ['/', 'blog', 'about', 'service', 'portfolio', 'contact'];

    $data['blog'] = Blog::orderBy('id', 'asc')->get()->map(function ($e) {
        $e['urls'] = asset('blog/' . $e->url);
        return $e;
    });

    $data['portfolio'] = Portfolio::orderBy('id', 'asc')->get()->map(function ($e) {
        $e['urls'] = asset('blog/' . $e->url);
        return $e;
    });

    $data['service'] = Service::orderBy('id', 'asc')->get()->map(function ($e) {
        $e['urls'] = asset('service/' . $e->url);
        return $e;
    });
    return response()->view('sitemap.index', $data)->header('Content-Type', 'text/xml');
});
