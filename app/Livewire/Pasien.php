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

    public $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung;
    public $pasien_id, $isEdit = false, $search = '';

    protected $rules = [
        'nama' => 'required|string',
        'usia' => 'required|integer',
        'nomor_rekam_medis' => 'required|string|unique:pasiens,nomor_rekam_medis',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|string',
        'tipe_jantung' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PasienModel::query()
            ->when($this->search, fn($q) =>
                $q->where('nama', 'like', '%' . $this->search . '%')
            )
            ->latest();

        return view('livewire.pages.admin.masterdata.pasien.index', [
            'pasiens' => $query->paginate(10),
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'nama', 'usia', 'nomor_rekam_medis',
            'tanggal_lahir', 'jenis_kelamin',
            'tipe_jantung', 'pasien_id', 'isEdit'
        ]);
        $this->resetValidation();
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
        $this->resetForm();
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
        $this->isEdit = true;
    }

    public function updatePasien()
    {
        $this->validate([
            'nama' => 'required|string',
            'usia' => 'required|integer',
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
        $this->resetForm();
    }

    public function deletePasien($id)
    {
        PasienModel::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Data has been deleted.');
    }
}
