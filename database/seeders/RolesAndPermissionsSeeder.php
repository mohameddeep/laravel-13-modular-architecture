<?php

namespace Database\Seeders;

use App\Modules\Auth\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Clean up ─────────────────────────────────────────────────────────
        DB::table('permission_user')->delete();
        DB::table('permission_role')->delete();
        DB::table('role_user')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // ── Roles ─────────────────────────────────────────────────────────────
        $adminRoleId = DB::table('roles')->insertGetId([
            'name'             => 'admin',
            'display_name_en'  => 'Administrator',
            'display_name_ar'  => 'مدير النظام',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $managerRoleId = DB::table('roles')->insertGetId([
            'name'             => 'manager',
            'display_name_en'  => 'Manager',
            'display_name_ar'  => 'مدير',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // ── Permissions ────────────────────────────────────────────────────────
        $permissions = [

            // Dashboard
            ['name' => 'dashboard-view',       'display_name' => 'View Dashboard',           'description' => 'عرض لوحة التحكم'],

            // Admins
            ['name' => 'admins-read',           'display_name' => 'View Admins',              'description' => 'عرض المشرفين'],
            ['name' => 'admins-create',         'display_name' => 'Create Admins',            'description' => 'إنشاء مشرفين'],
            ['name' => 'admins-update',         'display_name' => 'Update Admins',            'description' => 'تعديل المشرفين'],
            ['name' => 'admins-delete',         'display_name' => 'Delete Admins',            'description' => 'حذف المشرفين'],

            // Roles
            ['name' => 'roles-read',            'display_name' => 'View Roles',               'description' => 'عرض الأدوار'],
            ['name' => 'roles-create',          'display_name' => 'Create Roles',             'description' => 'إنشاء أدوار'],
            ['name' => 'roles-update',          'display_name' => 'Update Roles',             'description' => 'تعديل الأدوار'],
            ['name' => 'roles-delete',          'display_name' => 'Delete Roles',             'description' => 'حذف الأدوار'],

            // Permissions
            ['name' => 'permissions-read',      'display_name' => 'View Permissions',         'description' => 'عرض الصلاحيات'],
            ['name' => 'permissions-create',    'display_name' => 'Create Permissions',       'description' => 'إنشاء صلاحيات'],
            ['name' => 'permissions-update',    'display_name' => 'Update Permissions',       'description' => 'تعديل الصلاحيات'],
            ['name' => 'permissions-delete',    'display_name' => 'Delete Permissions',       'description' => 'حذف الصلاحيات'],

            // Users
            ['name' => 'users-read',            'display_name' => 'View Users',               'description' => 'عرض المستخدمين'],
            ['name' => 'users-create',          'display_name' => 'Create Users',             'description' => 'إنشاء مستخدمين'],
            ['name' => 'users-update',          'display_name' => 'Update Users',             'description' => 'تعديل المستخدمين'],
            ['name' => 'users-delete',          'display_name' => 'Delete Users',             'description' => 'حذف المستخدمين'],

        ];

        $permissionIds = [];
        foreach ($permissions as $p) {
            $permissionIds[$p['name']] = DB::table('permissions')->insertGetId(
                array_merge($p, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ── Assign ALL permissions to admin role ───────────────────────────────
        foreach ($permissionIds as $permId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permId,
                'role_id'       => $adminRoleId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // ── Assign limited permissions to manager role ─────────────────────────
        $managerPermissions = [
            'dashboard-view',
            'admins-read',
            'roles-read',
            'permissions-read',
            'users-read',
        ];

        foreach ($managerPermissions as $permName) {
            if (isset($permissionIds[$permName])) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionIds[$permName],
                    'role_id'       => $managerRoleId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // ── Default super-admin user ───────────────────────────────────────────
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        DB::table('role_user')->insertOrIgnore([
            'role_id'    => $adminRoleId,
            'user_id'    => $admin->id,
            'user_type'  => Admin::class,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Roles & Permissions seeded successfully!');
        $this->command->info('👤 Super Admin — email: admin@admin.com  |  password: password');
    }
}
