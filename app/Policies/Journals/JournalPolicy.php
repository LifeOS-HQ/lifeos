<?php

namespace App\Policies\Journals;

use App\Models\Journals\Journal;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any items.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Journal $journal = null)
    {
        if (is_null($journal)) {
            return true;
        }

        return ($user->id == $journal->user_id);
    }

    /**
     * Determine whether the user can view the item.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Journals\Journal  $journal
     * @return mixed
     */
    public function view(User $user, Journal $journal)
    {
        return ($user->id == $journal->user_id);
    }

    /**
     * Determine whether the user can create Journals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the item.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Journals\Journal  $journal
     * @return mixed
     */
    public function update(User $user, Journal $journal)
    {
        return ($user->id == $journal->user_id);
    }

    /**
     * Determine whether the user can delete the item.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Journals\Journal  $journal
     * @return mixed
     */
    public function delete(User $user, Journal $journal)
    {
        return ($user->id == $journal->user_id);
    }

    /**
     * Determine whether the user can restore the item.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Journals\Journal  $journal
     * @return mixed
     */
    public function restore(User $user, Journal $journal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the item.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Journals\Journal  $journal
     * @return mixed
     */
    public function forceDelete(User $user, Journal $journal)
    {
        //
    }
}
