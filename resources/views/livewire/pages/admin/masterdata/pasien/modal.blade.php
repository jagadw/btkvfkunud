<div wire:ignore.self class="modal fade" id="pasienModal" tabindex="-1" aria-labelledby="pasienModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form wire:submit.prevent="{{ $isEdit ? 'updatePasien' : 'store' }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="pasienModalLabel">
                        {{ $isEdit ? 'Edit Patient' : 'Add Patient' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" wire:model.defer="nama" class="form-control @error('nama') is-invalid @enderror">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Age</label>
                        <input type="number" wire:model.defer="usia" class="form-control @error('usia') is-invalid @enderror">
                        @error('usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Medical Record Number</label>
                        <input type="text" wire:model.defer="nomor_rekam_medis" class="form-control @error('nomor_rekam_medis') is-invalid @enderror">
                        @error('nomor_rekam_medis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" wire:model.defer="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror">
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select wire:model.defer="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="laki-laki">Male</option>
                            <option value="perempuan">Female</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Heart Type</label>
                        <input type="text" wire:model.defer="tipe_jantung" class="form-control @error('tipe_jantung') is-invalid @enderror">
                        @error('tipe_jantung') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
