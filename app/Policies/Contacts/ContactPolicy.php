<?php

namespace App\Policies\Contacts;

use App\Models\Contacts\Contact;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
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
     * Determine whether the user can view the DocContact.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Contacts\Contact  $contact
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        return ($user->id == $contact->user_id);
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
     * Determine whether the user can update the DocContact.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Contacts\Contact  $contact
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        return ($user->id == $contact->user_id);
    }

    /**
     * Determine whether the user can delete the DocContact.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Contacts\Contact  $contact
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        return ($user->id == $contact->user_id);
    }

    /**
     * Determine whether the user can restore the DocContact.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Contacts\Contact  $contact
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the DocContact.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Contacts\Contact  $contact
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        //
    }
}
