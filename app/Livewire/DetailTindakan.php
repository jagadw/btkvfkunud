<?php

namespace App\Livewire;

use App\Models\Tindakan;
use App\Models\Pasien;
use App\Models\Conference;
use App\Models\TindakanAsisten;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("layouts.admin")]
class DetailTindakan extends Component
{
    public $selectedPasien;
    public $idTindakan, $nama, $usia, $nomor_rekam_medis, $tanggal_lahir, $jenis_kelamin, $tipe_jantung, $asal_rumah_sakit;
    public $diagnosa, $tanggal_conference, $hasil_conference;
    public $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $dpjp_id;
    public $tanggal_operasi, $realisasi_tindakan, $kesesuaian, $tindakan_id, $divisi, $on,
        $asistens = [
            [
                'user_id' => '',
                'role' => '',
                'deskripsi' => '',
            ]
        ];
    public $on_loop = [
        'user_id' => '',
        'role' => 'Observer',
        'deskripsi' => '',
    ];
    public $nama_tindakan, $laporan_tindakan, $foto_tindakan, $foto_tindakan_lama;
    public $isEdit = false;
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
                $this->operator_id = $dataTindakan->operator_id ?? null;
                $this->asisten1_id = $dataTindakan->asisten1_id ?? null;
                $this->asisten2_id = $dataTindakan->asisten2_id ?? null;
                $this->tanggal_operasi = $dataTindakan->tanggal_operasi ? Carbon::parse($dataTindakan->tanggal_operasi)->format('Y-m-d') : null;
                $this->realisasi_tindakan = $dataTindakan->realisasi_tindakan ?? null;
                $this->kesesuaian = $dataTindakan->kesesuaian ?? null;
                $this->tindakan_id = $this->idTindakan;
                $this->nama_tindakan = $dataTindakan->nama_tindakan ?? null;
                $this->divisi = $dataTindakan->divisi ?? null;
                $this->laporan_tindakan = $dataTindakan->laporan_tindakan ?? null;
                $this->foto_tindakan_lama = $dataTindakan->foto_tindakan ?? null;
                $this->dpjp_id = $dataTindakan->dpjp_id ?? null;
                $this->diagnosa = $dataTindakan->diagnosa;
                // dd($this->diagnosa);
                if ($pasien) {
                    $this->nama = $pasien->nama;
                    $this->usia = $pasien->usia;
                    $this->nomor_rekam_medis = $pasien->nomor_rekam_medis;
                    $this->tanggal_lahir = $pasien->tanggal_lahir;
                    $this->jenis_kelamin = $pasien->jenis_kelamin;
                    $this->tipe_jantung = $pasien->tipe_jantung;
                    $this->asal_rumah_sakit = $pasien->asal_rumah_sakit;
                }
                $conference = Conference::where('tindakan_id', $dataTindakan->id)->first();
                if ($conference) {
                    $this->diagnosa = $conference->diagnosa ?? $dataTindakan->diagnosa;
                    $this->tanggal_conference = $conference->tanggal_conference ? Carbon::parse($conference->tanggal_conference)->format('Y-m-d') : null;
                    $this->hasil_conference = $conference->hasil_conference;
                    $this->realisasi_tindakan = $conference->realisasi_tindakan;
                    $this->kesesuaian = $conference->kesesuaian !== null ? (bool) $conference->kesesuaian : null;
                } else {
                    $this->diagnosa = $dataTindakan->diagnosa ?? null;
                    $this->tanggal_conference = null;
                    $this->hasil_conference = null;
                }

                $asistens = TindakanAsisten::where('tindakan_id', $dataTindakan->id)
                    ->where('tipe', 'asisten')
                    ->orderBy('urutan')
                    ->get();
                if ($asistens->count()) {
                    $this->asistens = [];
                    foreach ($asistens as $asisten) {
                        $this->asistens[] = [
                            'user_id' => $asisten->user_id,
                            'nama' => $asisten->user?->mahasiswa?->nama ?? '-',
                            'role' => $asisten->role,
                            'deskripsi' => $asisten?->deskripsi,
                        ];
                    }
                } else {
                    $this->asistens = [
                        [
                            'user_id' => '',
                            'role' => '',
                            'deskripsi' => '',
                        ]
                    ];
                }

                $onLoop = TindakanAsisten::where('tindakan_id', $dataTindakan->id)
                    ->where('tipe', 'onloop')
                    ->first();
                if ($onLoop) {
                    $this->on_loop = [
                        'nama' => $onLoop->user?->mahasiswa?->nama ?? '-',
                        'user_id' => $onLoop->user_id,
                        'role' => $onLoop->role ?? 'Observer',
                        'deskripsi' => $onLoop?->deskripsi ?? '',

                    ];
                    // dd($this->on_loop);
                } else {
                    $this->on_loop = [
                        'user_id' => '',
                        'role' => 'Observer',
                        'deskripsi' => '',
                    ];
                }

                $this->isEdit = true;
            }
        } else {
            $this->idTindakan = null;
        }
    }

    public function render()
    {
        return view(
            'livewire.pages.admin.masterdata.tindakan.detail',
            [
                'pasien' => Pasien::where('id',$this->pasien_id)->get(),
                'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
                'users' => User::all(),
                'dpjp' => User::with('dpjp')->where('id', $this->dpjp_id)->get(),
              
            ]
        );
    }
}
