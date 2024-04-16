<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(int $post)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            return response()->json('Post not found', Response::HTTP_NOT_FOUND);
        }
        return CommentResource::collection($post->comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommentRequest $request, int $post)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            return response()->json('Post not found', Response::HTTP_NOT_FOUND);
        }

        $validatedInput = $request->validated();
        $validatedInput['user_id'] = Auth::user()->id;
        $validatedInput['post_id'] = $post->id;
        $comment = Comment::create($validatedInput);
        
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $post, int $comment)
    {
        try {
            $comment = Comment::findOrFail($comment);
        } catch (ModelNotFoundException $e) {
            return response()->json('Comment not found', Response::HTTP_NOT_FOUND);
        }

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request,int $post, string $comment)
    {
        try {
            $comment = Comment::findOrFail($comment);
        } catch (ModelNotFoundException $e) {
            return response()->json('Comment not found', Response::HTTP_NOT_FOUND);
        }

        $validatedInput = $request->validated();
        $comment->update($validatedInput);
        $comment->refresh();

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $post, int $comment)
    {
       try {
            $comment = Comment::findOrFail($comment);
        } catch (ModelNotFoundException $e) {
            return response()->json('Comment not found', Response::HTTP_NOT_FOUND);
        }

        $comment->delete();

        return response()->json('Comment deleted', Response::HTTP_OK);
    }
}
