<div class="modal fade" tabindex="-1" id="logBookModal" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ $logbookId ? 'Edit' : 'Add' }} Logbook</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" wire:click="resetForm">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <div class="col-md-6">
                        <label class="required form-label">User</label>
                        <select class="form-select" wire:model="user_id">
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="required form-label">Activity</label>
                        <input type="text" class="form-control" wire:model="kegiatan" placeholder="Kegiatan">
                    </div>
                    <div class="col-md-12">
                        <label class="required form-label">Date</label>
                        <input type="date" class="form-control" wire:model="tanggal">
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