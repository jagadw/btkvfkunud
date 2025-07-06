<?php

namespace App\Livewire;

use App\Models\Conference;
use App\Models\FotoTindakan;
use App\Models\Pasien;
use App\Models\Tindakan as TindakanModel;
use App\Models\TindakanAsisten;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Tindakan extends Component
{
    use WithPagination;

    public $idToDelete, $selectedPasien, $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung, $diagnosa, $tanggal_conference, $hasil_conference, $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $on_loop_id, $tanggal_operasi_start, $tanggal_operasi_end, $relealisasi_tindakan, $kesesuaian, $tindakan_id, $waktu_operasi, $isTambahFoto = false, $fotoPaths, $fotoPreview, $selectedDivisi;

    public $isExport = false;
    protected $listeners = ['deleteTindakanConfirmed', 'divisiChanged'];
    // Foto Tindakan
    public  $fotoTindakanId, $foto, $deskripsi;
    public $isEdit = false;
    public $search = '';
    public $tindakanForPrint;
    public $selectedDokter;

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));
        abort_unless($userPermissions->contains('masterdata-tindakan'), 403);
    }
    public function showModal()
    {
        $this->dispatch('show-modal');
    }
    public function closeModal()
    {
        $this->dispatch('hide-modal');
    }

    public function showFoto($foto)
    {
        $this->fotoPreview = $foto;
        $this->showModal();
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
            
        }
        $this->dispatch('download-pdf', $singkatan);
    }
    public function render()
    {
        if (!$this->isExport) {
            $this->tindakanForPrint = TindakanModel::with(['pasien', 'dpjp', 'tindakanAsistens.user'])
                ->where(function ($query) {
                    $user = Auth::user();
                    $userId = $user->id;
                  
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

            return view(
                'livewire.pages.admin.masterdata.tindakan.index',
                [
                    'tindakans' => $this->tindakanForPrint,
                    'pasiens' => Pasien::all(),
                    'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
                    'users' => User::all(),
                ]
            );
        } else {
            $tindakans = TindakanModel::with('koordinator')->where(function ($query) {
                $user = Auth::user();
                $userId = $user->id;

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

    public function resetForm()
    {
        $this->reset([
            'selectedPasien',
            'nama',
            'usia',
            'nomor_rekam_medis',
            'tanggal_lahir',
            'jenis_kelamin',
            'tipe_jantung',
            'diagnosa',
            'tanggal_conference',
            'hasil_conference',
            'pasien_id',
            'operator_id',
            'asisten1_id',
            'asisten2_id',
            'on_loop_id',
            'tanggal_operasi',
            'relealisasi_tindakan',
            'kesesuaian',
            'tindakan_id',
            'isEdit'
        ]);
    }

    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin ingin menghapus tindakan ini?');
    }

    public function deleteTindakanConfirmed()
    {
        $deleteTindakan = TindakanModel::findOrFail($this->idToDelete);
        $deleteTindakan->delete();

        $this->dispatch('delete-success', 'Tindakan berhasil dihapus.');
    }
}
