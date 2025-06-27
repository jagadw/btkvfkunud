<?php

namespace App\Livewire;

use App\Models\FotoTindakan;
use App\Models\LogBook as LogBookModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LogBook extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $selectedMahasiswa, $showForm, $logbookId, $user_id, $kegiatan, $tanggal, $idToDelete, $search = '';

    public $foto, $fotoPath;
    protected $listeners = ['deleteLogBookConfirmed'];
    protected $rules = [
        'kegiatan' => 'required|string',
        'tanggal' => 'required|date',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ];
    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));

        if (!$userPermissions->contains('masterdata-logbook')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto');
        $this->dispatch('success',  'Foto berhasil diunggah.');
    }

    public function openModal()
    {
        $this->dispatch('show-modal');
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->dispatch('hide-modal');
    }

    public function resetForm()
    {
        $this->reset(['logbookId', 'user_id', 'kegiatan', 'tanggal', 'foto']);
    }


    public function store()
    {
        try {
            $this->validate([
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
                'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            ]);

            $idUser = $this->selectedMahasiswa ?: Auth::user()->id;
            $logBookData = LogBookModel::create([
                'user_id' => $idUser,
                'kegiatan' => $this->kegiatan,
                'tanggal' => $this->tanggal,
            ]);
            $path = $this->foto->store('foto_tindakans', 'public');
            FotoTindakan::create([
                'log_book_id' => $logBookData->id,
                'foto' => $path,
                'deskripsi' => $this->kegiatan,
            ]);


            $this->dispatch('success', 'LogBook Berhasil Di Simpan.');
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
    }



    public function edit($id)
    {
        $data = LogBookModel::findOrFail($id);
        $this->logbookId = $data->id;
        $this->user_id = $data->user_id;
        $this->kegiatan = $data->kegiatan;
        $this->tanggal = Carbon::parse($data->tanggal)->format('Y-m-d');

        $foto = FotoTindakan::where('log_book_id', $id)->first();
        $this->fotoPath = $foto ? asset('storage/foto/' . $foto->foto) : null;

        $this->dispatch('show-modal', $this->fotoPath);
    }


    public function update()
    {
        try {
            $this->validate([
                'user_id' => 'required|exists:users,id',
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

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
            $fotoKegiatan->update([
                'deskripsi' => $this->kegiatan,
            ]);
        }

        $this->dispatch('success', 'Logbook entry updated successfully.');
        $this->closeModal();
    }

    public function showFoto($id)
    {
        $this->logbookId = $id;
        $foto = FotoTindakan::where('log_book_id', $id)->first();
        $this->fotoPath = $foto->foto;
        $this->dispatch('show-modal-foto', $this->fotoPath);
    }
    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin ingin menghapus LogBook Ini?');
    }

    public function deleteLogBookConfirmed()
    {
        $fotoKegiatan = FotoTindakan::where('log_book_id', $this->idToDelete)->first();
        if ($fotoKegiatan) {
            Storage::disk('public')->delete($fotoKegiatan->foto);
        }
        LogBookModel::destroy($this->idToDelete);
        $this->dispatch('delete-success', 'LogBook berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.logbook.index', [
            'logbooks' => LogBookModel::with('user')
                ->when(Auth::user()->roles->contains(fn($role) => $role->name === 'dokter'), function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->when($this->search, function ($q) {
                    $search = $this->search;
                    $q->where(function ($query) use ($search) {
                        $query->where('kegiatan', "like", "%{$search}%")
                            ->orWhereHas('user', fn($u) => $u->where('name', "like", "%{$search}%"));
                    });
                })
                ->get(),
            'users' => User::all(),
        ]);
    }
}
