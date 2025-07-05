<?php

namespace App\Livewire;

use App\Models\Mahasiswa;
use Livewire\Component;

class SelectMahasiswa extends Component
{
    public $selectedMahasiswa;

    public function render()
    {
        return view('livewire.pages.admin.masterdata.user.select-mahasiswa', [
            'mahasiswas' => Mahasiswa::where('user_id', null)->get(),
        ]);
    }
}
