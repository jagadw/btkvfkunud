<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleMenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $developer = Role::firstOrCreate(['name' => 'developer', 'guard_name' => 'web']);
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $dokter = Role::firstOrCreate(['name' => 'dokter', 'guard_name' => 'web']);
        $dpjp = Role::firstOrCreate(['name' => 'dpjp', 'guard_name' => 'web']);

        // Define permissions
        $permissions = [
            'dashboard',
            'masterdata-user',
            'masterdata-menu',
            'masterdata-role',
            'masterdata-mahasiswa',
            'masterdata-pasien',
            'masterdata-conference',
            'masterdata-tindakan',
            'masterdata-fototindakan',
            'masterdata-logbook',
            'masterdata-dpjp',
            'masterdata-verifikasi-tindakan',
            'masterdata-sudah-verifikasi',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $developer->syncPermissions([
            'dashboard',
            'masterdata-user',
            'masterdata-menu',
            'masterdata-role',
            'masterdata-mahasiswa',
            'masterdata-pasien',
            'masterdata-conference',
            'masterdata-tindakan',
            // 'masterdata-fototindakan',
            'masterdata-logbook',
            'masterdata-dpjp',
        ]);

        $operator->syncPermissions([
            'dashboard',
            'masterdata-user',
            // 'masterdata-menu',
            // 'masterdata-role',
            'masterdata-mahasiswa',
            'masterdata-pasien',
            'masterdata-conference',
            'masterdata-tindakan',
            // 'masterdata-fototindakan',
            'masterdata-logbook',
            'masterdata-dpjp',
        ]);

        $admin->syncPermissions([
            'dashboard',
            'masterdata-user',
            // 'masterdata-menu',
            // 'masterdata-role',
            'masterdata-mahasiswa',
            'masterdata-pasien',
            'masterdata-conference',
            'masterdata-tindakan',
            // 'masterdata-fototindakan',
            'masterdata-logbook',
            'masterdata-dpjp',
        ]);

        $dokter->syncPermissions([
            'dashboard',
            'masterdata-tindakan',
            // 'masterdata-fototindakan',
            'masterdata-conference',
            'masterdata-logbook',
        ]);

        $dpjp->syncPermissions([
            'masterdata-verifikasi-tindakan',
            'masterdata-sudah-verifikasi',
        ]);

        // Menu Dashboard
        $dashboard = Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-house',
            'route' => 'dashboard',
            'order' => 1,
        ]);

        SubMenu::create([
            'menu_id' => $dashboard->id,
            'name' => 'Home',
            'route' => 'dashboard',
            'order' => 1,
            'permission_id' => Permission::where('name', 'dashboard')->first()->id

        ]);

        // Menu Master Data
        $masterData = Menu::create([
            'name' => 'Master Data',
            'icon' => 'fa-solid fa-database',
            'route' => null,
            'order' => 2
        ]);

        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'User',
            'route' => 'user',
            'order' => 1,
            'permission_id' => Permission::where('name', 'masterdata-user')->first()->id
        ]);

        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Menu',
            'route' => 'menu',
            'order' => 2,
            'permission_id' => Permission::where('name', 'masterdata-menu')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Role',
            'route' => 'role',
            'order' => 3,
            'permission_id' => Permission::where('name', 'masterdata-role')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Mahasiswa',
            'route' => 'mahasiswa',
            'order' => 4,
            'permission_id' => Permission::where('name', 'masterdata-mahasiswa')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'DPJP',
            'route' => 'dpjp',
            'order' => 5,
            'permission_id' => Permission::where('name', 'masterdata-dpjp')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Pasien',
            'route' => 'pasien',
            'order' => 6,
            'permission_id' => Permission::where('name', 'masterdata-pasien')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Tindakan',
            'route' => 'tindakan',
            'order' => 7,
            'permission_id' => Permission::where('name', 'masterdata-tindakan')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Conference',
            'route' => 'conference',
            'order' => 8,
            'permission_id' => Permission::where('name', 'masterdata-conference')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Logbook',
            'route' => 'logbook',
            'order' => 9,
            'permission_id' => Permission::where('name', 'masterdata-logbook')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Foto Tindakan',
            'route' => 'fototindakan',
            'order' => 10,
            'permission_id' => Permission::where('name', 'masterdata-fototindakan')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Verifikasi Tindakan',
            'route' => 'verifikasi-tindakan',
            'order' => 11,
            'permission_id' => Permission::where('name', 'masterdata-verifikasi-tindakan')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Sudah Verifikasi',
            'route' => 'sudah-verifikasi',
            'order' => 12,
            'permission_id' => Permission::where('name', 'masterdata-sudah-verifikasi')->first()->id
        ]);

    }
}
