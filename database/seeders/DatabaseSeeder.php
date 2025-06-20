<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Customer;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\Mahasiswa;
use App\Models\Menu;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\SubMenu;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $developer = Role::firstOrCreate(['name' => 'developer', 'guard_name' => 'web']);
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $dokter = Role::firstOrCreate(['name' => 'dokter', 'guard_name' => 'web']);

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
        ]);

        $dokter->syncPermissions([
            'dashboard',
            'masterdata-tindakan',
            // 'masterdata-fototindakan',
            'masterdata-conference',
            'masterdata-logbook',
        ]);

        // Create a developer user

        $developerUser = User::factory()->create([
            'name' => 'Harrys',
            'email' => 'harrysputra46@gmail.com',
            'password' => bcrypt('guarajadisini')
        ]);
        $developerUser->assignRole('developer');

        $admin =  User::factory()->create([
            'name' => 'Resident BTKV',
            'email' => 'residenbtkv.udayana@gmail.com',
            'password' => bcrypt('banjarbtkv')
        ]);
        $admin->assignRole('admin');

        Mahasiswa::create([
            'nama' => 'Resident BTKV',
            'inisial_residen' => 'RB',
            'nim' => '24993934',
            'user_id' => $admin->id,
            'status' => 'aktif',
        ]);

        $operator1 = User::factory()->create([
            'name' => 'Gede Juliarta',
            'email' => 'gdejuliaarta@gmail.com',
            'password' => bcrypt('Acmilan22!')
        ]);
        $operator1->assignRole('operator');

        Mahasiswa::create([
            'nama' => 'Gede Juliarta',
            'inisial_residen' => 'GJ',
            'nim' => '24993935',
            'user_id' => $operator1->id,
            'status' => 'aktif',
        ]);

        $operator2 = User::factory()->create([
            'name' => 'Bhaskara Satria',
            'email' => 'bhaskara.satria78@gmail.com',
            'password' => bcrypt('btkvoperator2!')
        ]);
        $operator2->assignRole('operator');

        Mahasiswa::create([
            'nama' => 'Bhaskara Satria',
            'inisial_residen' => 'BS',
            'nim' => '24993936',
            'user_id' => $operator2->id,
            'status' => 'aktif',
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
            'name' => 'Pasien',
            'route' => 'pasien',
            'order' => 5,
            'permission_id' => Permission::where('name', 'masterdata-pasien')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Conference',
            'route' => 'conference',
            'order' => 6,
            'permission_id' => Permission::where('name', 'masterdata-conference')->first()->id
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
            'name' => 'Foto Tindakan',
            'route' => 'fototindakan',
            'order' => 8,
            'permission_id' => Permission::where('name', 'masterdata-fototindakan')->first()->id
        ]);
        SubMenu::create([
            'menu_id' => $masterData->id,
            'name' => 'Logbook',
            'route' => 'logbook',
            'order' => 9,
            'permission_id' => Permission::where('name', 'masterdata-logbook')->first()->id
        ]);
        
        // Create Mahasiswa
 
        $mahasiswas = [
            [
                'nama' => 'Andi Pratama',
                'inisial_residen' => 'AP',
                'nim' => '24993929',
                'email' => 'andi.pratama@gmail.com',
            ],
            [
                'nama' => 'Budi Santoso',
                'inisial_residen' => 'BS',
                'nim' => '24993930',
                'email' => 'budi.santoso@gmail.com',
            ],
            [
                'nama' => 'Citra Dewi',
                'inisial_residen' => 'CD',
                'nim' => '24993931',
                'email' => 'citra.dewi@gmail.com',
            ],
            [
                'nama' => 'Dewi Lestari',
                'inisial_residen' => 'DL',
                'nim' => '24993932',
                'email' => 'dewi.lestari@gmail.com',
            ],
            [
                'nama' => 'Eko Wijaya',
                'inisial_residen' => 'EW',
                'nim' => '24993933',
                'email' => 'eko.wijaya@gmail.com',
            ],
        ];

        foreach ($mahasiswas as $mhs) {
            $user = User::create([
                'name' => $mhs['nama'],
                'email' => $mhs['email'],
                'password' => bcrypt('dokterbtkv'), 
            ]);
            $user->assignRole('dokter');

            Mahasiswa::create([
                'nama' => $mhs['nama'],
                'inisial_residen' => $mhs['inisial_residen'],
                'nim' => $mhs['nim'],
                'user_id' => $user->id,
                'status' => 'aktif',
            ]);
        }

    }
}
