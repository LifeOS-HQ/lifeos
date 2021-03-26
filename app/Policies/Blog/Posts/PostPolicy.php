<?php

namespace App\Policies\Blog\Posts;

use App\Models\Blog\Posts\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the DocPost.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return mixed
     */
    public function view(User $user, Post $post)
    {
        return ($user->id == $post->user_id);
    }

    /**
     * Determine whether the user can create DocDummyPluralModel.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the DocPost.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return ($user->id == $post->user_id);
    }

    /**
     * Determine whether the user can delete the DocPost.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        return ($user->id == $post->user_id);
    }

    /**
     * Determine whether the user can restore the DocPost.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return mixed
     */
    public function restore(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocPost.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return mixed
     */
    public function forceDelete(User $user, Post $post)
    {
        //
    }
}
