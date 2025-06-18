<?php

namespace App\Livewire;

use App\Models\LogBook AS LogBookModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LogBook extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $logbookId, $user_id, $kegiatan, $tanggal, $idToDelete, $search = '';
    protected $listeners = ['deleteLogBookConfirmed'];

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));

        if (!$userPermissions->contains('masterdata-logbook')) {
            abort(403, 'Unauthorized action.');
        }
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
        $this->reset(['logbookId', 'user_id', 'kegiatan', 'tanggal']);
    }

    public function create()
    {
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        LogBookModel::create([
            'user_id' => Auth::user()->id,
            'kegiatan' => $this->kegiatan,
            'tanggal' => $this->tanggal,
        ]);

        $this->dispatch('success', 'LogBook Berhasil Di Simpan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = LogBookModel::findOrFail($id);
        $this->logbookId = $data->id;
        $this->user_id = $data->user_id;
        $this->kegiatan = $data->kegiatan;
        $this->tanggal = $data->tanggal;
        $this->openModal();
    }

    public function update()
    {
        try {
            $this->validate([
                'user_id' => 'required|exists:users,id',
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
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

        $this->dispatch('success', 'Logbook entry updated successfully.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin ingin menghapus LogBook Ini?');
    }

    public function deleteLogBookConfirmed()
    {
        LogBookModel::destroy($this->idToDelete);
        $this->dispatch('delete-success', 'LogBook berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.logbook.index', [
            'logbooks' => LogBookModel::with('user')
                ->when(Auth::user()->hasRole('dokter'), function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->when($this->search, function ($q) {
                    $q->where(function ($query) {
                        $query->where('kegiatan', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'));
                    });
                })
                ->get(),
            'users' => User::all(),
        ]);
    }
}