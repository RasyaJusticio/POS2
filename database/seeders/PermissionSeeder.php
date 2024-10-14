<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_has_permissions')->delete();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $menuInventori = ['inventori', 'inventori-produk', 'inventori-kategori', 'inventori-stok', 'inventori-reservasi'];
        $menuMaster = ['master', 'master-user', 'master-role'];
        $menuWebsite = ['website', 'setting'];

        // Definisikan permissions berdasarkan role
        $permissionsByRole = [
            'admin' => ['dashboard', ...$menuInventori, ...$menuMaster, ...$menuWebsite],
            'user' => ['dashboard', 'view-posts', 'comment'], // Menambahkan permission untuk user
        ];

        // Fungsi untuk memasukkan permissions
        $insertPermissions = fn ($role) => collect($permissionsByRole[$role])
            ->map(function ($name) {
                $check = Permission::whereName($name)->first();

                if (!$check) {
                    return Permission::create([
                        'name' => $name,
                        'guard_name' => 'api',
                    ])->id;
                }

                return $check->id;
            })
            ->toArray();

        // Ambil permission IDs berdasarkan role
        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'user' => $insertPermissions('user'), // Mengambil permission untuk user
        ];

        // Menyimpan permissions ke dalam tabel role_has_permissions
        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }
    }
}
