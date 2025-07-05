<?php

namespace App\Livewire;

use App\Models\Dpjp;
use Livewire\Component;

class SelectDpjp extends Component
{
    public $selectedDpjp;

    public function render()
    {
        return view('livewire.pages.admin.masterdata.user.select-dpjp', [
            'dpjps' => Dpjp::where('user_id', null)->get(),
        ]);
    }
}
