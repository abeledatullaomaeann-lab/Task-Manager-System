<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $categories = Category::withCount([
            'tasks' => fn ($query) => $query->where('user_id', $userId),
        ])->get();

        return CategoryResource::collection($categories);
    }
}