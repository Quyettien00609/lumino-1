<?php

namespace App\Http\Controllers\Api\Product;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'sku' => 'nullable|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'string',
            'is_featured' => 'string',
            'is_new' => 'string',
            'published_at' => 'nullable|date',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $slug = Str::slug($request->input('name'));
        $is_featured = filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN);
        $is_active = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        $is_new = filter_var($request->input('is_new'), FILTER_VALIDATE_BOOLEAN);
        $product = $this->productRepository->create([
            'name' => $request->input('name'),
            'slug'=>$slug,
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'discount_price' => $request->input('discount_price'),
            'quantity' => $request->input('quantity'),
            'sku' => $request->input('sku'),
            'barcode' => $request->input('barcode'),
            'category_id' => $request->input('category_id'),
            'images' => $imagePath,
            'is_active' =>$is_active,
            'is_featured' =>$is_featured,
            'is_new' => $is_new,
        ]);
        $id=auth()->id();
        $product->image = $imagePath ? url('storage/' . $imagePath) : null;
        $product->save();
        return response()->json([$product,$id]);
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
        $product->image_url = $product->image_path ? Storage::url($product->image_path) : null;
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        $product = $this->productRepository->update($id, [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image_path' => $imagePath,
        ]);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->image_url = $product->image_path ? Storage::url($product->image_path) : null;

        return response()->json($product);
    }

    // Xóa sản phẩm
    public function destroy($id)
    {
        $success = $this->productRepository->delete($id);
        if (!$success) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
        return response()->json(['message' => 'Product deleted successfully.']);
    }

    public function index()
    {
        $products = $this->productRepository->all();
        foreach ($products as $product) {
            $product->image_url = $product->image_path ? Storage::url($product->image_path) : null;
        }

        return response()->json($products);
    }
    public function showActiveAndFeaturedProducts()
    {
        $products = $this->productRepository->showProductfeatured();

        return response()->json($products, 200);
    }
    public function showActiveAndNewProducts()
    {
        $products = $this->productRepository->showProductnew();

        return response()->json($products, 200);
    }

}
