<?php

namespace App\Policies\Comments;

use App\Models\Comments\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can view the DocComment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Comments\Comment  $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        return ($user->id == $comment->user_id);
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
     * Determine whether the user can update the DocComment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Comments\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return ($user->id == $comment->user_id);
    }

    /**
     * Determine whether the user can delete the DocComment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Comments\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return ($user->id == $comment->user_id);
    }

    /**
     * Determine whether the user can restore the DocComment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Comments\Comment  $comment
     * @return mixed
     */
    public function restore(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocComment.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Comments\Comment  $comment
     * @return mixed
     */
    public function forceDelete(User $user, Comment $comment)
    {
        //
    }
}
