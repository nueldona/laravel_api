<?php

namespace App\Http\Controllers\Api;

use auth;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostsResource;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class PostsController extends BaseController
{
    //
    public function index()
    {
        $post = Post::with('categories')->get();
        return $this->sendResponse(PostsResource::collection($post), 'Posts retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
            'status' => 'required',
            'published_at' => 'required',
            'last_modified_at' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $post = new Post([
            'title' => $input['title'],
            'content' => $input['content'],
            'status' => $input['status'],
            'user_id' => auth()->id(),
            'published_at' => $input['published_at'],
            'last_modified_at' => $input['last_modified_at'],
        ]);
        $post->save();
        $post->categories()->attach($input['category_id']);
        return $this->sendResponse(new PostsResource($post), 'Post created successfully.');
    }

    public function show($id)
    {
        $post = Post::with('categories')->findOrFail($id);
        if (is_null($post)) {
            return $this->sendError('post not found.');
        }
        return $this->sendResponse(new PostsResource($post), 'Post retrieved successfully.');
    }

    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'status' => 'required',
            'published_at' => 'required',
            'last_modified_at' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $post->title = $input['title'];
        $post->content = $input['content'];
        $post->user_id = auth()->id();
        $post->status = $input['status'];
        $post->published_at = $input['published_at'];
        $post->last_modified_at = $input['last_modified_at'];
        $post->save();
        $post->categories()->sync($request->input('category_ids', $input['category_id']));
        return $this->sendResponse(new PostsResource($post), 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->categories()->detach();
        $post->delete();
        $this->sendResponse([], 'Post deleted successfully.');
    }
}
