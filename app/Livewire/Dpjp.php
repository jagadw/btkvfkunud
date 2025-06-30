<?php

namespace App\Livewire;

use App\Models\Dpjp as DpjpModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Dpjp extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $dpjpId, $user_id, $nama, $inisial_residen, $tempat_lahir, $tanggal_lahir, $status, $alamat, $idToDelete, $search = '';
    protected $listeners = ['deleteDpjpConfirmed'];

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        });

        if (!$userPermissions->contains('masterdata-dpjp')) {
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
        $this->reset(['dpjpId', 'user_id', 'nama', 'inisial_residen', 'tempat_lahir', 'tanggal_lahir', 'status', 'alamat']);
    }

    public function create()
    {
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string',
                'inisial_residen' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'status' => 'required|string',
                'alamat' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        DpjpModel::create([
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'status' => $this->status,
            'alamat' => $this->alamat,
        ]);

        $this->dispatch('success', 'DPJP created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = DpjpModel::findOrFail($id);
        $this->fill($data->only([
            'user_id', 'nama', 'inisial_residen',
            'tempat_lahir', 'tanggal_lahir', 'status', 'alamat'
        ]));
        $this->dpjpId = $id;
        $this->openModal();
    }

    public function update()
    {
        try {
            $this->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string',
                'inisial_residen' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'status' => 'required|string',
                'alamat' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        DpjpModel::where('id', $this->dpjpId)->update([
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'status' => $this->status,
            'alamat' => $this->alamat,
        ]);

        $this->dispatch('success', 'DPJP updated successfully.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin ingin menonaktifkan DPJP ini?');
    }

    public function deleteDpjpConfirmed()
    {
        $dpjpData = DpjpModel::where('id', $this->idToDelete)->first();
        $dpjpData?->delete();

        $this->dispatch('delete-success', 'DPJP Berhasil di Non Aktifkan!.');
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.dpjp.index', [
            'dpjps' => DpjpModel::with('user')
                ->when($this->search, function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('inisial_residen', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->get(),
            'users' => User::all(),
        ]);
    }
}
