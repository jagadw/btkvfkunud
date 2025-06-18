<div class="modal fade" tabindex="-1" id="fotoModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Foto Tindakan</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">

                <div class="row g-9 mb-8">
                    <div class="col-md-12 mb-2 d-flex justify-content-center" data-box="fotoTindakan">
                        
                        <img src="{{ asset('storage/' . ($fotoPreview->foto ?? 'default.jpg')) }}" alt="Preview Foto" class="img-fluid rounded" style="max-height: 400px;">
                        
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal">Close</button>


            </div>
        </div>
    </div>
</div>
