<div class="modal fade" tabindex="-1" id="fotoTindakanModal" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $fotoTindakanId ? 'Edit' : 'Add' }} Foto Tindakan</h3>
                <div class="btn btn-icon btn-sm btn-primary ms-2" data-bs-dismiss="modal" wire:click="resetForm">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-6">
                        <label class="required form-label">Tindakan</label>
                        <select class="form-select @error('tindakan_id') is-invalid @enderror" wire:model="tindakan_id">
                            <option value="">Select Tindakan</option>
                            @foreach ($tindakans as $tindakan)
                                <option value="{{ $tindakan->id }}">{{ $tindakan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="required form-label">Foto</label>
                        <input type="file" class="form-control form-control-solid @error('foto') is-invalid @enderror" wire:model="foto" accept="image/*">
                    </div>
                </div>

                <div class="row g-9 mb-8">
                    <div class="col-md-12">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" class="form-control form-control-solid @error('deskripsi') is-invalid @enderror" wire:model="deskripsi" placeholder="Deskripsi foto">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetForm">Close</button>
                <button class="btn btn-primary" wire:click="{{ $fotoTindakanId ? 'update' : 'store' }}">
                    {{ $fotoTindakanId ? 'Update' : 'Store' }}
                </button>
            </div>
        </div>
    </div>
</div>