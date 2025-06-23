<div class="modal fade" tabindex="-1" id="logBookModal" aria-hidden="true" wire:ignore.self tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $logbookId ? 'Edit' : 'Add' }} Logbook</h3>
                <div class="btn btn-icon btn-sm btn-primary ms-2" data-bs-dismiss="modal" wire:click="resetForm">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-6" wire:ignore>
                        <label class="required form-label">User</label>
                        <select class="form-select" wire:model="user_id" data-control="select2" data-placeholder="Pilih Dokter" >
                            <option></option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="required form-label">Aktivitas</label>
                        <textarea class="form-control" wire:model="kegiatan" placeholder="Kegiatan" rows="1" style="resize:vertical; min-height:100px; overflow:auto;"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="required form-label">Tanggal</label>
                        <input type="date" class="form-control" wire:model="tanggal">
                    </div>
                    <div class="col-md-12 mb-2" data-box="fotoTindakan" wire:ignore>
                        <label for="">Foto Kegiatan</label>
                        <input type="file" id="fotoInput" class="dropify form-control form-control-solid @error('foto') is-invalid @enderror" wire:model="foto" accept="image/jpeg,image/png" data-height="275" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="4M" />

                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetForm">Close</button>
                <button class="btn btn-primary" wire:click="{{ $logbookId ? 'update' : 'store' }}">
                    {{ $logbookId ? 'Update' : 'Store' }}
                </button>
            </div>
        </div>
    </div>
</div>
