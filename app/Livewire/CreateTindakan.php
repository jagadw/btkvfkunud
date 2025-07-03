<?php

namespace App\Livewire;

use App\Models\Conference;
use App\Models\Pasien;
use App\Models\Tindakan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\TindakanAsisten;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
// use Livewire\TemporaryUploadedFile;

#[Layout('layouts.admin')]
class CreateTindakan extends Component
{
    use WithFileUploads;
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
    protected $listeners = ['updateUsia', 'deleteTindakanConfirmed', 'divisiChanged'];

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
            'on_loop',
            'tanggal_operasi',
            'realisasi_tindakan',
            'kesesuaian',
            'tindakan_id',
            'isEdit'
        ]);
    }

    public function addAsisten()
    {
        $this->asistens[] = [
            'user_id' => '',
            'role' => '',
            'deskripsi' => '',
        ];
    }


    public function removeAsisten($index)
    {
        if (count($this->asistens) > 1) {
            array_splice($this->asistens, $index, 1);
            $this->asistens = array_values($this->asistens); // reindex
        }
    }
    public function removeFoto()
    {
        $this->foto_tindakan = null;
    }


    public function updatedFotoTindakan()
    {
        if ($this->idTindakan) {
            $this->foto_tindakan_lama = null;
        }
        $this->dispatch('success', 'Foto berhasil di-load.');
    }

    public function store()
    {
        try {
            // Gabungkan semua rules validasi di awal
            $rules = [
                'selectedPasien' => 'required',
                'dpjp_id' => 'required|exists:users,id',
                'nama_tindakan' => 'required|string',
                'divisi' => 'required|in:Jantung Dewasa,Jantung Pediatri & Kongengital,Toraks,Vaskular,Endovaskular',
                'diagnosa' => 'required|string',
                'tanggal_operasi' => 'required|date',
                'laporan_tindakan' => 'required|string',
                'foto_tindakan' => 'nullable|image|mimes:jpeg,png,jpg|max:4096', // Maksimal 4MB
            ];

            if ($this->selectedPasien === 'manual') {
                $rules = array_merge($rules, [
                    'nama' => 'required|string',
                    'nomor_rekam_medis' => 'required|numeric|digits:8|unique:pasiens,nomor_rekam_medis',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:L,P',
                    'asal_rumah_sakit' => 'required|string',
                ]);
            }

            if (in_array($this->divisi, ['Jantung Dewasa', 'Jantung Pediatri & Kongengital'])) {
                $rules = array_merge($rules, [
                    'tanggal_conference' => 'required|date',
                    'hasil_conference' => 'required|string',
                    'realisasi_tindakan' => 'required|string',
                    'kesesuaian' => 'required|boolean',
                ]);
            }

            // Validasi asisten: minimal satu asisten diisi dan semua field wajib diisi jika user_id ada
            if (empty($this->asistens) || !collect($this->asistens)->filter(fn($a) => !empty($a['user_id']))->count()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'asistens' => ['Minimal satu asisten harus diisi.'],
                ]);
            }

            if (is_array($this->asistens)) {
                foreach ($this->asistens as $idx => $asisten) {
                    if (!empty($asisten['user_id'])) {
                        $rules["asistens.$idx.user_id"] = 'required|exists:users,id';
                        $rules["asistens.$idx.role"] = 'required|string|max:50';
                        $rules["asistens.$idx.deskripsi"] = 'required|string';
                    }
                }
            }

            // Validasi on_loop: semua field wajib diisi jika user_id ada
            if (empty($this->on_loop['user_id'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'on_loop.user_id' => ['Data on loop wajib diisi.'],
                ]);
            } else {
                $rules['on_loop.user_id'] = 'required|exists:users,id';
                $rules['on_loop.role'] = 'required|string|max:50';
                $rules['on_loop.deskripsi'] = 'required|string|';
            }

            // Validasi semua inputan sebelum insert
            $validated = $this->validate($rules);

            // Insert pasien jika manual dan belum ada
            if ($this->selectedPasien === 'manual') {
                $pasien = Pasien::where('nomor_rekam_medis', $this->nomor_rekam_medis)->first();
                if (!$pasien) {
                    $pasien = Pasien::create([
                        'nama' => $this->nama,
                        'nomor_rekam_medis' => $this->nomor_rekam_medis,
                        'tanggal_lahir' => $this->tanggal_lahir,
                        'jenis_kelamin' => $this->jenis_kelamin,
                        'asal_rumah_sakit' => $this->asal_rumah_sakit,
                    ]);
                }
                $this->pasien_id = $pasien->id;
            } else {
                $this->pasien_id = $this->selectedPasien;
            }

            $fotoPath = null;
            if ($this->foto_tindakan) {
                $fotoPath = Storage::disk('public')->putFile('foto-tindakan', $this->foto_tindakan);
            }

            $tindakan = Tindakan::create([
                'pasien_id' => $this->pasien_id,
                'dpjp_id' => $this->dpjp_id,
                'nama_tindakan' => $this->nama_tindakan,
                'divisi' => $this->divisi,
                'diagnosa' => $this->diagnosa,
                'tanggal_operasi' => $this->tanggal_operasi,
                'laporan_tindakan' => $this->laporan_tindakan,
                'foto_tindakan' => $fotoPath ?? null,
            ]);
            $this->tindakan_id = $tindakan->id;

            if (in_array($this->divisi, ['Jantung Dewasa', 'Jantung Pediatri & Kongengital'])) {
                Conference::create([
                    'tindakan_id' => $tindakan->id,
                    'pasien_id' => $this->pasien_id,
                    'diagnosa' => $this->diagnosa,
                    'tanggal_conference' => $this->tanggal_conference,
                    'hasil_conference' => $this->hasil_conference,
                    'realisasi_tindakan' => $this->realisasi_tindakan,
                    'kesesuaian' => (bool) $this->kesesuaian,
                ]);
            }

            // Simpan asisten
            if (is_array($this->asistens)) {
                foreach ($this->asistens as $idx => $asisten) {
                    if (!empty($asisten['user_id'])) {
                        TindakanAsisten::create([
                            'tindakan_id' => $tindakan->id,
                            'user_id' => $asisten['user_id'],
                            'tipe' => 'asisten',
                            'urutan' => $idx + 1,
                            'role' => isset($asisten['role']) && strlen($asisten['role']) <= 50 ? $asisten['role'] : null,
                            'deskripsi' => isset($asisten['deskripsi']) && strlen($asisten['deskripsi']) <= 255 ? $asisten['deskripsi'] : null,
                        ]);
                    }
                }
            }

            // Simpan on loop
            if (!empty($this->on_loop['user_id'])) {
                TindakanAsisten::create([
                    'tindakan_id' => $tindakan->id,
                    'user_id' => $this->on_loop['user_id'],
                    'tipe' => 'onloop',
                    'urutan' => null,
                    'role' => isset($this->on_loop['role']) && strlen($this->on_loop['role']) <= 50 ? $this->on_loop['role'] : 'Observer',
                    'deskripsi' => isset($this->on_loop['deskripsi']) && strlen($this->on_loop['deskripsi']) <= 255 ? $this->on_loop['deskripsi'] : null,
                ]);
            }

            $this->dispatch('success', 'Tindakan berhasil disimpan.');
            $this->resetForm();
            return redirect()->route('tindakan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $messages = $e->validator->errors()->all();
            $this->dispatch('error', implode(' ', $messages));
            return;
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.');
            return;
        }
    }


    public function update()
    {
        try {
            // Validasi
            $rules = [
                'selectedPasien' => 'required',
                'dpjp_id' => 'required|exists:users,id',
                'nama_tindakan' => 'required|string',
                'divisi' => 'required|in:Jantung Dewasa,Jantung Pediatri & Kongengital,Toraks,Vaskular,Endovaskular',
                'diagnosa' => 'required|string',
                'tanggal_operasi' => 'required|date',
                'laporan_tindakan' => 'required|string',
                'foto_tindakan' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            ];

            if ($this->selectedPasien === 'manual') {
                $rules = array_merge($rules, [
                    'nama' => 'required|string',
                    'nomor_rekam_medis' => 'required|numeric|digits:8|unique:pasiens,nomor_rekam_medis,' . ($this->pasien_id ?? 'NULL'),
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:L,P',
                    'asal_rumah_sakit' => 'required|string',
                ]);
            }

            if (in_array($this->divisi, ['Jantung Dewasa', 'Jantung Pediatri & Kongengital'])) {
                $rules = array_merge($rules, [
                    'tanggal_conference' => 'required|date',
                    'hasil_conference' => 'required|string',
                    'realisasi_tindakan' => 'required|string',
                    'kesesuaian' => 'required|boolean',
                ]);
            }

            foreach ($this->asistens ?? [] as $idx => $asisten) {
                if (!empty($asisten['user_id'])) {
                    $rules["asistens.$idx.user_id"] = 'required|exists:users,id';
                    $rules["asistens.$idx.role"] = 'required|string|max:50';
                    $rules["asistens.$idx.deskripsi"] = 'required|string';
                }
            }

            if (!empty($this->on_loop['user_id'])) {
                $rules['on_loop.user_id'] = 'required|exists:users,id';
                $rules['on_loop.role'] = 'required|string|max:50';
                $rules['on_loop.deskripsi'] = 'required|string';
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'on_loop.user_id' => ['Data on loop wajib diisi.'],
                ]);
            }

            $this->validate($rules);

            // 🔥 Ambil data tindakan existing
            $tindakan = Tindakan::where('id', $this->idTindakan)->first();
            if (!$tindakan) {
                $this->dispatch('error', 'Data tindakan tidak ditemukan.');
                return;
            }

            // Pasien
            if ($this->selectedPasien === 'manual') {
                $pasien = Pasien::where('id', $this->pasien_id)->first();
                if ($pasien) {
                    $pasien->update([
                        'nama' => $this->nama,
                        'nomor_rekam_medis' => $this->nomor_rekam_medis,
                        'tanggal_lahir' => $this->tanggal_lahir,
                        'jenis_kelamin' => $this->jenis_kelamin,
                        'asal_rumah_sakit' => $this->asal_rumah_sakit,
                    ]);
                }
                $this->pasien_id = $pasien ? $pasien->id : $this->pasien_id;
            } else {
                $this->pasien_id = $this->selectedPasien;
            }

            // Foto
            $fotoPath = $tindakan->foto_tindakan;
            if ($this->foto_tindakan && $this->foto_tindakan instanceof TemporaryUploadedFile) {
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = Storage::disk('public')->putFile('foto-tindakan', $this->foto_tindakan);
            }

            // Update tindakan
            $tindakan->update([
                'pasien_id' => $this->pasien_id,
                'dpjp_id' => $this->dpjp_id,
                'nama_tindakan' => $this->nama_tindakan,
                'divisi' => $this->divisi,
                'diagnosa' => $this->diagnosa,
                'tanggal_operasi' => $this->tanggal_operasi,
                'laporan_tindakan' => $this->laporan_tindakan,
                'foto_tindakan' => $fotoPath,
            ]);

            // Conference (hanya update jika sudah ada)
            $conference = Conference::where('tindakan_id', $tindakan->id)->first();
            if ($conference) {
                if (in_array($this->divisi, ['Jantung Dewasa', 'Jantung Pediatri & Kongengital'])) {
                    $conference->update([
                        'pasien_id' => $this->pasien_id,
                        'diagnosa' => $this->diagnosa,
                        'tanggal_conference' => $this->tanggal_conference,
                        'hasil_conference' => $this->hasil_conference,
                        'realisasi_tindakan' => $this->realisasi_tindakan,
                        'kesesuaian' => (bool) $this->kesesuaian,
                    ]);
                } else {
                    $conference->delete();
                }
            }

            // Asisten (hapus dulu semua lalu insert ulang)
            TindakanAsisten::where('tindakan_id', $tindakan->id)->where('tipe', 'asisten')->delete();

            foreach ($this->asistens ?? [] as $idx => $asisten) {
                if (!empty($asisten['user_id'])) {
                    TindakanAsisten::create([
                        'tindakan_id' => $tindakan->id,
                        'user_id' => $asisten['user_id'],
                        'tipe' => 'asisten',
                        'urutan' => $idx + 1,
                        'role' => $asisten['role'],
                        'deskripsi' => $asisten['deskripsi'],
                    ]);
                }
            }

            // On loop (hapus dulu, insert ulang satu data)
            TindakanAsisten::where('tindakan_id', $tindakan->id)->where('tipe', 'onloop')->delete();

            TindakanAsisten::create([
                'tindakan_id' => $tindakan->id,
                'user_id' => $this->on_loop['user_id'],
                'tipe' => 'onloop',
                'urutan' => null,
                'role' => $this->on_loop['role'] ?? 'Observer',
                'deskripsi' => $this->on_loop['deskripsi'] ?? null,
            ]);

            $this->dispatch('success', 'Tindakan berhasil diupdate.');
            return redirect()->route('tindakan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $messages = $e->validator->errors()->all();
            $this->dispatch('error', implode(' ', $messages));
            return;
        } catch (\Throwable $e) {
            $this->dispatch('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.');
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
                'dpjp' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'dpjp');
                })->get(),
            ]);
        } else {
            return view('livewire.pages.admin.masterdata.tindakan.create-tindakan', [
                'pasiens' => Pasien::all(),
                'dokters' => User::with('mahasiswa')->whereHas('roles', fn($q) => $q->where('name', 'dokter'))->get(),
                'users' => User::all(),
                'dpjp' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'dpjp');
                })->get(),
            ]);
        }
    }
}
