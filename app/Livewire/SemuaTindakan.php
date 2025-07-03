<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tindakan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class SemuaTindakan extends Component
{
    public $tanggal_operasi, $search = '', $fotoPreview, $selectedDivisi;

    public function mount()
    {
        $user = Auth::user();
        if (
            !$user ||
            (
            $user->akses_semua != 1 &&
            !$user->roles->contains('name', 'admin') &&
            !$user->roles->contains('name', 'operator') &&
            !$user->roles->contains('name', 'developer')
            )
        ) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function closeModal()
    {
        $this->dispatch('close-modal');
    }
    public function showFoto($foto)
    {
        $this->fotoPreview = $foto;
        $this->dispatch('show-modal');
    }  
    public function render()
    {
        // $tanggal_operasi = $this->tanggal_operasi;
        // $search = $this->search;

        return view('livewire.pages.admin.masterdata.tindakan.semua-tindakan', [
            'tindakans' => Tindakan::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
            ->where(function ($query) {
                // $user = Auth::user();
                // $userId = $user->id;

                // $query->whereHas('tindakanAsistens', function ($q) use ($userId) {
                //     $q->where('user_id', $userId);
                // });
            })
            ->when($this->tanggal_operasi, function ($query) {
                $query->whereYear('tanggal_operasi', substr($this->tanggal_operasi, 0, 4))
                    ->whereMonth('tanggal_operasi', substr($this->tanggal_operasi, 5, 2));
            })
            ->when($this->search, function ($query) {
                $query->whereHas('pasien', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_rekam_medis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedDivisi, function ($query) {
                $query->where('divisi', $this->selectedDivisi);
            })
            ->get(),
            'pasiens' => Pasien::all(),
            'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
            'users' => User::all(),
        ]);
    }
}
