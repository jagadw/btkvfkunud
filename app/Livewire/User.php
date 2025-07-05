<?php

namespace App\Livewire;

use App\Models\Dpjp;
use App\Models\Mahasiswa;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class User extends Component
{
    public $userId, $name, $email, $password, $idToDelete;
    public $selectedRole, $selectedMahasiswa, $selectedDpjp;
    public $search = '';

    protected $listeners = ['deleteUser'];

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
        $this->dispatch('show-modal');
    }

    public function closeModal()
    {
        $this->userId = null;
        $this->reset(['name', 'email', 'password', 'selectedRole', 'selectedMahasiswa', 'selectedDpjp']);
        $this->dispatch('hide-modal');
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
                'selectedMahasiswa' => 'nullable|exists:mahasiswas,id',
                'selectedDpjp' => 'nullable|exists:dpjps,id',
            ]);

            $user = ModelsUser::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
            $user->assignRole($this->selectedRole);

            // Handle relasi Mahasiswa atau DPJP
            if ($this->selectedRole == 'dokter' && $this->selectedMahasiswa) {
                $mahasiswa = Mahasiswa::find($this->selectedMahasiswa);
                if ($mahasiswa) {
                    $mahasiswa->user_id = $user->id;
                    $mahasiswa->save();
                }
            } elseif ($this->selectedRole == 'dpjp' && $this->selectedDpjp) {
                $dpjp = Dpjp::find($this->selectedDpjp);
                if ($dpjp) {
                    $dpjp->user_id = $user->id;
                    $dpjp->save();
                }
            } elseif ($this->selectedRole != 'dokter' && $this->selectedRole != 'dpjp') {
                // Jika bukan dokter atau DPJP, cek relasi Mahasiswa atau DPJP
                $dpjp = Dpjp::where('user_id', null)->first();
                if ($dpjp) {
                    $dpjp->user_id = $user->id;
                    $dpjp->save();
                } else {
                    $mahasiswa = Mahasiswa::where('user_id', null)->first();
                    if ($mahasiswa) {
                        $mahasiswa->user_id = $user->id;
                        $mahasiswa->save();
                    }
                }
            }

            $this->dispatch('success', 'User telah dibuat.');
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
    }

    public function edit($id)
    {
        $user = ModelsUser::find($id);
        if (!$user) {
            $this->dispatch('error', 'User tidak ditemukan.');
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->selectedRole = $user->getRoleNames()->first();

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

            $user = ModelsUser::find($this->userId);
            if (!$user) {
                $this->dispatch('error', 'User not found.');
                return;
            }

            $dataToUpdate = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $dataToUpdate['password'] = bcrypt($this->password);
            }

            $user->update($dataToUpdate);
            $user->syncRoles($this->selectedRole);

            

            $this->dispatch('success', 'User telah diperbarui.');
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
    }


    public function delete($id)
    {
        $this->idToDelete = $id;
        $this->dispatch('confirm-delete', 'Seriuz ingin menghapus user ini?setelah di hapus, data user tidak dapat dikembalikan.');
    }

    public function nonAktif($id)
    {
        $user = ModelsUser::find($id);
        if ($user) {
            $user->deleted_at = now();
            $user->save();


            $this->dispatch('success', 'User Telah Dinonaktifkan.');
        } else {
            $this->dispatch('error', 'User Tidak Ditemukan.');
        }
    }

    public function deleteUser()
    {
        try {

            $user = ModelsUser::find($this->idToDelete);
            if ($user) {
                if ($user->roles->pluck('name')->first() == 'dokter') {
                    $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
                    if ($mahasiswa) {
                        $mahasiswa->user_id = null;
                        $mahasiswa->save();
                    }
                } elseif ($user->roles->pluck('name')->first() == 'dpjp') {
                    $dpjp = Dpjp::where('user_id', $user->id)->first();
                    if ($dpjp) {
                        $dpjp->user_id = null;
                        $dpjp->save();
                    }
                } else {
                    $dpjp = Dpjp::where('user_id', $user->id)->first();
                    if ($dpjp) {
                        $dpjp->user_id = null;
                        $dpjp->save();
                    } else {
                        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
                        if ($mahasiswa) {
                            $mahasiswa->user_id = null;
                            $mahasiswa->save();
                        }
                    }
                }
                $user->delete();
                $this->dispatch('delete-success', 'User deleted successfully.');
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
            $user->akses_semua = !$user->akses_semua;
            $user->save();

            $message = $user->akses_semua
                ? 'User diberikan semua akses data tindakan.'
                : 'User tidak dapat mengakses semua data tindakan.';
            $this->dispatch('success', $message);
        } else {
            $this->dispatch('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.pages.admin.masterdata.user.index', [
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
            'mahasiswas' => Mahasiswa::where('user_id', null)->get(),
            'dpjps' => Dpjp::where('user_id', null)->get(),
        ]);
    }
}
