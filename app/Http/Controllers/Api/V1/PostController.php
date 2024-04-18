<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
            new Middleware('role:Admin', except: ['index', 'show']),
        ];

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
       $validatedInput = $request->validated();
       $validatedInput['user_id'] = Auth::user()->id;
       $firebase_storage_path = config('services.firebase.environment').'/'.'blog/'; 
       $originName = $validatedInput['media']->getClientOriginalName();
       $fileName  = pathinfo($originName, PATHINFO_FILENAME);
       $extension = $validatedInput['media']->getClientOriginalExtension(); 
       $fileName = $fileName.'.'.$extension; 
       $localPath = 'storage/images/blog';
       $downloadUrl = Auth::user()->store($validatedInput['media'], $firebase_storage_path, $localPath, $fileName); 
       $validatedInput['media'] = $downloadUrl;
       $post = Post::create($validatedInput);

       return new PostResource($post, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $post)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => ['message' => 'Post not found']], Response::HTTP_NOT_FOUND);
        }
        return new PostResource($post, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, int $post)
    {
        try {
            $old_post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }
        $validatedInput = $request->validated();
        if ($request->has('media')) {
            $firebase_storage_path = config('services.firebase.environment').'/'.'blog/'; 
            $originName = $validatedInput['media']->getClientOriginalName();
            $fileName  = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $validatedInput['media']->getClientOriginalExtension(); 
            $fileName = $fileName.'.'.$extension; 
            $localPath = 'storage/images/blog';
            $downloadUrl = Auth::user()->store($validatedInput['media'], $firebase_storage_path, $localPath, $fileName); 
            $validatedInput['media'] = $downloadUrl;
        }
        $old_post->update($validatedInput);
        $old_post->refresh();
       return new PostResource($old_post, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $post)
    {
        try {
            $post = Post::findOrFail($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $post->delete();
        
        return response()->json(['message' => 'Post deleted'], Response::HTTP_OK);
    }
}
