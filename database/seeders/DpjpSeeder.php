<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DpjpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        $user1 = User::create([
            'name' => 'Dr. dr. I Nyoman Semadi, Sp.B., Sp.BTKV., Subsp.VE(K), FIATCVS',
            'email' => 'nyomansemadi56@gmail.com',
            'password' => Hash::make('nyomansemadi'),
        ]);
        $user1->assignRole('dpjp');
        \App\Models\Dpjp::create([
            'user_id' => $user1->id,
            'nama' => 'Dr. dr. I Nyoman Semadi, Sp.B., Sp.BTKV., Subsp.VE(K), FIATCVS',
            'inisial_residen' => 'INS',
            'tempat_lahir' => 'Karangasem',
            'tanggal_lahir' => '1956-02-12',
            'status' => 'Menikah',
            'alamat' => 'Jalan Tukad Yeh HO III, No. 2, Denpasar',
        ]);

        // 2
        $user2 = User::create([
            'name' => 'Dr. dr. Ketut Putu Yasa, Sp.B., Sp.BTKV., Subsp.VE(K), FICS, FIATCVS',
            'email' => 'ketut.putuyasa07@gmail.com',
            'password' => Hash::make('ketutputu'),
        ]);
        $user2->assignRole('dpjp');
        \App\Models\Dpjp::create([
            'user_id' => $user2->id,
            'nama' => 'Dr. dr. Ketut Putu Yasa, Sp.B., Sp.BTKV., Subsp.VE(K), FICS, FIATCVS',
            'inisial_residen' => 'KYS',
            'tempat_lahir' => 'Buleleng',
            'tanggal_lahir' => '1960-11-15',
            'status' => 'Menikah',
            'alamat' => 'Jalan Gunung Himalaya I B, No. 8, Denpasar',
            'is_koordinator' => 1,
        ]);

        // 3
        $user3 = User::create([
            'name' => 'dr. I Wayan Sudarma, Sp.BTKV., Subsp.T(K), FIATCVS',
            'email' => 'driwayansudarmaiws@gmail.com',
            'password' => Hash::make('wayansudarma'),
        ]);
        $user3->assignRole('dpjp');
        \App\Models\Dpjp::create([
            'user_id' => $user3->id,
            'nama' => 'dr. I Wayan Sudarma, Sp.BTKV., Subsp.T(K), FIATCVS',
            'inisial_residen' => 'IWS',
            'tempat_lahir' => 'Darmasaba',
            'tanggal_lahir' => '1986-04-18',
            'status' => 'Menikah',
            'alamat' => 'Jalan Raya Darmasaba, Gg. Gelatik No. 12, Br. Baler Pasar Darmasaba, Abiansemal',
        ]);

        // 4
        $user4 = User::create([
            'name' => 'dr. I Komang Adhi Parama Harta, Sp.BTKV., Subsp.JD(K), FICS, FIATCVS',
            'email' => 'adhiparamaharta@gmail.com',
            'password' => Hash::make('komangadhi'),
        ]);
        $user4->assignRole('dpjp');
        \App\Models\Dpjp::create([
            'user_id' => $user4->id,
            'nama' => 'dr. I Komang Adhi Parama Harta, Sp.BTKV., Subsp.JD(K), FICS, FIATCVS',
            'inisial_residen' => 'ADI',
            'tempat_lahir' => 'Penyaringan',
            'tanggal_lahir' => '1988-05-31',
            'status' => 'Menikah',
            'alamat' => 'Jalan Kresna, Banjar Anyar Kelod, Penyaringan, Mendoyo',
        ]);

        // 5
        $user5 = User::create([
            'name' => 'dr. I Putu Kokohana Arisutawan, Sp.BTKV, Subsp.VE(K), FIATCVS',
            'email' => 'iputukokohanaas@gmail.com',
            'password' => Hash::make('putukokohana'),
        ]);
        $user5->assignRole('dpjp');
        \App\Models\Dpjp::create([
            'user_id' => $user5->id,
            'nama' => 'dr. I Putu Kokohana Arisutawan, Sp.BTKV, Subsp.VE(K), FIATCVS',
            'inisial_residen' => 'IPK',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '1987-07-30',
            'status' => 'Menikah',
            'alamat' => 'Jalan Tukad Batanghari no. 90',
        ]);
    }
}
