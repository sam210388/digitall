<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Administrasi\AdministrasiUserModel' => 'App\Policies\Administrasi\KewenanganMenuPolicy',
        'App\Models\Administrasi\KewenanganMenuModel' => 'App\Policies\Administrasi\KewenanganMenuPolicy',
        'App\Models\Administrasi\KewenanganModel' => 'App\Policies\Administrasi\KewenanganPolicy',
        'App\Models\Administrasi\KewenanganUserModel' => 'App\Policies\Administrasi\KewenanganUserPolicy',
        'App\Models\Administrasi\MenuModel' => 'App\Policies\Administrasi\MenuPolicy',
        'App\Models\Administrasi\SubMenuModel' => 'App\Policies\Administrasi\SubMenuPolicy',
        'App\Models\ReferensiUnit\DeputiModel' => 'App\Policies\ReferensiUnit\DeputiPolicy',
        'App\Models\ReferensiUnit\BiroModel' => 'App\Policies\ReferensiUnit\BiroPolicy',
        'App\Models\ReferensiUnit\BagianModel' => 'App\Policies\ReferensiUnit\BagianPolicy',
        'App\Models\BPK\Admin\TemuanModel' => 'App\Policies\BPK\Admin\TemuanPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
