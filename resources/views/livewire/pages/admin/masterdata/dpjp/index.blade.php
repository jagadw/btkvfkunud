<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Dpjp</x-slot:title>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen Dpjp</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">DPJP</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button class="btn btn-sm fw-bold btn-primary fs-5" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#dpjpModal">Tambah DPJP</button>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card shadow-lg p-5" style="min-height: 500px">
                <!-- Search -->
                <div class="mb-5">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12  border-primary border-3 text-primary" placeholder="Cari Nama / Inisial" wire:model.live.debounce.100ms="search" />
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table id="kt_datatable_zero_configuration" class="table table-row-bordered table-striped gy-5">
                            <thead>
                                <tr class="fw-semibold fs-6">
                                    <th class="text-center align-items-center">No</th>
                                    <th class="text-center align-items-center">Aksi</th>
                                    <th class="text-center align-items-center">TTD</th>
                                    <th class="text-center align-items-center">Nama</th>
                                    <th class="text-center align-items-center">Inisial Residen</th>
                                    <th class="text-center align-items-center">Tempat Lahir</th>
                                    <th class="text-center align-items-center">Tanggal Lahir</th>
                                    <th class="text-center align-items-center">Status</th>
                                    <th class="text-center align-items-center">Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dpjps as $index => $dpjp)
                                <tr>
                                    <td class="text-center align-items-center">{{ $index + 1 }}</td>
                                    <td class="text-center align-items-center">
                                        <a href="#" class="btn-primary text-light btn btn-sm btn-light btn-flex btn-center btn-active-primary fs-5" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-5 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3 ">
                                                <a wire:click="edit({{ $dpjp->id }})" class=" bg-warning text-dark menu-link px-3 w-100">Edit</a>
                                            </div>
                                            <div class="menu-item px-3 ">
                                                <a wire:click="setKoordinator({{ $dpjp->id }})" class=" bg-info text-white menu-link px-3 w-100">Jadikan Koordinator</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 w-100 text-white bg-danger" wire:click="delete({{ $dpjp->id }})">Non Aktifkan</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-items-center">
                                        @if($dpjp->ttd)
                                            <img src="{{ asset('storage/' . $dpjp->ttd) }}" alt="TTD" style="max-height: 50px;">
                                        @else
                                            -
                                        @endif
                                    </td></td>
                                    <td class="text-center align-items-center">
                                    {{ $dpjp->nama }}
                                    @if($dpjp->is_koordinator == true)
                                    <span class="badge badge-success text-white">Koordinator</span>
                                    @endif
                                </td>
                                    <td class="text-center align-items-center">{{ $dpjp->inisial_residen }}</td>
                                    <td class="text-center align-items-center">{{ $dpjp->tempat_lahir }}</td>
                                    <td class="text-center align-items-center">{{ \Carbon\Carbon::parse($dpjp->tanggal_lahir)->format('d F Y') }}</td>
                                    <td class="text-center align-items-center">
                                       {{ $dpjp->status }}
                                    </td>
                                    <td class="text-center align-items-center">{{ $dpjp->alamat }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Data Tidak Ditemukan!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                      
                    </div>



                </div>

                <!-- Modal Include -->
                @include('livewire.pages.admin.masterdata.dpjp.modal')

            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    $(function() {
        Livewire.on('show-modal', () => {
            var modalEl = document.getElementById('dpjpModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal = new bootstrap.Modal(modalEl, {});
            myModal.show();
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('dpjpModal');
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
                , confirmButtonText: "Ya"
                , cancelButtonText: "Tidak"
                , icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteDpjpConfirmed');
                } else {
                    Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                }
            });
        });


    });

</script>
@endpush
