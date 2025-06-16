<?php

namespace App\Livewire;

use App\Models\FotoTindakan AS FotoTindakanModel;
use App\Models\Tindakan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class FotoTindakan extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $fotoTindakanId, $tindakan_id, $foto, $deskripsi, $idToDelete, $search = '';
    protected $listeners = ['deleteFotoTindakanConfirmed'];

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));

        if (!$userPermissions->contains('masterdata-fototindakan')) {
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
        $this->reset(['fotoTindakanId', 'tindakan_id', 'foto', 'deskripsi']);
    }

    public function create()
    {
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'tindakan_id' => 'required|exists:tindakans,id',
                'foto' => 'required|image|max:2048',
                'deskripsi' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $fotoPath = $this->foto->store('foto_tindakans', 'public');

        FotoTindakanModel::create([
            'tindakan_id' => $this->tindakan_id,
            'foto' => $fotoPath,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->dispatch('success', 'Foto tindakan created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = FotoTindakanModel::findOrFail($id);
        $this->fotoTindakanId = $data->id;
        $this->tindakan_id = $data->tindakan_id;
        $this->deskripsi = $data->deskripsi;
        $this->openModal();
    }

    public function update()
    {
        try {
            $this->validate([
                'tindakan_id' => 'required|exists:tindakans,id',
                'foto' => 'nullable|image|max:2048',
                'deskripsi' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $fotoTindakan = FotoTindakanModel::findOrFail($this->fotoTindakanId);

        if ($this->foto) {
            $fotoPath = $this->foto->store('foto_tindakans', 'public');
            $fotoTindakan->update(['foto' => $fotoPath]);
        }

        $fotoTindakan->update([
            'tindakan_id' => $this->tindakan_id,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->dispatch('success', 'Foto tindakan updated successfully.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Are you sure you want to delete this foto tindakan?');
    }

    public function deleteFotoTindakanConfirmed()
    {
        FotoTindakanModel::destroy($this->idToDelete);
        $this->dispatch('delete-success', 'Foto tindakan deleted successfully.');
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.fototindakan.index', [
            'fotoTindakans' => FotoTindakanModel::with('tindakan')
                ->when($this->search, function ($q) {
                    $q->where('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('tindakan', fn($t) => $t->where('name', 'like', '%' . $this->search . '%'));
                })
                ->paginate(10),
            'tindakans' => Tindakan::all(),
        ]);
    }
}