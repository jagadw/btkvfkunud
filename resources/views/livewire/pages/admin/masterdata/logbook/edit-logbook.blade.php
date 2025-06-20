<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Edit LogBook</x-slot:title>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit LogBook</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <a class="breadcrumb-item text-muted" href="{{ route('logbook') }}" wire:navigate>Logbook</a>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Edit LogBook</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- <button class="btn btn-sm fw-bold btn-primary" wire:click="create">Tambah Logbook</button> --}}
            </div>
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5">
                <div class="row g-9 mb-8">
                    <div class="col-md-6" wire:ignore>
                        <label class="required form-label">Mahasiswa</label>
                        <select class="form-select" disabled data-control="select2" onchange="@this.set('selectedMahasiswa',this.value)" wire:model="selectedMahasiswa">
                            <option value="">Pilih Mahasiswa</option>
                            @foreach($users->filter(fn($user) => !$user->roles->pluck('name')->contains('developer')) as $dokter)
                            <option value="{{ $dokter->id }}">{{ $dokter->mahasiswa->nama . ' - ' . $dokter->mahasiswa->inisial_residen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="required form-label">Tanggal</label>
                        <input type="date" class="form-control" wire:model="tanggal">
                    </div>
                    <div class="col-md-12">
                        <label class="required form-label">Aktivitas</label>
                        <textarea class="form-control" wire:model="kegiatan" placeholder="Kegiatan" rows="1" style="resize:vertical; min-height:100px; overflow:auto;"></textarea>
                    </div>
                    <div class="col-md-12 mb-2" data-box="fotoTindakan" wire:ignore>
                        <label for="">Foto Kegiatan</label>
                        <input type="file" id="fotoInput" class="dropify form-control form-control-solid @error('foto') is-invalid @enderror" wire:model="foto" accept="image/jpeg,image/png" data-height="275" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="4M" />
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-primary" wire:click="update">Simpan</button>
                    <button class="btn btn-secondary ms-2" wire:click="resetForm">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
{{-- <script data-navigated-once src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script> --}}
<script data-navigated-once src="{{ asset('assets/js/plugin/dropify/dropify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click'
                , 'replace': 'Drag and drop or click to replace'
                , 'remove': 'Remove'
                , 'error': 'Ooops, something wrong appended.'
            }
        });
    });
    $(function() {

        Livewire.on('show-modal', () => {
            // Pastikan fotoModal tidak sedang terbuka
            var fotoModalEl = document.getElementById('fotoModal');
            if (fotoModalEl) {
                var fotoModalInstance = bootstrap.Modal.getInstance(fotoModalEl);
                if (fotoModalInstance) {
                    fotoModalInstance.hide();
                    fotoModalInstance.dispose();
                }
            }

            var modalEl = document.getElementById('logBookModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal = new bootstrap.Modal(modalEl, {});
            myModal.show();
            setTimeout(() => {
                let dropify = $('#fotoInput').dropify({
                    defaultFile: typeof fotoPath !== 'undefined' && fotoPath ? fotoPath : null
                });

                dropify = dropify.data('dropify');
                dropify.resetPreview();
                dropify.clearElement();

                if (typeof fotoPath !== 'undefined' && fotoPath) {
                    dropify.settings.defaultFile = fotoPath;
                    dropify.destroy();
                    dropify.init();
                }
            }, 300);
        });

        Livewire.on('show-modal-foto', () => {
            // Pastikan logBookModal tidak sedang terbuka
            var logBookModalEl = document.getElementById('logBookModal');
            if (logBookModalEl) {
                var logBookModalInstance = bootstrap.Modal.getInstance(logBookModalEl);
                if (logBookModalInstance) {
                    logBookModalInstance.hide();
                    logBookModalInstance.dispose();
                }
            }

            var modalEl1 = document.getElementById('fotoModal');
            if (!modalEl1) {
                console.error('Element with id "fotoModal" not found.');
                return;
            }
            var existingModal = bootstrap.Modal.getInstance(modalEl1);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal1 = new bootstrap.Modal(modalEl1, {});
            myModal1.show();
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('logBookModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
                modal.dispose();
            }
            modalEl.style.display = 'none';
            modalEl.setAttribute('aria-hidden', 'true');
            modalEl.removeAttribute('aria-modal');
            modalEl.removeAttribute('role');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });

        Livewire.on('confirm-delete', (message) => {
            Swal.fire({
                title: message
                , showCancelButton: true
                , confirmButtonText: "Yes"
                , cancelButtonText: "No"
                , icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteLogBookConfirmed');
                } else {
                    Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                }
            });
        });
    });

</script>
@endpush
