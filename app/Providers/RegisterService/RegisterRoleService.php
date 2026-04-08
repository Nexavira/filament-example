<?php

namespace App\Providers\RegisterService;

use App\Providers\AppServiceProvider;

class RegisterRoleService extends AppServiceProvider
{
    public function register(): void
    {
        /** Role */
        $this->registerService('StoreRoleService', \App\Services\RoleService\StoreRoleService::class);
        $this->registerService('UpdateRoleService', \App\Services\RoleService\UpdateRoleService::class);
        $this->registerService('DeleteRoleService', \App\Services\RoleService\DeleteRoleService::class);
        $this->registerService('GetRoleService', \App\Services\RoleService\GetRoleService::class);
    }
}