<?php

namespace App\Livewire;

use App\Models\LogBook as LogBookModel;
use App\Models\FotoTindakan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class CreateLogBook extends Component
{
    use WithFileUploads;

    public $logBookId, $user_id, $kegiatan, $tanggal, $foto, $fotoPath, $selectedMahasiswa;

    protected $rules = [
        'kegiatan' => 'required|string',
        'tanggal' => 'required|date',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ];

    public function mount($id = null)
    {
        $userRole = Auth::user()->roles->pluck('name')->first();
        if ($id) {
            $this->logBookId = decrypt($id);
            $logbook = LogBookModel::findOrFail($this->logBookId);
            $this->logBookId = $logbook->id;
            $this->user_id = $logbook->user_id;
            $this->kegiatan = $logbook->kegiatan;
            $this->tanggal = Carbon::parse($logbook->tanggal)->format('Y-m-d');
            $this->selectedMahasiswa = $logbook->user_id;

            $foto = FotoTindakan::where('log_book_id', $id)->first();
            $this->fotoPath = $foto ? asset('storage/' . $foto->foto) : null;
        } else {
            $this->user_id = Auth::user()->id;
        }
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto');
        $this->dispatch('success', 'Foto berhasil diunggah.');
    }

    public function store()
    {
        
        try {
            $userRole = Auth::user()->roles->pluck('name')->first();

            $rules = [
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
                'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            ];

            if ($userRole !== 'dokter') {
                $rules['selectedMahasiswa'] = 'required|exists:users,id';
            }

            $this->validate($rules);

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
            return redirect()->route('logbook');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
    }

    public function update(){
        try{

            $this->validate([
                'kegiatan' => 'required|string',
                'tanggal' => 'required|date',
            ]);

            $logBookData = LogBookModel::findOrFail($this->logBookId);
            $logBookData->update([
                'kegiatan' => $this->kegiatan,
                'tanggal' => $this->tanggal,
            ]);
            if ($this->foto) {
                
                $fotoTindakan = FotoTindakan::where('log_book_id', $this->logBookId)->first();
                if ($fotoTindakan && $fotoTindakan->foto && Storage::disk('public')->exists($fotoTindakan->foto)) {
                    Storage::disk('public')->delete($fotoTindakan->foto);
                }

                
                $path = $this->foto->store('foto_tindakans', 'public');

                
                FotoTindakan::updateOrCreate(
                    ['log_book_id' => $this->logBookId],
                    ['foto' => $path, 'deskripsi' => $this->kegiatan]
                );
            }
            $this->dispatch('success', 'LogBook Berhasil Di Update.');
            return redirect()->route('logbook');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', collect($e->errors())->flatten()->first());
            return;
        }
    }

    public function resetForm()
    {
        $this->reset(['logBookId', 'user_id', 'kegiatan', 'tanggal', 'foto', 'fotoPath']);
    }

    public function render()
    {
       if(!$this->logBookId) {
            return view('livewire.pages.admin.masterdata.logbook.create-logbook', [
                'users' => User::all(),
            ]);
        } else {
            return view('livewire.pages.admin.masterdata.logbook.edit-logbook',[
                'users' => User::all(),
            ]);
        }
    }
}
