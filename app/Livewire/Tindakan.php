<?php

namespace App\Livewire;

use App\Models\Conference;
use App\Models\FotoTindakan;
use App\Models\Pasien;
use App\Models\Tindakan as TindakanModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Tindakan extends Component
{
    use WithPagination;

    public $idToDelete, $selectedPasien, $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung, $diagnosa, $tanggal_conference, $hasil_conference, $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $on_loop_id, $tanggal_operasi, $relealisasi_tindakan, $kesesuaian, $tindakan_id, $waktu_operasi, $isTambahFoto = false, $fotoPaths;

    protected $listeners = ['deleteTindakanConfirmed'];
    // Foto Tindakan
    public  $fotoTindakanId, $foto, $deskripsi;
    public $isEdit = false;
    public $search = '';

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));
        abort_unless($userPermissions->contains('masterdata-tindakan'), 403);
    }

    // public $fotoPreview = '';

    // public function tambahFoto($id)
    // {
    //     $this->tindakan_id = $id;
    //     $this->isTambahFoto = true;
    //     $this->foto = null;
    //     $this->fotoPreview = null;
    //     return route('create-fototindakan', ['id' => $this->tindakan_id]);
    // }

    public function showModal()
    {
        $this->dispatch('show-modal');
    }
    public function closeModal()
    {
        $this->dispatch('close-modal');
    }
    // public function showFotoTindakan($id)
    // {

    //     $this->fotoTindakanId = $id;
    //     $this->showModal();
    //     $this->fotoPreview = FotoTindakan::where('tindakan_id', $id)->firstOrFail();
    // }

    // public function deleteFoto()
    // {
    //     $this->foto = null;
    //     $this->fotoPreview = null;
    // }

    // public function storeFoto()
    // {
    //     try {
    //         $this->validate([
    //             'tindakan_id' => 'required|exists:tindakans,id',
    //             'foto' => 'required|image|max:2048',
    //             'deskripsi' => 'nullable|string',
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         $this->dispatch('error', collect($e->errors())->flatten()->first());
    //         return;
    //     }

    //     $fotoPath = $this->foto->store('foto_tindakans', 'public');

    //     FotoTindakan::create([
    //         'tindakan_id' => $this->tindakan_id,
    //         'foto' => $fotoPath,
    //         'deskripsi' => $this->deskripsi,
    //     ]);

    //     $this->dispatch('success', 'Foto tindakan created successfully.');
    //     $this->closeModal();
    // }

    public function render()
    {

        return view('livewire.pages.admin.masterdata.tindakan.index', [
            'tindakans' => TindakanModel::with(['pasien', 'operator', 'asisten1', 'asisten2', 'onLoop'])
                ->where(function ($query) {
                    $user = Auth::user();
                    $userId = $user->id;
                    if ($user->roles->pluck('name')->first() === 'dokter' && $user->akses_semua == 0) {
                        $query->where('operator_id', $userId)
                            ->orWhere('asisten1_id', $userId)
                            ->orWhere('asisten2_id', $userId)
                            ->orWhere('on_loop_id', $userId);
                    }
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
                ->latest()
                ->paginate(10),
            'pasiens' => Pasien::all(),
            'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
            'users' => User::all(),
        ]);
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

    public function store()
    {
        try {
            if ($this->selectedPasien === 'manual') {
                $this->validate([
                    'nama' => 'required|string',
                    'usia' => 'required|integer',
                    'nomor_rekam_medis' => 'required|string|unique:pasiens,nomor_rekam_medis',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:L,P',
                    'tipe_jantung' => 'required|in:Jantung Dewasa,Jantung Pediatri & Kongengital',
                ]);

                $pasien = Pasien::create([
                    'nama' => $this->nama,
                    'usia' => $this->usia,
                    'nomor_rekam_medis' => $this->nomor_rekam_medis,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'tipe_jantung' => $this->tipe_jantung,
                ]);
                $this->pasien_id = $pasien->id;

                if ($this->diagnosa && $this->tanggal_conference && $this->hasil_conference) {
                    Conference::create([
                        'pasien_id' => $pasien->id,
                        'diagnosa' => $this->diagnosa,
                        'tanggal_conference' => $this->tanggal_conference,
                        'hasil_conference' => $this->hasil_conference,
                    ]);
                }
            } else {
                $this->pasien_id = $this->selectedPasien;
                $newConference = Conference::create([
                    'pasien_id' => $this->pasien_id,
                    'diagnosa' => $this->diagnosa,
                    'tanggal_conference' => $this->tanggal_conference,
                    'hasil_conference' => $this->hasil_conference,
                ]);
            }

            $this->validate([
                'pasien_id' => 'required|exists:pasiens,id',
                'operator_id' => 'required|exists:users,id',
                'asisten1_id' => 'required|exists:users,id',
                'asisten2_id' => 'required|exists:users,id',
                'on_loop_id' => 'required|exists:users,id',
                'tanggal_operasi' => 'required|date',
                'relealisasi_tindakan' => 'required|string',
                'kesesuaian' => 'required|string',
            ]);

            TindakanModel::create([
                'pasien_id' => $this->pasien_id,
                'operator_id' => $this->operator_id,
                'asisten1_id' => $this->asisten1_id,
                'asisten2_id' => $this->asisten2_id,
                'on_loop_id' => $this->on_loop_id,
                'tanggal_operasi' => $this->tanggal_operasi,
                'relealisasi_tindakan' => $this->relealisasi_tindakan,
                'kesesuaian' => $this->kesesuaian,
            ]);

            $this->dispatch('success', 'Tindakan berhasil disimpan.');
            $this->resetForm();
            return redirect()->route('tindakan');
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }
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
