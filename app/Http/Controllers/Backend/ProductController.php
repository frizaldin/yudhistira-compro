<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SampleProduct;
use App\Models\User;
use App\Models\UserPemasok;
use App\Models\UserPerusahaan;
use App\Services\ProductApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {

        $data['collection'] = Product::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('nama', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query, $search) {
            return $query->where('created_by',  Auth::user()->id);
        })->paginate(15);
        return view('backend.product.index', $data);
    }

    public function add()
    {
        // Load all subcategories (category_id and service_id will be auto-filled)
        $data['subcategories'] = Subcategory::where('visible', 'yes')
            ->with('category.service')
            ->get();
        $data['sample_products'] = SampleProduct::orderBy('name', 'ASC')->get();
        return view('backend.product.add', $data);
    }

    function store(Request $req)
    {
        try {
            // Auto-fill service_id and category_id from subcategory
            $subcategory = Subcategory::with('category')->find($req->subcategory_id);
            $serviceId = $subcategory && $subcategory->category ? $subcategory->category->service_id : null;
            $categoryId = $subcategory ? $subcategory->category_id : null;

            $create = Product::create([
                'service_id' => $serviceId,
                'category_id' => $categoryId,
                'subcategory_id' => $req->subcategory_id ?? null,
                'sample_product_id' => $req->sample_product_id ?? null,
                'name' => $req->name,
                'nama' => $req->nama,
                'type_sample' => $req->type_sample,
                'link_sample' => $req->link_sample,
                'link' => $req->link ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'product') : ($req->external_photo ?? ''),
                'type_photo' => $req->type_photo ?? 'internal',
                'description' => $req->description ?? '',
                'overview' => $req->overview ?? '',
                'tags' => $req->tags ?? '',
                'meta_description' => $req->meta_description ?? '',
                'date' => !empty($req->date) ? $req->date : null,
                'meta_keyword' => $req->meta_keyword ?? '',
                'url' => Str::slug($req->name ?? $req->nama),
                'visible' => $req->visible ? 'yes' : 'no',
                'created_by' => Auth::user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/product')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Product::find($id);
        // Load all subcategories (category_id and service_id will be auto-filled)
        $data['subcategories'] = Subcategory::where('visible', 'yes')
            ->with('category.service')
            ->get();
        $data['sample_products'] = SampleProduct::orderBy('name', 'ASC')->get();
        return view('backend.product.edit', $data);
    }

    function update(Request $req)
    {
        try {
            // Auto-fill service_id and category_id from subcategory
            $subcategory = Subcategory::with('category')->find($req->subcategory_id);
            $serviceId = $subcategory && $subcategory->category ? $subcategory->category->service_id : null;
            $categoryId = $subcategory ? $subcategory->category_id : null;

            $data = Product::find($req->id);
            $data->update([
                'service_id' => $serviceId,
                'category_id' => $categoryId,
                'subcategory_id' => $req->subcategory_id ?? null,
                'sample_product_id' => $req->sample_product_id ?? $data->sample_product_id,
                'name' => $req->name,
                'nama' => $req->nama,
                'type_sample' => $req->type_sample,
                'link_sample' => $req->link_sample,
                'link' => $req->link ?? $data->link,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'product') : ($req->external_photo ?? $data->photo),
                'type_photo' => $req->type_photo ?? $data->type_photo ?? 'internal',
                'description' => $req->description ?? $data->description,
                'overview' => $req->overview ?? $data->overview,
                'tags' => $req->tags ?? $data->tags,
                'meta_description' => $req->meta_description ?? $data->meta_description,
                'meta_keyword' => $req->meta_keyword ?? $data->meta_keyword,
                'date' => !empty($req->date) ? $req->date : $data->date,
                'url' => Str::slug($req->name ?? $req->nama),
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/product')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Product::find($req->id);
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    /**
     * Fetch categories by service_id
     */
    public function fetchCategories(Request $req)
    {
        try {
            $serviceId = $req->service_id;
            if (!$serviceId) {
                return [
                    'code' => 400,
                    'success' => false,
                    'message' => 'Service ID is required',
                    'data' => []
                ];
            }

            $categories = Category::where('visible', 'yes')
                ->where('type', 'product')
                ->where('service_id', $serviceId)
                ->get();

            return [
                'code' => 200,
                'success' => true,
                'data' => $categories
            ];
        } catch (\Throwable $th) {
            return [
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Fetch subcategories by category_id
     */
    public function fetchSubcategories(Request $req)
    {
        try {
            $categoryId = $req->category_id;
            if (!$categoryId) {
                return [
                    'code' => 400,
                    'success' => false,
                    'message' => 'Category ID is required',
                    'data' => []
                ];
            }

            $subcategories = Subcategory::where('visible', 'yes')
                ->where('category_id', $categoryId)
                ->get();

            return [
                'code' => 200,
                'success' => true,
                'data' => $subcategories
            ];
        } catch (\Throwable $th) {
            return [
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Fetch products from external API
     */
    public function fetchApiProducts(Request $req)
    {
        try {
            $apiService = new ProductApiService();
            $products = $apiService->getProducts();

            return [
                'code' => 200,
                'success' => true,
                'data' => $products
            ];
        } catch (\Throwable $th) {
            return [
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ];
        }
    }
}
