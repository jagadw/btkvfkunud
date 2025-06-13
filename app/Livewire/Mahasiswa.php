<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Mahasiswa as MahasiswaModel;
use App\Models\User;

#[Layout('layouts.admin')]
class Mahasiswa extends Component
{
    use WithPagination;

    public $nama, $inisial_residen, $user_id, $mahasiswa_id, $status;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'nama' => 'required|string',
        'inisial_residen' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'status' => 'in:aktif,nonaktif',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MahasiswaModel::with('user')
            ->when($this->search, function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('inisial_residen', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($u) {
                      $u->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->latest();

        return view('livewire.pages.admin.masterdata.mahasiswa.index', [
            'mahasiswas' => $query->paginate(10),
            'users' => User::all(),
        ]);
    }

    public function resetForm()
    {
        $this->reset(['nama', 'inisial_residen', 'user_id', 'mahasiswa_id', 'status', 'isEdit']);
    }

    public function store()
    {
        $this->validate();

        MahasiswaModel::create([
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Student has been successfully added.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $data = MahasiswaModel::findOrFail($id);
        $this->mahasiswa_id = $id;
        $this->nama = $data->nama;
        $this->inisial_residen = $data->inisial_residen;
        $this->user_id = $data->user_id;
        $this->status = $data->status;
        $this->isEdit = true;
    }

    public function updateMahasiswa()
    {
        $this->validate();

        MahasiswaModel::where('id', $this->mahasiswa_id)->update([
            'nama' => $this->nama,
            'inisial_residen' => $this->inisial_residen,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Student data has been updated.');
        $this->resetForm();
    }

    public function deleteMahasiswa($id)
    {
        MahasiswaModel::findOrFail($id)->delete();
        session()->flash('message', 'Student has been deleted.');
    }
}
