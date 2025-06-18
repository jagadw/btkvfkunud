<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class CreateFotoTindakan extends Component
{
    use WithFileUploads;
    public $foto;
    public $id_tindakan, $deskripsi;
    protected $listeners = ['removePhoto'];

    protected $rules = [
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        'deskripsi' => 'required|string|',
    ];

    public function mount($id)
    {
        $this->id_tindakan = decrypt(value: $id);
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto');
        $this->dispatch('success',  'Foto berhasil diunggah.');
    }
    public function store()
    {
        try {
            $this->validate();

            $path = $this->foto->store('foto_tindakans', 'public');

            \App\Models\FotoTindakan::create([
            'tindakan_id' => $this->id_tindakan,
            'foto' => $path,
            'deskripsi' => $this->deskripsi,
            ]);

            $this->dispatch('success', 'Foto berhasil diunggah.');
            $this->reset('foto');
            return redirect()->route('tindakan');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.pages.admin.masterdata.tindakan.create-fototindakan');
    }
}
