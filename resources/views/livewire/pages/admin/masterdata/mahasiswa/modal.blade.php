<div class="modal fade" tabindex="-1" id="mahasiswaModal" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $mahasiswaId ? 'Edit' : 'Add' }} Student</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" wire:click="resetForm">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-4">
                        <label class="required form-label">NIM</label>
                        <textarea class="form-control form-control-solid @error('nim') is-invalid @enderror" wire:model="nim" placeholder="Nama" style="resize: vertical; min-height: 38px; overflow-y: auto; word-break: break-word;"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="required form-label">Nama</label>
                        <textarea class="form-control form-control-solid @error('nama') is-invalid @enderror" wire:model="nama" placeholder="Nama" style="resize: vertical; min-height: 38px; overflow-y: auto; word-break: break-word;"></textarea>
                    </div>
                   
                    <div class="col-md-4">
                        <label class="required form-label">Inisial Residen</label>
                        <input type="text" class="form-control form-control-solid @error('inisial_residen') is-invalid @enderror" wire:model="inisial_residen" placeholder="Inisial Residen">
                    </div>
                </div>

                <div class="row g-9 mb-8">
                    
                    {{-- <div class="col-md-12">
                        <label class="required form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                            <option value="">Select Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div> --}}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetForm">Close</button>
                <button class="btn btn-primary" wire:click="{{ $mahasiswaId ? 'update' : 'store' }}">
                    {{ $mahasiswaId ? 'Update' : 'Store' }}
                </button>
            </div>
        </div>
    </div>
</div>
