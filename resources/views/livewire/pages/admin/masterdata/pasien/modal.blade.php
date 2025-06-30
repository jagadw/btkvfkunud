<div wire:ignore.self class="modal fade" id="pasienModal" tabindex="-1" aria-labelledby="pasienModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pasienModalLabel">
                    {{ $pasien_id ? 'Edit Patient' : 'Add Patient' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">No Rekam Medis</label>
                    <input type="text" placeholder="No Rekam Medis" wire:model="nomor_rekam_medis" maxlength="8" class="form-control @error('nomor_rekam_medis') is-invalid @enderror" pattern="\d{1,8}" title="Maksimal 8 digit angka" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('nomor_rekam_medis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" wire:model="nama" placeholder="Nama" class="form-control @error('nama') is-invalid @enderror">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" wire:model="tanggal_lahir" placeholder="Tanggal Lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" onchange="Livewire.dispatch('updateUsia')">
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Usia</label>
                    <input type="text" value="{{ $usia }}" placeholder="Usia" class="form-control @error('usia') is-invalid @enderror" disabled>
                    @error('usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select wire:model="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ $jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ $jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Asal Rumah Sakit</label>
                    <input type="text" wire:model="asal_rumah_sakit" placeholder="Asal Rumah Sakit" class="form-control @error('asal_rumah_sakit') is-invalid @enderror">
                    @error('asal_rumah_sakit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button wire:click="{{ $pasien_id ? 'update' : 'store' }}" class="btn btn-primary">{{ $pasien_id ? 'Update' : 'Save' }}</button>
            </div>

        </div>
    </div>
</div>
