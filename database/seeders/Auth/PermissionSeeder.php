<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guards = ['admin', 'mobile', 'web'];

        $structure = [
            'Authentication' => [
                'User' => ['View', 'Create', 'Edit', 'Delete', 'Reset Password'],
                'Role' => ['View', 'Create', 'Edit', 'Delete'],
                'Log'  => ['View', 'Export'],
            ],
            'Patient Management' => [
                'Registration' => ['View', 'Create', 'Edit', 'Cancel'],
                'Medical Record' => ['View', 'Create', 'Edit', 'Print'],
                'History' => ['View'],
            ],
            'Transaction' => [
                'Invoice' => ['View', 'Create', 'Payment', 'Void'],
                'Report'  => ['Daily', 'Monthly', 'Annual'],
            ],
            'Master Data' => [
                'Doctor' => ['View', 'Create', 'Edit', 'Delete'],
                'Clinic' => ['View', 'Edit Settings'],
                'Drug'   => ['View', 'Create', 'Edit', 'Stock In', 'Stock Out'],
            ],
        ];

        foreach ($guards as $guard) {
            foreach ($structure as $prefix => $modules) {
                foreach ($modules as $module => $actions) {
                    foreach ($actions as $action) {                        
                        $code = Str::lower($guard . '_' . Str::slug($prefix, '_') . '_' . Str::slug($module, '_') . '_' . Str::slug($action, '_'));

                        Permission::create([
                            'uuid'          => (string) Str::uuid(),
                            'guard_name'    => $guard,
                            'prefix_label'   => $prefix,
                            'module_label'  => $module,
                            'action_label'  => $action,
                            'code'          => $code,
                            'is_active'     => 1,
                            'version'       => 1,
                        ]);
                    }
                }
            }
        }
    }
}