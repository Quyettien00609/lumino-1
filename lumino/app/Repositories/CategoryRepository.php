<?php

namespace App\Repositories;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    public function getProductsByCategory($id)
    {
        $category = $this->model->findOrFail($id);
        $products = $category->products;

        return response()->json([
            'category' => $category,
            'products' => $products
        ], 200);
    }
}
