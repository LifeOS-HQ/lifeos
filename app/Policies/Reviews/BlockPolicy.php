<?php

namespace App\Policies\Reviews;

use App\Models\Reviews\Block;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlockPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any blocks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the block.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Block  $block
     * @return mixed
     */
    public function view(User $user, Block $block)
    {
        return ($user->id == $block->user_id);
    }

    /**
     * Determine whether the user can create blocks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the block.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Block  $block
     * @return mixed
     */
    public function update(User $user, Block $block)
    {
        return ($user->id == $block->user_id);
    }

    /**
     * Determine whether the user can delete the block.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Block  $block
     * @return mixed
     */
    public function delete(User $user, Block $block)
    {
        return ($user->id == $block->user_id);
    }

    /**
     * Determine whether the user can restore the block.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Block  $block
     * @return mixed
     */
    public function restore(User $user, Block $block)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the block.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reviews\Block  $block
     * @return mixed
     */
    public function forceDelete(User $user, Block $block)
    {
        //
    }
}
