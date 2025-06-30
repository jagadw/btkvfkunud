<div class="modal fade" tabindex="-1" id="dpjpModal" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $dpjpId ? 'Edit' : 'Tambah' }} Dpjp</h3>
                <div class="btn btn-icon btn-sm btn-primary ms-2" data-bs-dismiss="modal" wire:click="resetForm">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-12">
                        <label class="required form-label">Nama</label>
                        <textarea class="form-control form-control-solid @error('nama') is-invalid @enderror" wire:model="nama" placeholder="Nama" rows="1" style="resize: vertical; min-height: 60px; overflow-y: auto; word-break: break-word;"></textarea>
                    </div>
                </div>

                <div class="row g-9 mb-8">
                    <div class="col-md-4">
                        <label class="required form-label">Inisial Residen</label>
                        <input type="text" class="form-control form-control-solid @error('inisial_residen') is-invalid @enderror" wire:model="inisial_residen" placeholder="Inisial Residen">
                    </div>
                    <div class="col-md-4">
                        <label class="required form-label">Tempat Lahir</label>
                        <input type="text" class="form-control form-control-solid @error('tempat_lahir') is-invalid @enderror" wire:model="tempat_lahir" placeholder="Tempat Lahir">
                    </div>
                    <div class="col-md-4">
                        <label class="required form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control form-control-solid @error('tanggal_lahir') is-invalid @enderror" wire:model="tanggal_lahir" placeholder="Tanggal Lahir">
                    </div>
                </div>
                    

                <div class="row g-9 mb-8">
                    <div class="col-md-12">
                        <label class="required form-label">Status</label>
                        <input type="text" class="form-control form-control-solid @error('status') is-invalid @enderror" wire:model="status" placeholder="Status">
                    </div>
                </div>

                <div class="row g-9 mb-8">
                    <div class="col-md-12">
                        <label class="required form-label">Alamat</label>
                        <textarea class="form-control form-control-solid @error('alamat') is-invalid @enderror" wire:model="alamat" placeholder="Alamat" style="resize: vertical; min-height: 38px; overflow-y: auto; word-break: break-word;"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="resetForm">Close</button>
                <button class="btn btn-primary" wire:click="{{ $dpjpId ? 'update' : 'store' }}">
                    {{ $dpjpId ? 'Update' : 'Store' }}
                </button>
            </div>
        </div>
    </div>
</div>
