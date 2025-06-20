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
    public $tanggal_operasi, $search = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->akses_semua != 1) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function render()
    {
        // $tanggal_operasi = $this->tanggal_operasi;
        // $search = $this->search;

        return view('livewire.pages.admin.masterdata.tindakan.semua-tindakan', [
            'tindakans' => Tindakan::with(['pasien', 'operator', 'asisten1', 'asisten2', 'onLoop'])
                ->where(function ($query) {
                    $user = Auth::user();
                    $userId = $user ? $user->id : null;
                    // if ($user && $user->roles->pluck('name')->first() === 'dokter') {
                    //     $query->where('operator_id', $userId)
                    //         ->orWhere('asisten1_id', $userId)
                    //         ->orWhere('asisten2_id', $userId)
                    //         ->orWhere('on_loop_id', $userId);
                    // }
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
                ->get(),
            'pasiens' => Pasien::all(),
            'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
            'users' => User::all(),
        ]);
    }
}
