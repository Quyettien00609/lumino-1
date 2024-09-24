<?php

namespace App\Repositories;
use App\Models\Product;
use App\Repositories\BaseRepository;
class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function showProductfeatured(){
        return $this->model->where('is_featured', true)
            ->where('is_active', true)
            ->get();
    }
    public function showProductnew(){
        return $this->model->where('is_new', true)
            ->where('is_active', true)
            ->get();
    }
}
