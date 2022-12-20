<?php

namespace App\Policies\Administrasi;

use App\Models\MenuModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MenuModel  $menuModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->id)
                ->where('idrole','=',1)
                ->count() > 0;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->id)
                ->where('idrole','=',1)
                ->count() > 0;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MenuModel  $menuModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->id)
                ->where('idrole','=',1)
                ->count() > 0;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MenuModel  $menuModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->id)
                ->where('idrole','=',1)
                ->count() > 0;
    }

}
