<?php

namespace App\Livewire;

use App\Models\Conference;
use App\Models\Pasien;
use App\Models\Tindakan;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CreateTindakan extends Component
{
    public $selectedPasien;
    public $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung;
    public $diagnosa, $tanggal_conference, $hasil_conference;
    public $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $on_loop_id;
    public $tanggal_operasi, $relealisasi_tindakan, $kesesuaian, $tindakan_id;
    public $isEdit = false;

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
                  if ($this->diagnosa && $this->tanggal_conference && $this->hasil_conference) {
                    Conference::create([
                        'pasien_id' => $this->pasien_id,
                        'diagnosa' => $this->diagnosa,
                        'tanggal_conference' => $this->tanggal_conference,
                        'hasil_conference' => $this->hasil_conference,
                    ]);
                }
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

            Tindakan::create([
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
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        $data = Tindakan::findOrFail($id);
        $this->fill($data->only([
            'pasien_id',
            'operator_id',
            'asisten1_id',
            'asisten2_id',
            'on_loop_id',
            'tanggal_operasi',
            'relealisasi_tindakan',
            'kesesuaian'
        ]));
        $this->selectedPasien = $data->pasien_id;
        $this->tindakan_id = $id;
        $this->isEdit = true;
    }

    public function update()
    {
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

        Tindakan::where('id', $this->tindakan_id)->update([
            'pasien_id' => $this->pasien_id,
            'operator_id' => $this->operator_id,
            'asisten1_id' => $this->asisten1_id,
            'asisten2_id' => $this->asisten2_id,
            'on_loop_id' => $this->on_loop_id,
            'tanggal_operasi' => $this->tanggal_operasi,
            'relealisasi_tindakan' => $this->relealisasi_tindakan,
            'kesesuaian' => $this->kesesuaian,
        ]);

        $this->dispatch('success', 'Tindakan berhasil diperbarui.');
        $this->resetForm();
    }

    public function deleteTindakan($id)
    {
        Tindakan::findOrFail($id)->delete();
        $this->dispatch('delete-success', 'Tindakan berhasil dihapus.');
    }
    public function render()
    {
        return view('livewire.pages.admin.masterdata.tindakan.create-tindakan', [
            'pasiens' => Pasien::all(),
            'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
            'users' => User::all(),
        ]);
    }
}
