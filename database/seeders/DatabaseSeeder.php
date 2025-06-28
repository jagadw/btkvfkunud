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
        // Role Menu Permission Seeder
        $this->call(RoleMenuPermissionSeeder::class);


        // Create a developer user

        $developerUser = User::factory()->create([
            'name' => 'Harrys',
            'email' => 'harrysputra46@gmail.com',
            'password' => bcrypt('developerbtkv')
        ]);
        $developerUser->assignRole('operator');

        $admin =  User::factory()->create([
            'name' => 'Resident BTKV',
            'email' => 'residenbtkv.udayana@gmail.com',
            'password' => bcrypt('banjarbtkv')
        ]);
        $admin->assignRole('admin');

        // Mahasiswa Seeder
        $this->call(MahasiswaSeeder::class);

        // Dpjp Seeder
        $this->call(DpjpSeeder::class);
    }
}
