<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;

class PostCategoryController extends BaseController
{
    //
    public function attachCategory(Request $request, $postId)
    {
        $post = Post::find($postId);
        $category = Category::find($request->input('category_id'));
        $post->categories()->attach($category->id);

        return response()->json(['message' => 'Category attached to post.']);
    }

    public function detachCategory(Request $request, $postId)
    {
        $post = Post::find($postId);
        $category = Category::find($request->input('category_id'));
        $post->categories()->detach($category->id);

        return response()->json(['message' => 'Category detached to post.']);
    }

    public function postsForCategory($categoryId)
    {
        $category = Category::find($categoryId);
        $posts = $category->posts;

        return response()->json(['posts' => $posts]);
    }
}
