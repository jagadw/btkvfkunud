<?php

namespace App\Livewire;

use App\Models\Mahasiswa;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]

class User extends Component
{
    public $userId, $name, $email, $password,  $idToDelete, $selectedRole, $selectedMahasiswa, $showForm = false;
    protected $listeners = ['deleteUser', 'loadData'];
    public $search = '';

    public function mount()
    {
        $userPermissions = Auth::user()->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        });

        if (!$userPermissions->contains('masterdata-user')) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function openModal()
    {
        if ($this->showForm) {
            $this->showForm = false;
        } else {
            $this->showForm = true;
        }
    }
    public function closeModal()
    {
        $this->userId = null;
        $this->reset(['name', 'email', 'password', 'selectedRole']);
        if ($this->showForm) {
            $this->showForm = false;
        } else {
            $this->showForm = true;
        }
    }
    public function create()
    {
        $this->openModal();
    }
    public function store()
    {

        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'selectedRole' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }

        $user =  ModelsUser::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
        $user->assignRole($this->selectedRole);

        $getUserId = ModelsUser::where('email', $this->email)->first();
        $mahasiswa = Mahasiswa::where('id', $this->selectedMahasiswa)->first();
        

        if ($mahasiswa && $mahasiswa instanceof Mahasiswa) {
            $mahasiswa->user_id = $getUserId->id;
            $mahasiswa->save();
            $this->dispatch('success', 'User created successfully.');
        } else {
            $this->dispatch('error', 'Mahasiswa not found.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $user = ModelsUser::find($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->selectedRole = $user->getRoleNames()->first();
        $this->dispatch('load-data', $user);
        $this->openModal();
    }
    public function update()
    {

        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'selectedRole' => 'required',
                'password' => 'nullable|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
        $user = ModelsUser::find($this->userId);
        $dataToUpdate = [
            'name' => $this->name,
            'email' => $this->email
        ];

        if (!empty($this->password)) {
            $dataToUpdate['password'] = bcrypt($this->password);
        }

        if ($this->selectedMahasiswa) {
            $mahasiswa = Mahasiswa::where('id', $this->selectedMahasiswa)->first();
            if ($mahasiswa) {
                $mahasiswa->user_id = $user->id;
                $mahasiswa->save();
            }
        }
        $user->update($dataToUpdate);

        $user->syncRoles($this->selectedRole);
        $this->dispatch('success', 'User updated successfully.');
        $this->closeModal();
    }
    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Are you sure you want to delete this user?');
    }
    public function deleteUser()
    {
        try {
            $user = ModelsUser::find($this->idToDelete);
            if ($user) {
                $user->delete();
                $this->dispatch('delete-success',  'User deleted successfully.');
            } else {
                $this->dispatch('error', 'User not found.');
            }
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
    public function aksesSemua($id)
    {
        $user = ModelsUser::find($id);
        if ($user) {
            $user->akses_semua = $user->akses_semua ? 0 : 1;
            $user->save();
            if ($user->akses_semua) {
                $this->dispatch('success', 'User diberikan semua akses data tindakan.');
            } else {
                $this->dispatch('success', 'User tidak dapat mengakses semua data tindakan.');
            }
        } else {
            $this->dispatch('error', 'User not found.');
        }
    }
    public function render()
    {
        return view('livewire.pages.admin.masterdata.user.index', data: [
            'data' => ModelsUser::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get()
            ->sortBy(function ($user) {
                $order = [
                    'developer' => 0,
                    'operator' => 1,
                    'admin' => 2,
                    'dpjp' => 3,
                    'dokter' => 4,
                ];
                $roleName = $user->roles->pluck('name')->first();
                return $order[$roleName] ?? 99;
            })
            ->values()
            ->all(),
            'roles' => Role::all(),
            'mahasiswas' => Mahasiswa::where('user_id', null)->get(), // Get Mahasiswa without user_id
        ]);
    }
}
