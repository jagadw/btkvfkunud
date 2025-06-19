<?php

namespace App\Livewire;

use App\Models\Mahasiswa as MahasiswaModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Mahasiswa extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $mahasiswaId, $nim, $nama, $inisial_residen,  $status, $idToDelete, $search = '';
    protected $listeners = ['deleteMahasiswaConfirmed'];

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        });

        if (!$userPermissions->contains('masterdata-mahasiswa')) {
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
        $this->reset(['mahasiswaId', 'nim', 'nama', 'inisial_residen', 'status']);
    }

    public function create()
    {
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'nim' => 'required|string',
                'nama' => 'required|string',
                'inisial_residen' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        MahasiswaModel::create([
            'nim' => $this->nim,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'status' => 'aktif',
        ]);

        $this->dispatch('success', 'Student created successfully.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = MahasiswaModel::findOrFail($id);
        $this->mahasiswaId = $data->id;
        $this->nim = $data->nim;
        $this->nama = $data->nama;
        $this->inisial_residen = $data->inisial_residen;
        $this->status = $data->status;
        $this->openModal();
    }

    public function update()
    {
        try {
            $this->validate([
                'nim' => 'required|string', 
                'nama' => 'required|string',
                'inisial_residen' => 'required|string',
                'status' => 'in:aktif,nonaktif',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $mahasiswa = MahasiswaModel::findOrFail($this->mahasiswaId);
        $mahasiswa->update([
            'nim' => $this->nim,
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'status' => $this->status,
        ]);

        $this->dispatch('success', 'Mahasiswa Berhasil di Update.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Yakin ingin menonaktifkan mahasiswa ini?');
    }

    public function deleteMahasiswaConfirmed()
    {
        $mahasiswaData = MahasiswaModel::where('id', $this->idToDelete)->first();
        // if ($mahasiswaData) {
        //     // Hapus user terkait jika ada
        //     if ($mahasiswaData->user) {
        //     $mahasiswaData->user->delete();
        //     }
        // }
        // Hapus data mahasiswa
        $mahasiswaData->delete();


        $this->dispatch('delete-success', 'Mahasiswa Berhasil di Non Aktifkan!.');
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.mahasiswa.index', [
            'mahasiswas' => MahasiswaModel::with('user')
                ->when($this->search, function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('inisial_residen', 'like', '%' . $this->search . '%')
                        ->orWhere('nim', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('name', 'like', '%' . $this->search . '%');
                        })
                      
                        ;
                })
                ->paginate(10),
            'users' => User::all(),
        ]);
    }
}
