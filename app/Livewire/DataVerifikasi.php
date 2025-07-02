<?php

namespace App\Livewire;

use App\Models\Tindakan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class DataVerifikasi extends Component
{
    public $tanggal_operasi_start, $tanggal_operasi_end, $search = '';
    public function render()
    {
        return view('livewire.pages.admin.masterdata.tindakan.sudah-verifikasi',[
            'tindakans' => Tindakan::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
            ->where('dpjp_id', Auth::id())
            ->where('verifikasi', 1)
            ->when($this->tanggal_operasi_start && $this->tanggal_operasi_end, function ($query) {
                $query->whereBetween('tanggal_operasi', [$this->tanggal_operasi_start, $this->tanggal_operasi_end]);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('pasien', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_rekam_medis', 'like', '%' . $this->search . '%');
                });
            })
            ->get()
        ]);
    }
}
