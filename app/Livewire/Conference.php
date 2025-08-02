<?php

namespace App\Livewire;

use App\Models\Pasien;
use App\Models\Conference as ConferenceModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Conference extends Component
{
    use WithPagination;

    public $selectedPasien;
    public $pasien_id, $diagnosa, $tanggal_conference, $hasil_conference;
    public $isEdit = false;
    public $search = '';

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));
        abort_unless($userPermissions->contains('masterdata-conference'), 403);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tindakans = \App\Models\Tindakan::with('conference')
            ->where(function ($query) {
                $user = Auth::user();
                if ($user->roles->pluck('name')->first() === 'dokter' && $user->akses_semua == 0) {
                    $query->whereHas('tindakanAsistens', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                }
            })
            ->get();

        return view('livewire.pages.admin.masterdata.conference.index', [
            'tindakans' => $tindakans,
            'conference' => ConferenceModel::with(['pasien', 'tindakan'])
                ->whereIn('tindakan_id', $tindakans->pluck('id'))
                ->when($this->tanggal_conference, function ($query) {
                    $query->whereYear('tanggal_conference', substr($this->tanggal_conference, 0, 4))
                        ->whereMonth('tanggal_conference', substr($this->tanggal_conference, 5, 2));
                })
                ->when($this->search, function ($query) {
                    $query->whereHas('pasien', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->get(),
            'pasiens' => Pasien::all(),
            'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
            'users' => User::all(),
        ]);
    }

}
