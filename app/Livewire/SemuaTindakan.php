<?php

namespace App\Livewire;

use App\Models\Dpjp;
use App\Models\TindakanAsisten;
use Livewire\Component;
use App\Models\Tindakan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class SemuaTindakan extends Component
{
    public $tanggal_operasi, $search = '', $fotoPreview, $selectedDivisi, $selectedDokter;
    public $tanggal_operasi_start, $tanggal_operasi_end, $isExport, $tindakanForPrint, $selectedDPJP;
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

    public function exportPDF()
    {
        $this->isExport = true;
    }
    public function downloadPDF()
    {


        $singkatan = '';
        if ($this->tindakanForPrint->first()->divisi) {
            preg_match_all('/\b([A-Za-z])/', $this->tindakanForPrint->first()->divisi, $matches);
            $singkatan = strtoupper(implode('', $matches[1]));
            $this->dispatch('download-pdf', $singkatan);
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

        if (!$this->isExport) {
            $this->tindakanForPrint = Tindakan::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
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
                    ->when($this->selectedDokter, function ($query) {
                        $query->whereHas('tindakanAsistens', function ($q) {
                            $q->where('user_id', $this->selectedDokter);
                        });
                    })
                    ->when($this->selectedDPJP, function ($query) {
                        $query->where('dpjp_id', $this->selectedDPJP);
                    })
                    ->where('verifikasi', 1)
                    ->get();
            return view('livewire.pages.admin.masterdata.tindakan.semua-tindakan', [
                'tindakans' => $this->tindakanForPrint,
                'pasiens' => Pasien::all(),
                'dokters' => User::has('mahasiswa')->get(),
                'users' => User::all(),
                'dpjps' => User::has('dpjp')->get(),
            ]);
        } else {
            $tindakans = Tindakan::where(function ($query) {
                // $user = Auth::user();
                $userId = $this->selectedDokter;
                
                $query->whereHas('tindakanAsistens', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })
                ->when($this->tanggal_operasi_start && $this->tanggal_operasi_end, function ($query) {
                    $query->whereBetween('tanggal_operasi', [$this->tanggal_operasi_start, $this->tanggal_operasi_end]);
                })
                ->when($this->selectedDivisi, function ($query) {
                    $query->where('divisi', $this->selectedDivisi);
                })
                ->when($this->search, function ($query) {
                    $query->whereHas('pasien', function ($q) {
                        $q->where('nama', 'like', '%' . $this->search . '%')
                            ->orWhere('nomor_rekam_medis', 'like', '%' . $this->search . '%');
                    });
                })
                ->get();
            $tindakanAsistens = TindakanAsisten::whereIn('tindakan_id', $tindakans->pluck('id')->toArray())->get();

            return view('livewire.pages.admin.masterdata.tindakan.laporan', [
                'tindakans' => $tindakans,
                'tindakanAsistens' => $tindakanAsistens,

            ]);
        }
    }
}
