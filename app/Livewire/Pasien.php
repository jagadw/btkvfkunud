<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Pasien AS PasienModel;

#[Layout('layouts.admin')]
class Pasien extends Component
{
    use WithPagination;

    public $idToDelete, $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung;
    public $pasien_id, $isEdit = false, $search = '';
    protected $listeners = ['deletePasienConfirmed','updateUsia'];
    protected $rules = [
        'nama' => 'required|string',
        'usia' => 'required|string',
        'nomor_rekam_medis' => 'required|string|unique:pasiens,nomor_rekam_medis',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|string',
        'tipe_jantung' => 'required|string',
    ];

    public function updateUsia()
    {
        if ($this->tanggal_lahir) {
            $birthDate = \Carbon\Carbon::parse($this->tanggal_lahir);
            $now = now();
            $diff = $birthDate->diff($now);
            $this->usia = "{$diff->y} tahun {$diff->m} bulan {$diff->d} hari";
        } else {
            $this->usia = null;
        }
    }

    public function render()
    {
    
        if($this->search) {
            dd('Search triggered: ' . $this->search);
        }
        return view('livewire.pages.admin.masterdata.pasien.index', [
            'pasiens' => PasienModel::when($this->search, function ($query) {
                return $query->where('nama', 'like', '%' . $this->search . '%');
            })->get(),
        ]);
    }

    public function showModal()
    {
        $this->dispatch('show-modal');
    }
    public function closeModal()
    {
        $this->reset([
            'nama', 'usia', 'nomor_rekam_medis',
            'tanggal_lahir', 'jenis_kelamin',
            'tipe_jantung', 'pasien_id', 'isEdit'
        ]);
        $this->dispatch('close-modal');
    }
    public function create()
    {
        $this->showModal();
    }
    
    public function store()
    {
        $this->validate();

        PasienModel::create([
            'nama' => $this->nama,
            'usia' => $this->usia,
            'nomor_rekam_medis' => $this->nomor_rekam_medis,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tipe_jantung' => $this->tipe_jantung,
        ]);

        $this->dispatch('success', message: 'Data has been added.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = PasienModel::findOrFail($id);
        $this->fill($data->only([
            'nama', 'usia', 'nomor_rekam_medis',
            'tanggal_lahir', 'jenis_kelamin',
            'tipe_jantung'
        ]));
        $this->pasien_id = $id;
        $this->showModal();
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string',
            'usia' => 'required|string',
            'nomor_rekam_medis' => 'required|string|unique:pasiens,nomor_rekam_medis,' . $this->pasien_id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'tipe_jantung' => 'required|string',
        ]);

        PasienModel::where('id', $this->pasien_id)->update([
            'nama' => $this->nama,
            'usia' => $this->usia,
            'nomor_rekam_medis' => $this->nomor_rekam_medis,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tipe_jantung' => $this->tipe_jantung,
        ]);

        $this->dispatch('success', message: 'Data has been updated.');
        $this->closeModal();
    }


    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin Ingin Menghapus Data Pasien Ini?');
    }
    public function deletePasienConfirmed()
    {
        $pasienData = Pasien::where('id', $this->idToDelete);
        $pasienData->delete();
        $this->dispatch('delete-success', 'Data Pasien Sudah di Hapus');
    }
}
