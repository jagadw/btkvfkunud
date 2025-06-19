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
                        <input type="text" wire:model="nomor_rekam_medis" class="form-control @error('nomor_rekam_medis') is-invalid @enderror">
                        @error('nomor_rekam_medis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" wire:model="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" onchange="Livewire.dispatch('updateUsia')">
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usia</label>
                        <input type="text" value="{{ $usia }}" class="form-control @error('usia') is-invalid @enderror" disabled>
                        @error('usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="L" {{ $jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ $jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label>Tipe Jantung</label>
                        <select class="form-select" wire:model="tipe_jantung">
                        <option value="">Pilih Tipe Jantung</option>
                            <option value="Jantung Dewasa" {{ $tipe_jantung == 'Jantung Dewasa' ? 'selected' : '' }}>Jantung Dewasa</option>
                            <option value="Jantung Pediatri & Kongenital" {{ $tipe_jantung == 'Jantung Pediatri & Kongenital' ? 'selected' : '' }}>Jantung Pediatri & Kongenital</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeModal" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button wire:click="{{ $pasien_id ? 'update' : 'store' }}" class="btn btn-primary">{{ $pasien_id ? 'Update' : 'Save' }}</button>
                </div>
           
        </div>
    </div>
</div>
