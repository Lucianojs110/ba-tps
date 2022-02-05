<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
     
    
    public function viewAll(User $user)
    {
       return isAdmin($user);
    }

    public function view(User $user)
    {
        return isAdmin($user);
    }

    public function create(User $user)
    {
        return isAdmin($user);
    }

    public function update(User $user)
    {
        return isAdmin($user);
    }

    public function destroy(User $user)
    {
        return isAdmin($user);
    }

}
