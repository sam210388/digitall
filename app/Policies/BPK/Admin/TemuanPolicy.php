<?php

namespace App\Policies\BPK\Admin;

use App\Models\BPK\Admin\TemuanModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class TemuanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function view(User $user)
    {
        return DB::table('role_users')
            ->where('iduser','=',$user->iduser)
            ->where('idrole','=',1)
            ->orWhere('idrole','=',6)
            ->orWhere('idrole','=',3)
            ->orWhere('idrole','=',7)
            ->count() > 0;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function store(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->iduser)
                ->where('idrole','=',1)
                ->orWhere('idrole','=',3)
                ->count() > 0;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemuanModel  $temuanModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TemuanModel $temuanModel)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->iduser)
                ->where('idrole','=',1)
                ->orWhere('idrole','=',3)
                ->count() > 0;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemuanModel  $temuanModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return DB::table('role_users')
                ->where('iduser','=',$user->iduser)
                ->where('idrole','=',1)
                ->orWhere('idrole','=',3)
                ->count() > 0;
    }

    public function kirimtemuankeunit(User $user){
        return DB::table('role_users')
                ->where('iduser','=',$user->iduser)
                ->where('idrole','=',1)
                ->orWhere('idrole','=',3)
                ->count() > 0;
    }


}
