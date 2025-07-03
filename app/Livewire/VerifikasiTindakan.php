<?php

namespace App\Livewire;

use App\Models\Tindakan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("layouts.admin")]
class VerifikasiTindakan extends Component
{
    public $tanggal_operasi_start, $tanggal_operasi_end, $search = '';
    public $selectedTindakans = [], $fotoPreview;
    public function showFoto($foto)
    {
        $this->fotoPreview = $foto;
        $this->dispatch('show-modal');
    }
    public function closeModal()
    {
        $this->dispatch('hide-modal');
    }


    public function toggleSelectAll()
    {
        $tindakans = $this->getTindakans();
        if (count($this->selectedTindakans) === $tindakans->count()) {
            $this->selectedTindakans = [];
        } else {
            $this->selectedTindakans = $tindakans->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
    }

    protected function getTindakans()
    {
        return Tindakan::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
            ->where('dpjp_id', Auth::id())
            ->where('verifikasi', 0)
            ->when($this->tanggal_operasi_start && $this->tanggal_operasi_end, function ($query) {
                $query->whereBetween('tanggal_operasi', [$this->tanggal_operasi_start, $this->tanggal_operasi_end]);
            })
            ->when($this->search, function ($query) {
                $search = $this->search;
                $query->whereHas('pasien', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nomor_rekam_medis', 'like', "%{$search}%");
                });
            })
            ->get();
    }

    public function verifikasi()
    {
        try {
            Tindakan::whereIn('id', $this->selectedTindakans)->update(['verifikasi' => 1]);
            $this->selectedTindakans = [];
            $this->dispatch('success',  'Verifikasi berhasil.');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }
    public function verifikasiSingle($tindakanId)
    {
        try {
            Tindakan::where('id', $tindakanId)->update(['verifikasi' => 1]);
            $this->dispatch('success', 'Verifikasi berhasil.');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.pages.admin.masterdata.tindakan.verifikasi-tindakan', [
            'tindakans' => $this->getTindakans()
        ]);
    }

    // public function render()
    // {
    //     return view('livewire.pages.admin.masterdata.tindakan.verifikasi-tindakan', [
    //         'tindakans' => Tindakan::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
    //         ->where('dpjp_id', Auth::id())
    //         ->where('verifikasi', 0)
    //         ->when($this->tanggal_operasi_start && $this->tanggal_operasi_end, function ($query) {
    //             $query->whereBetween('tanggal_operasi', [$this->tanggal_operasi_start, $this->tanggal_operasi_end]);
    //         })
    //         ->when($this->search, function ($query) {
    //             $query->whereHas('pasien', function ($q) {
    //                 $q->where('nama', 'like', '%' . $this->search . '%')
    //                     ->orWhere('nomor_rekam_medis', 'like', '%' . $this->search . '%');
    //             });
    //         })
    //         ->get()
    //     ]);
    // }
}
