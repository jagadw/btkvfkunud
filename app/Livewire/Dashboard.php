<?php

namespace App\Livewire;


use App\Models\Tindakan;
use App\Models\Pasien;
use App\Models\User;
use App\Models\Mahasiswa;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $userRole, $tanggal_operasi, $dataJumlahPasienDitangani, $dataPasienDitanganiFilter, $JumlahDokter, $JumlahPasien, $JumlahTindakan, $JumlahUser, $search = '', $totalPasienDitanganiBulanIni;

    protected $listerners = [
        'loadDashboardDokter',
        'loadDashboardOperator',
        'loadDashboardAdmin'
    ];

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        });

        if (!$userPermissions->contains('dashboard')) {
            abort(403, 'Unauthorized action.');
        }

        $userRole = Auth::user()->roles->pluck('name')->first();
        if ($userRole === 'admin') {
            $this->userRole = 'admin';
        } elseif ($userRole === 'operator') {
            $this->userRole = 'operator';
        } elseif ($userRole === 'dokter') {
            $this->userRole = 'dokter';
        } elseif ($userRole === 'developer') {
            $this->userRole = 'developer';
        } elseif ($userRole === 'dpjp') {
            $this->userRole = 'dpjp';
        }
         else{
            abort(403, 'Unauthorized action.');
        }

        if ($this->userRole === 'admin') {
            $this->JumlahPasien = Pasien::count();
            $this->JumlahDokter = Mahasiswa::count();
            $this->JumlahTindakan = Tindakan::count();
        } elseif ($this->userRole === 'operator') {
            $this->JumlahUser = User::count();
            $this->JumlahDokter = Mahasiswa::count();
            $this->JumlahPasien = Pasien::count();
            $this->JumlahTindakan = Tindakan::count();
        }
    }

    public function render()
    {
        if ($this->userRole === 'admin') {
            $JumlahPasien = Pasien::count();
            $JumlahDokter = Mahasiswa::count();
            $JumlahTindakan = Tindakan::count();

            return view('livewire.pages.dashboard-admin', compact('JumlahPasien', 'JumlahDokter', 'JumlahTindakan'));
        } elseif ($this->userRole === 'operator' || $this->userRole === 'developer') {
            $JumlahUser = User::count();
            $JumlahDokter = Mahasiswa::count();
            $JumlahPasien = Pasien::count();
            $JumlahTindakan = Tindakan::count();

            return view('livewire.pages.dashboard-operator', compact('JumlahUser', 'JumlahDokter', 'JumlahPasien', 'JumlahTindakan'));
        } elseif ($this->userRole === 'dokter') {


            $userId = Auth::id();

            $this->dataJumlahPasienDitangani = Tindakan::whereHas('tindakanAsistens', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();

            $this->dataPasienDitanganiFilter = Tindakan::whereHas('tindakanAsistens', function ($query) use ($userId) {
                if (Auth::user()->akses_semua == 0) {
                    $query->where('user_id', $userId);
                }
            })
                ->when($this->tanggal_operasi, function ($query) {
                    $query->whereYear('tanggal_operasi', substr($this->tanggal_operasi, 0, 4))
                        ->whereMonth('tanggal_operasi', substr($this->tanggal_operasi, 5, 2));
                })
                ->when($this->search, function ($query) {
                    $query->whereHas('pasien', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%');
                    });
                })
                ->get();


            $this->totalPasienDitanganiBulanIni = Tindakan::whereHas('tindakanAsistens', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->whereMonth('tanggal_operasi', Carbon::now()->month)
                ->whereYear('tanggal_operasi', Carbon::now()->year)
                ->count();


            // if($this->tanggal_operasi){
            //     dd($this->tanggal_operasi);
            // }

            return view('livewire.pages.dashboard-dokter', [
                'dataJumlahPasienDitangani' => $this->dataJumlahPasienDitangani,
                'dataPasienDitanganiFilter' => $this->dataPasienDitanganiFilter,
                'totalPasienDitanganiBulanIni' => $this->totalPasienDitanganiBulanIni,
            ]);
        } elseif ($this->userRole === 'developer') {
            return view('livewire.pages.admin.dashboard');
        } elseif($this->userRole === 'dpjp'){
            return view('livewire.pages.dashboard-dpjp',[
                'dataJumlahBelumVerifikasi' => Tindakan::where('dpjp_id', Auth::id())
                    ->where('verifikasi', 0)
                    ->count(),
                    'dataJumlahSudahVerifikasi' => Tindakan::where('dpjp_id', Auth::id())
                    ->where('verifikasi', 1)
                    ->count(),
            ]);
        } 
        
        else {
            abort(403, 'Unauthorized action.');
        }
    }
}
