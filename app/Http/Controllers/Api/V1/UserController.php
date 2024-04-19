<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('role:Admin', except: ['show']),
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();

        return UserResource::collection($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $user)
    {
        try {
            $user = User::findOrFail($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $user)
    {
        try {
            $user = User::findOrFail($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validatedInput = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,'.$request->route('user')],
        ]);

        return DB::transaction(function () use($validatedInput, $user) {
            
            $user->profile()->update($validatedInput);
            
            $user = $user->profile->makeUser();

            return new UserResource($user, Response::HTTP_OK);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user)
    {
        try {
            $user = User::findOrFail($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        }

        DB::transaction(function () use($user) {
            $user->profile()->delete();
            $user->delete();
            
        });

        return response()->json(['message' => 'Profile deleted'], Response::HTTP_OK);
    }
}
