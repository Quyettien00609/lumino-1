<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return response()->json($categories, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $slug = Str::slug($request->input('name'));
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $booleanValue = boolval($request->input('is_active'));
        $category = $this->categoryRepository->create([
            'name' => $request->input('name'),
            'slug'=>$slug,
            'description' => $request->input('description'),
            'parent_id' => $request->input('parent_id'),
            'position' => $request->input('position'),
            'is_active' =>  $booleanValue,
            'image_path' => $imagePath,
        ]);

        $category->image = $imagePath ? url('storage/' . $imagePath) : null;
        $category->save();
        return response()->json([$category,$booleanValue], 201);
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($category, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $category = $this->categoryRepository->update($id, $data);

        if (!$category) {
            return response()->json(['message' => 'Category not found or not updated'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($category, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $deleted = $this->categoryRepository->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Category not found or not deleted'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
    public function getProductsByCategory($id)
    {
        try {
            $response = $this->categoryRepository->getProductsByCategory($id);
            return $response;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving the category'], 500);
        }
    }


}
