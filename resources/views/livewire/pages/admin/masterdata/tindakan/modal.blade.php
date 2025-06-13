<div wire:ignore.self class="modal fade" id="tindakanModal" tabindex="-1" aria-labelledby="tindakanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="tindakanModalLabel">
                        {{ $isEdit ? 'Edit Treatment' : 'Add Treatment' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient</label>
                        <select wire:model.defer="pasien_id" class="form-select @error('pasien_id') is-invalid @enderror">
                            <option value="">Select Patient</option>
                            @foreach ($pasiens as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        @error('pasien_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Operator</label>
                        <select wire:model.defer="operator_id" class="form-select @error('operator_id') is-invalid @enderror">
                            <option value="">Select Operator</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('operator_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Assistant 1</label>
                        <select wire:model.defer="asisten1_id" class="form-select @error('asisten1_id') is-invalid @enderror">
                            <option value="">Select Assistant 1</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('asisten1_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Assistant 2</label>
                        <select wire:model.defer="asisten2_id" class="form-select @error('asisten2_id') is-invalid @enderror">
                            <option value="">Select Assistant 2</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('asisten2_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">On Loop</label>
                        <select wire:model.defer="on_loop_id" class="form-select @error('on_loop_id') is-invalid @enderror">
                            <option value="">Select On Loop</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('on_loop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Operation Date</label>
                        <input type="datetime-local" wire:model.defer="tanggal_operasi" class="form-control @error('tanggal_operasi') is-invalid @enderror">
                        @error('tanggal_operasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Treatment Realization</label>
                        <input type="text" wire:model.defer="relealisasi_tindakan" class="form-control @error('relealisasi_tindakan') is-invalid @enderror">
                        @error('relealisasi_tindakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Treatment Match</label>
                        <input type="text" wire:model.defer="kesesuaian" class="form-control @error('kesesuaian') is-invalid @enderror">
                        @error('kesesuaian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="resetForm" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
