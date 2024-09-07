<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserApiResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersApiController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserApiResource::collection(
            User::all()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // i made one with register
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check if the user exists and not deleted (soft delete)
        $user =  User::find($id);

        // If user not found, return an error response
        if (!$user) {
            return $this->error('', 'User not found', 404);
        }
        return new UserApiResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        // Check if the user exists and not deleted (soft delete)
        $user =  User::find($id);
        // If user not found, return an error response
        if (!$user) {
            return $this->error('', 'User not found', 404);
        }
        $request->validated($request->all());

        $user->update($request->all());
        $user =  new UserApiResource($user);
        return $this->success($user, 'user has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if the user exists and not deleted (soft delete)
        $user = User::find($id);

        // If user not found, return an error response
        if (!$user) {
            return $this->error('', 'User not found', 404);
        }

        // If user found, delete the user and return a success response
        $user->delete();

        return $this->success('', 'User has been deleted');
    }
}
