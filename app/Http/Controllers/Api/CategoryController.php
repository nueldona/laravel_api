<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;
use App\Http\Controllers\Api\BaseController;

class CategoryController extends BaseController
{
    //
    public function index()
    {
        $category = Category::all();
        return $this->sendResponse(CategoriesResource::collection($category), 'Category retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category = new Category([
            'name' => $input['name'],
        ]);
        $category->save();

        return $this->sendResponse(new CategoriesResource($category), 'category created successfully.');
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('category not found.');
        }
        return $this->sendResponse(new CategoriesResource($category), 'Category retrieved successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $input = $request->all();
        if (is_null($category)) {
            return $this->sendError('category not found.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $category->name = $input['name'];
        $category->save();
        return $this->sendResponse(new CategoriesResource($category), 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        $this->sendResponse([], 'Category deleted successfully.');
    }
}
