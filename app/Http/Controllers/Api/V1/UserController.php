<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    protected $policy_class = UserPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filter)
    {
        
        return UserResource::collection(
            User::filter($filter)->paginate()
        );
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{

            $this->isAble('store', User::class);
            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $ex){
            return $this->error('You are not authorized to create users', 401);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('tickets'));
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $user_id)
    {
        // PATCH
        try {
            $user = User::find($user_id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            //policy, ability to authorize users
            $this->isAble('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $exception) {

            return $this->error('User not found', 404);
        
        } catch (AuthorizationException $exception) {

            return $this->error('You are not authorized to update user', 401);
        } 
    }

     public function replace(ReplaceUserRequest $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->isAble('replace', $user);

        $user->update($request->mappedAttributes());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
         try {
            $user = User::findOrFail($user_id);
            $this->isAble('delete', $user);
            $user->delete();
            return $this->ok('User successfully deleted', null);

        } catch (ModelNotFoundException $exception) {

            return $this->error('User not found', 404);


        }
    }
}
