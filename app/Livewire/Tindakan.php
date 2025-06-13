<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Tindakan as TindakanModel;
use App\Models\Pasien;
use App\Models\User;

#[Layout('layouts.admin')]
class Tindakan extends Component
{
    use WithPagination;

    public $pasien_id, $operator_id, $asisten1_id, $asisten2_id, $on_loop_id;
    public $tanggal_operasi, $relealisasi_tindakan, $kesesuaian, $tindakan_id;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'pasien_id' => 'required|exists:pasiens,id',
        'operator_id' => 'required|exists:users,id',
        'asisten1_id' => 'required|exists:users,id',
        'asisten2_id' => 'required|exists:users,id',
        'on_loop_id' => 'required|exists:users,id',
        'tanggal_operasi' => 'required|date',
        'relealisasi_tindakan' => 'required|string',
        'kesesuaian' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tindakans = TindakanModel::with(['pasien', 'operator', 'asisten1', 'asisten2', 'onLoop'])
            ->whereHas('pasien', function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.pages.admin.masterdata.tindakan.index', [
            'tindakans' => $tindakans,
            'pasiens' => Pasien::all(),
            'users' => User::all(),
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'pasien_id', 'operator_id', 'asisten1_id', 'asisten2_id', 'on_loop_id',
            'tanggal_operasi', 'relealisasi_tindakan', 'kesesuaian', 'tindakan_id', 'isEdit'
        ]);
    }

    public function store()
    {
        $this->validate();

        TindakanModel::create([
            'pasien_id' => $this->pasien_id,
            'operator_id' => $this->operator_id,
            'asisten1_id' => $this->asisten1_id,
            'asisten2_id' => $this->asisten2_id,
            'on_loop_id' => $this->on_loop_id,
            'tanggal_operasi' => $this->tanggal_operasi,
            'relealisasi_tindakan' => $this->relealisasi_tindakan,
            'kesesuaian' => $this->kesesuaian,
        ]);

        session()->flash('message', 'Data successfully added.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $data = TindakanModel::findOrFail($id);
        $this->fill($data->only([
            'pasien_id', 'operator_id', 'asisten1_id', 'asisten2_id', 'on_loop_id',
            'tanggal_operasi', 'relealisasi_tindakan', 'kesesuaian'
        ]));
        $this->tindakan_id = $id;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        TindakanModel::where('id', $this->tindakan_id)->update([
            'pasien_id' => $this->pasien_id,
            'operator_id' => $this->operator_id,
            'asisten1_id' => $this->asisten1_id,
            'asisten2_id' => $this->asisten2_id,
            'on_loop_id' => $this->on_loop_id,
            'tanggal_operasi' => $this->tanggal_operasi,
            'relealisasi_tindakan' => $this->relealisasi_tindakan,
            'kesesuaian' => $this->kesesuaian,
        ]);

        session()->flash('message', 'Data successfully updated.');
        $this->resetForm();
    }

    public function delete($id)
    {
        TindakanModel::findOrFail($id)->delete();
        session()->flash('message', 'Data successfully deleted.');
    }
}
