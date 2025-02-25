<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;


class CategoryService
{

    public function getCategories()
    {
        $categories = Category::get();
        return CategoryResource::collection($categories);
    }
}