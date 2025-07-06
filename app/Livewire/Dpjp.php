<?php

namespace App\Livewire;

use App\Models\Dpjp as DpjpModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Dpjp extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $dpjpId, $user_id, $nama, $inisial_residen, $tempat_lahir, $tanggal_lahir, $status, $alamat, $idToDelete, $search = '', $ttd, $photoPreview;

    protected $listeners = ['deleteDpjpConfirmed'];

    // public function updatedTtd()
    // {
    //     if ($this->ttd) {
    //         // Update preview dengan foto baru
    //         $this->photoPreview = $this->ttd->temporaryUrl();
    //         $this->dispatch('success', 'Foto tanda tangan berhasil diupload.');
    //     }
    // }

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(fn($role) => $role->permissions->pluck('name'));

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
        $this->reset([
            'dpjpId',
            'user_id',
            'nama',
            'inisial_residen',
            'tempat_lahir',
            'tanggal_lahir',
            'status',
            'alamat',
            'ttd',
            'photoPreview'
        ]);
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
                'ttd' => 'required|image|max:4096',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $ttdPath = $this->ttd ? $this->ttd->store('ttd', 'public') : null;

        DpjpModel::create([
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'status' => $this->status,
            'alamat' => $this->alamat,
            'ttd' => $ttdPath,
        ]);

        $this->dispatch('success', 'Data DPJP Berhasil Di Simpan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = DpjpModel::findOrFail($id);
        $this->fill($data->only([
            'user_id',
            'nama',
            'inisial_residen',
            'tempat_lahir',
            'tanggal_lahir',
            'status',
            'alamat'
        ]));
        $this->dpjpId = $id;

        // Set photoPreview ke foto lama dari storage jika ada
        if ($data->ttd) {
            $this->photoPreview = asset("storage/{$data->ttd}");
        } else {
            $this->photoPreview = null;
        }

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
                'ttd' => 'nullable|image|max:4096',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $dpjp = DpjpModel::findOrFail($this->dpjpId);

        $updateData = [
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'status' => $this->status,
            'alamat' => $this->alamat,
        ];

        if ($this->ttd) {
            // Hapus file lama jika ada
            if ($dpjp->ttd && Storage::disk('public')->exists($dpjp->ttd)) {
                Storage::disk('public')->delete($dpjp->ttd);
            }

            // Simpan file baru
            $ttdPath = $this->ttd->store('ttd', 'public');
            $updateData['ttd'] = $ttdPath;

            // Update preview ke foto baru
            $this->photoPreview = asset("storage/{$ttdPath}");
        }

        $dpjp->update($updateData);

        $this->dispatch('success', 'Data DPJP Berhasil Di Update.');
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

        if ($dpjpData) {
            if ($dpjpData->ttd && Storage::disk('public')->exists($dpjpData->ttd)) {
                Storage::disk('public')->delete($dpjpData->ttd);
            }
            $dpjpData->delete();
        }

        $this->dispatch('delete-success', 'DPJP Berhasil di Non Aktifkan!.');
    }

    public function setKoordinator($id)
    {
        $dataDpjp = DpjpModel::where('id', $id)->first();
        // check current coordinator
        $currentCoordinator = DpjpModel::where('is_koordinator', 1)->first();
        if ($currentCoordinator) {
            $currentCoordinator->is_koordinator = false;
            $currentCoordinator->save();
            $dataDpjp->is_koordinator = true;
            $dataDpjp->save();

            $this->dispatch('success', 'DPJP telah dijadikan Koordinator Program Studi');
        } else {
            $dataDpjp->is_koordinator = true;
            $dataDpjp->save();
            $this->dispatch('success', 'DPJP telah dijadikan Koordinator Program Studi');
        }
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
