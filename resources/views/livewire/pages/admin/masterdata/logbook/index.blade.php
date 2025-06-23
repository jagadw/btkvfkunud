<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen LogBook</x-slot:title>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen LogBook</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Logbook</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a class="btn btn-sm fs-5 fw-bold btn-primary" href="{{ route('add-logbook') }}" wire:navigate.prevent>Tambah Logbook</a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg" style="min-height: 500px">
                <!-- Search -->
                <div class="mb-5">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" class="form-control form-control-solid w-250px ps-12  border-primary border-3 text-primary" placeholder="Cari Kegiatan LogBook" wire:model.live.debounce.100ms="search" />
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="kt_datatable_zero_configuration" class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Nama</th>
                                <th>Aktivitas</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logbooks as $index => $logbook)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="#" class="btn-primary btn btn-sm btn-light btn-flex btn-center fs-5" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Aksi
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('edit-logbook', encrypt($logbook->id)) }}" class="menu-link bg-warning text-dark px-3 w-100">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            @if (Auth::user()->roles->pluck('name')->first() == 'operator'|| Auth::user()->roles->pluck('name')->first() == 'developer')
                                            <a href="#" class="menu-link bg-danger text-white px-3 w-100" wire:click="delete({{ $logbook->id }})">Hapus</a>
                                            @endif
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            @if($logbook->fotoTindakan)
                                            <a href="#" class="menu-link bg-info text-white px-3 w-100" wire:click="showFoto({{ $logbook->id }})">Lihat Foto</a>
                                            @endif
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                                <td>{{ $logbook->user->name }}</td>
                                <td>{{ $logbook->kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d F Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Tidak Ditemukan!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- <div class="mt-4 d-flex justify-content-center">
                        {{ $logbooks->onEachSide(1)->links() }}
                </div> --}}
            </div>

            <!-- Modal Include -->
            @include('livewire.pages.admin.masterdata.logbook.modal')
            @include('livewire.pages.admin.masterdata.logbook.modal-foto')

        </div>
    </div>
</div>
</div>
@push('script')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/dropify/dropify.min.js') }}"></script>
<script>

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
