<?php

namespace App\Livewire;

use App\Models\LogBook as LogBookModel;
use App\Models\FotoTindakan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class CreateLogBook extends Component
{
    use WithFileUploads;

    public $logbookId, $user_id, $kegiatan, $tanggal, $foto, $fotoPath, $selectedMahasiswa;

    protected $rules = [
        'kegiatan' => 'required|string',
        'tanggal' => 'required|date',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $logbook = LogBookModel::findOrFail($id);
            $this->logbookId = $logbook->id;
            $this->user_id = $logbook->user_id;
            $this->kegiatan = $logbook->kegiatan;
            $this->tanggal = $logbook->tanggal;

            $foto = FotoTindakan::where('log_book_id', $id)->first();
            $this->fotoPath = $foto ? asset('storage/' . $foto->foto) : null;
        } else {
            $this->user_id = Auth::user()->id;
        }
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto');
        $this->dispatch('success', 'Foto berhasil diunggah.');
    }

    public function save()
    {
        $this->validate();

        if ($this->logbookId) {
            // Update
            $logbook = LogBookModel::findOrFail($this->logbookId);
            $logbook->update([
                'user_id' => $this->user_id,
                'kegiatan' => $this->kegiatan,
                'tanggal' => $this->tanggal,
            ]);

            $fotoKegiatan = FotoTindakan::where('log_book_id', $this->logbookId)->first();
            if ($this->foto) {
                if ($fotoKegiatan && $fotoKegiatan->foto) {
                    Storage::disk('public')->delete($fotoKegiatan->foto);
                }
                $path = $this->foto->store('foto_tindakans', 'public');
                if ($fotoKegiatan) {
                    $fotoKegiatan->update([
                        'foto' => $path,
                        'deskripsi' => $this->kegiatan,
                    ]);
                } else {
                    FotoTindakan::create([
                        'log_book_id' => $this->logbookId,
                        'foto' => $path,
                        'deskripsi' => $this->kegiatan,
                    ]);
                }
            } elseif ($fotoKegiatan) {
                $fotoKegiatan->update(['deskripsi' => $this->kegiatan]);
            }

            $this->dispatch('success', 'LogBook berhasil diupdate.');
        } else {
            // Create
            $logbook = LogBookModel::create([
                'user_id' => $this->user_id,
                'kegiatan' => $this->kegiatan,
                'tanggal' => $this->tanggal,
            ]);
            if ($this->foto) {
                $path = $this->foto->store('foto_tindakans', 'public');
                FotoTindakan::create([
                    'log_book_id' => $logbook->id,
                    'foto' => $path,
                    'deskripsi' => $this->kegiatan,
                ]);
            }
            $this->dispatch('success', 'LogBook berhasil disimpan.');
        }

        $this->resetForm();
        return redirect()->route('logbook.index');
    }

    public function resetForm()
    {
        $this->reset(['logbookId', 'user_id', 'kegiatan', 'tanggal', 'foto', 'fotoPath']);
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.logbook.create-logbook',[
            'users' => User::all(),
        ]);
    }
}
