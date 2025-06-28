<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper function to generate random NIM
        function randomNim() {
            return strval(rand(200000000, 399999999));
        }

        // dr. I Gde Julia Arta (operator)
        $operator1 = User::factory()->create([
            'name' => 'dr. I Gde Julia Arta',
            'email' => 'gdejuliaarta@gmail.com',
            'password' => bcrypt('Acmilan22!'),
        ]);
        $operator1->assignRole('operator');
        Mahasiswa::create([
            'nama' => 'dr. I Gde Julia Arta',
            'inisial_residen' => 'JUL',
            'nim' => randomNim(),
            'tempat_lahir' => 'Br. Bunutan',
            'tanggal_lahir' => '1991-07-18',
            'status' => 'Menikah',
            'alamat' => 'Jalan Palapa XI, Perum Taman Palapa Residence, No. 2, Sesetan, Denpasar Bali.',
            'user_id' => $operator1->id,
        ]);

        // dr. Giadefa Imam Cesyo
        $user2 = User::factory()->create([
            'name' => 'dr. Giadefa Imam Cesyo',
            'email' => 'adefcesyo@gmail.com',
            'password' => bcrypt('giadefaimam'),
        ]);
        $user2->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Giadefa Imam Cesyo',
            'inisial_residen' => 'ADF',
            'nim' => randomNim(),
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1989-12-14',
            'status' => 'Menikah',
            'alamat' => 'Jl. Pulau serangan no17. Gang pondok taman sari',
            'user_id' => $user2->id,
        ]);

        // dr. Henok Nugrahawanto
        $user3 = User::factory()->create([
            'name' => 'dr. Henok Nugrahawanto',
            'email' => 'henoq.holy@gmail.com',
            'password' => bcrypt('henoknugrahawanto'),
        ]);
        $user3->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Henok Nugrahawanto',
            'inisial_residen' => 'HNO',
            'nim' => randomNim(),
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1994-05-09',
            'status' => 'Belum Menikah',
            'alamat' => 'Jl. Pulau Buton No.21, Dauh Puri Klod, Kec. Denpasar Bar., Kota Denpasar, Bali 80113',
            'user_id' => $user3->id,
        ]);

        // dr. Ngurah Dwiky Abadi Resta
        $user4 = User::factory()->create([
            'name' => 'dr. Ngurah Dwiky Abadi Resta',
            'email' => 'ngurah.dwiky@gmail.com',
            'password' => bcrypt('ngurahdwiky'),
        ]);
        $user4->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Ngurah Dwiky Abadi Resta',
            'inisial_residen' => 'NDR',
            'nim' => randomNim(),
            'tempat_lahir' => 'Kupang',
            'tanggal_lahir' => '1993-03-15',
            'status' => 'Menikah',
            'alamat' => 'Jl. Tegal Wangi Gang Gadung No 7',
            'user_id' => $user4->id,
        ]);

        // dr. Novita Sahniar Sahyunu
        $user5 = User::factory()->create([
            'name' => 'dr. Novita Sahniar Sahyunu',
            'email' => 'nvtshnr16@gmail.com',
            'password' => bcrypt('novitasahniar'),
        ]);
        $user5->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Novita Sahniar Sahyunu',
            'inisial_residen' => 'NVT',
            'nim' => randomNim(),
            'tempat_lahir' => 'Kendari',
            'tanggal_lahir' => '1993-11-16',
            'status' => 'Belum Menikah',
            'alamat' => 'K&J Suite ., Jl. Komodo II No. 5 Dauh Puri Kelod Denpasar',
            'user_id' => $user5->id,
        ]);

        // dr. I Putu Sakamekya Wicaksana Sujaya
        $user6 = User::factory()->create([
            'name' => 'dr. I Putu Sakamekya Wicaksana Sujaya',
            'email' => 'sakamekyaw@gmail.com',
            'password' => bcrypt('putusakamekya'),
        ]);
        $user6->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. I Putu Sakamekya Wicaksana Sujaya',
            'inisial_residen' => 'SAK',
            'nim' => randomNim(),
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '1995-03-30',
            'status' => 'Menikah',
            'alamat' => 'Jalan Tegal Wangi II, Gang Cempaka Indah no 11',
            'user_id' => $user6->id,
        ]);

        // dr. Ester Hans Sunanto, Sp.B
        $user7 = User::factory()->create([
            'name' => 'dr. Ester Hans Sunanto, Sp.B',
            'email' => 'esterhs12@gmail.com',
            'password' => bcrypt('esterhans'),
        ]);
        $user7->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Ester Hans Sunanto, Sp.B',
            'inisial_residen' => 'ANA',
            'nim' => randomNim(),
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '1992-12-31',
            'status' => 'Menikah',
            'alamat' => 'Vila Puncak Tidar A1, Malang, Jawa Timur',
            'user_id' => $user7->id,
        ]);

        // dr. Maranatha
        $user8 = User::factory()->create([
            'name' => 'dr. Maranatha',
            'email' => 'maranathaliem@yahoo.com',
            'password' => bcrypt('drmaranatha'),
        ]);
        $user8->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Maranatha',
            'inisial_residen' => 'MRN',
            'nim' => randomNim(),
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1995-10-19',
            'status' => 'Belum Menikah',
            'alamat' => 'Taman Pondok Indah Blok RX-6, Kec Wiyung, Surabaya, Jawa Timur',
            'user_id' => $user8->id,
        ]);

        // dr. I Gede Sumertana Jaya
        $user9 = User::factory()->create([
            'name' => 'dr. I Gede Sumertana Jaya',
            'email' => 'diallzeronine@gmail.com',
            'password' => bcrypt('gedesumertana'),
        ]);
        $user9->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. I Gede Sumertana Jaya',
            'inisial_residen' => 'IGS',
            'nim' => randomNim(),
            'tempat_lahir' => 'Mataram',
            'tanggal_lahir' => '1993-03-29',
            'status' => 'Belum Menikah',
            'alamat' => 'Jln Raya Tanjung Bayan Dusun Nusantara Desa Medana  kec Tanjung Kabupaten Lombok Utara',
            'user_id' => $user9->id,
        ]);

        // dr. Putu Topan Bagaskara
        $user10 = User::factory()->create([
            'name' => 'dr. Putu Topan Bagaskara',
            'email' => 'topanbagaskara@gmail.com',
            'password' => bcrypt('pututopan'),
        ]);
        $user10->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Putu Topan Bagaskara',
            'inisial_residen' => 'TPN',
            'nim' => randomNim(),
            'tempat_lahir' => 'Penatahan',
            'tanggal_lahir' => '1996-08-10',
            'status' => 'Belum Menikah',
            'alamat' => 'Jl. Perm. Graha Parta Lestari, E/10, Tegal Buah, Padangsambian Kelod, Denpasar',
            'user_id' => $user10->id,
        ]);

        // dr. Lita Stephani Sianturi
        $user11 = User::factory()->create([
            'name' => 'dr. Lita Stephani Sianturi',
            'email' => 'litastevanii@gmail.com',
            'password' => bcrypt('litastephani'),
        ]);
        $user11->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Lita Stephani Sianturi',
            'inisial_residen' => 'STE',
            'nim' => randomNim(),
            'tempat_lahir' => 'Sidikalang',
            'tanggal_lahir' => '1996-09-14',
            'status' => 'Belum Menikah',
            'alamat' => 'Jalan Nusa Penida No. 18',
            'user_id' => $user11->id,
        ]);

        // dr. Fulgensius Atin
        $user12 = User::factory()->create([
            'name' => 'dr. Fulgensius Atin',
            'email' => 'fulgensiusatin@gmail.com',
            'password' => bcrypt('fulgensiusatin'),
        ]);
        $user12->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Fulgensius Atin',
            'inisial_residen' => 'FGN',
            'nim' => randomNim(),
            'tempat_lahir' => 'Atambua',
            'tanggal_lahir' => '1994-06-02',
            'status' => 'Menikah',
            'alamat' => 'Jalan pulau kae no 27, Denpasar, Bali',
            'user_id' => $user12->id,
        ]);

        // dr. Reza Fauzi
        $user13 = User::factory()->create([
            'name' => 'dr. Reza Fauzi',
            'email' => 'rezafauzi2804@gmail.com',
            'password' => bcrypt('rezafauzi'),
        ]);
        $user13->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Reza Fauzi',
            'inisial_residen' => 'RFZ',
            'nim' => randomNim(),
            'tempat_lahir' => 'Pematang siantar',
            'tanggal_lahir' => '1994-04-28',
            'status' => 'Menikah',
            'alamat' => 'Kori Nuansa Hijau No.41, Dauh Puri Klod, Kec. Denpasar Bar., Kota Denpasar, Bali',
            'user_id' => $user13->id,
        ]);

        // dr. Nyoman Satria Sadu Bhaskara, S.Ked (operator)
        $operator2 = User::factory()->create([
            'name' => 'dr. Nyoman Satria Sadu Bhaskara, S.Ked',
            'email' => 'bhaskara.satria78@gmail.com',
            'password' => bcrypt('btkvoperator2!'),
        ]);
        $operator2->assignRole('operator');
        Mahasiswa::create([
            'nama' => 'dr. Nyoman Satria Sadu Bhaskara, S.Ked',
            'inisial_residen' => 'BAS',
            'nim' => randomNim(),
            'tempat_lahir' => 'Negara',
            'tanggal_lahir' => '1999-01-08',
            'status' => 'Belum Menikah',
            'alamat' => 'Jalan Perum Taman Lembusura Blok VII No. 7, Ubung Kaja, Denpasar Utara',
            'user_id' => $operator2->id,
        ]);

        // dr. Auzan Qostholani Al Khoiri
        $user15 = User::factory()->create([
            'name' => 'dr. Auzan Qostholani Al Khoiri',
            'email' => 'auzanqostholani@gmail.com',
            'password' => bcrypt('auzanqostholani'),
        ]);
        $user15->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Auzan Qostholani Al Khoiri',
            'inisial_residen' => 'OZN',
            'nim' => randomNim(),
            'tempat_lahir' => 'Grobogan',
            'tanggal_lahir' => '1996-12-25',
            'status' => 'Menikah',
            'alamat' => 'Jl. dr Angka No. 22, Purwokerto Timur, Kabupaten Banyumas, Jawa Tengah',
            'user_id' => $user15->id,
        ]);

        // dr Bram Ray Leonard Demetouw
        $user16 = User::factory()->create([
            'name' => 'dr Bram Ray Leonard Demetouw',
            'email' => 'bram_ray@yahoo.com',
            'password' => bcrypt('bramray'),
        ]);
        $user16->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr Bram Ray Leonard Demetouw',
            'inisial_residen' => 'BRM',
            'nim' => randomNim(),
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1989-12-12',
            'status' => 'Menikah',
            'alamat' => 'Pemda 2 cigombong Blok M.14 Kota Jayapura',
            'user_id' => $user16->id,
        ]);

        // dr. Atthoriq Hayat Kurnia
        $user17 = User::factory()->create([
            'name' => 'dr. Atthoriq Hayat Kurnia',
            'email' => 'riqahk@gmail.com',
            'password' => bcrypt('atthoriqhayat'),
        ]);
        $user17->assignRole('dokter');
        Mahasiswa::create([
            'nama' => 'dr. Atthoriq Hayat Kurnia',
            'inisial_residen' => 'TOR',
            'nim' => randomNim(),
            'tempat_lahir' => 'Muaro Bungo',
            'tanggal_lahir' => '1995-09-21',
            'status' => 'Belum Menikah',
            'alamat' => 'Sunrise Residence, Jalan Pulau Ambon No.25, Dauh Puri Klod, Denpasar Barat, Denpasar, Bali',
            'user_id' => $user17->id,
        ]);

        // // STAFF BEDAH TORAKS, KARDIAK, DAN VASKULAR UDAYANA

        // // Ni Komang Widya Astuti, S.S
        // $user18 = User::factory()->create([
        //     'name' => 'Ni Komang Widya Astuti, S.S',
        //     'email' => 'komangwidyaastuti05@gmail.com',
        //     'password' => bcrypt('komangwidya'),
        // ]);
        // $user18->assignRole('dokter');
        // Mahasiswa::create([
        //     'nama' => 'Ni Komang Widya Astuti, S.S',
        //     'inisial_residen' => null,
        //     'nim' => randomNim(),
        //     'tempat_lahir' => 'Denpasar',
        //     'tanggal_lahir' => '2000-06-05',
        //     'status' => 'Belum Menikah',
        //     'alamat' => 'Jl. Tegal Wangi, No. 8, Sesetan, Denpasar',
        //     'user_id' => $user18->id,
        // ]);

        // // I Putu Yudi Suantara, S.Kom
        // $user19 = User::factory()->create([
        //     'name' => 'I Putu Yudi Suantara, S.Kom',
        //     'email' => 'yudhisuantara@gmail.com',
        //     'password' => bcrypt('putuyudi'),
        // ]);
        // $user19->assignRole('dokter');
        // Mahasiswa::create([
        //     'nama' => 'I Putu Yudi Suantara, S.Kom',
        //     'inisial_residen' => null,
        //     'nim' => randomNim(),
        //     'tempat_lahir' => 'Gianyar',
        //     'tanggal_lahir' => '1998-03-15',
        //     'status' => 'Belum Menikah',
        //     'alamat' => 'Jl.Manyar, No. 64, Desa Batubulan, Kec. Sukawati, Kab. Gianyar, Bali',
        //     'user_id' => $user19->id,
        // ]);
        

    }
}
