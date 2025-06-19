<?php

namespace App\Livewire;

use App\Models\Conference;
use App\Models\Pasien;
use App\Models\Tindakan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class CreateTindakan extends Component
{
    public $selectedPasien;
    public $idTindakan, $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung;
    public $diagnosa, $tanggal_conference, $hasil_conference;
    public $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $on_loop_id;
    public $tanggal_operasi, $relealisasi_tindakan, $kesesuaian, $tindakan_id;
    public $isEdit = false;
    protected $listeners = ['updateUsia','deleteTindakanConfirmed'];

    public function mount($id = null)
    {
        if ($id) {
            $this->idTindakan = decrypt($id);
            $dataTindakan = Tindakan::find($this->idTindakan);
            $this->isEdit = true;
            if ($dataTindakan) {
                $pasien = Pasien::find($dataTindakan->pasien_id);
                $this->selectedPasien = $dataTindakan->pasien_id;
                $this->pasien_id = $dataTindakan->pasien_id;
                $this->operator_id = $dataTindakan->operator_id;
                $this->asisten1_id = $dataTindakan->asisten1_id;
                $this->asisten2_id = $dataTindakan->asisten2_id;
                $this->on_loop_id = $dataTindakan->on_loop_id;
                $this->tanggal_operasi = Carbon::parse($dataTindakan->tanggal_operasi)->format('Y-m-d');

                $this->relealisasi_tindakan = $dataTindakan->relealisasi_tindakan;
                $this->kesesuaian = $dataTindakan->kesesuaian;
                $this->tindakan_id = $this->idTindakan;

                if ($pasien) {
                    $this->nama = $pasien->nama;
                    $this->usia = $pasien->usia;
                    $this->nomor_rekam_medis = $pasien->nomor_rekam_medis;
                    $this->tanggal_lahir = $pasien->tanggal_lahir;
                    $this->jenis_kelamin = $pasien->jenis_kelamin;
                    $this->tipe_jantung = $pasien->tipe_jantung;
                }

                // Ambil data conference jika ada
                $conference = Conference::where('tindakan_id', $dataTindakan->id)->first();
                if ($conference) {
                    $this->diagnosa = $conference->diagnosa;
                    $this->tanggal_conference = Carbon::parse($conference->tanggal_conference)->format('Y-m-d');
                    $this->hasil_conference = $conference->hasil_conference;
                } else {
                    $this->diagnosa = null;
                    $this->tanggal_conference = null;
                    $this->hasil_conference = null;
                }

                $this->isEdit = true;
            }
        } else {
            $this->idTindakan = null;
        }
    }
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
                    'usia' => 'required|string',
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
            } else {
                $this->pasien_id = $this->selectedPasien;
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

            $tindakan = Tindakan::create([
                'pasien_id' => $this->pasien_id,
                'operator_id' => $this->operator_id,
                'asisten1_id' => $this->asisten1_id,
                'asisten2_id' => $this->asisten2_id,
                'on_loop_id' => $this->on_loop_id,
                'tanggal_operasi' => $this->tanggal_operasi,
                'relealisasi_tindakan' => $this->relealisasi_tindakan,
                'kesesuaian' => $this->kesesuaian,
            ]);

            $this->tindakan_id = $tindakan->id;

            if ($this->diagnosa && $this->tanggal_conference && $this->hasil_conference) {
                Conference::create([
                    'tindakan_id' => $this->tindakan_id,
                    'pasien_id' => $this->pasien_id,
                    'diagnosa' => $this->diagnosa,
                    'tanggal_conference' => $this->tanggal_conference,
                    'hasil_conference' => $this->hasil_conference,
                ]);
            }

            $this->dispatch('success', 'Tindakan berhasil disimpan.');
            $this->resetForm();
            return redirect()->route('tindakan');
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }
    }

    public function update()
    {
        try {
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

            if (
                !empty($this->diagnosa) &&
                !empty($this->tanggal_conference) &&
                !empty($this->hasil_conference)
            ) {
                Conference::updateOrCreate(
                    [
                        'tindakan_id' => $this->tindakan_id,
                    ],
                    [
                        'pasien_id' => $this->pasien_id,
                        'diagnosa' => $this->diagnosa,
                        'tanggal_conference' => $this->tanggal_conference,
                        'hasil_conference' => $this->hasil_conference,
                    ]
                );
            }

            $this->dispatch('success', 'Tindakan berhasil diperbarui.');
            return redirect()->route('tindakan');
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        if ($this->idTindakan) {
            return view('livewire.pages.admin.masterdata.tindakan.edit-tindakan', [
                'pasiens' => Pasien::all(),
                'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
                'users' => User::all(),
            ]);
        } else {
            return view('livewire.pages.admin.masterdata.tindakan.create-tindakan', [
                'pasiens' => Pasien::all(),
                'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
                'users' => User::all(),
            ]);
        }
    }
}
