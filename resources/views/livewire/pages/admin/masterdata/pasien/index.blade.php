<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Pasien</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen Pasien</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Pasien</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button class="btn btn-sm fs-5 fw-bold btn-primary" wire:click="create">Tambah Pasien</button>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">
                <div class="mb-5">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-solid w-250px border-primary border-3 text-primary" placeholder="Cari Nama Pasien">
                </div>

                <div class="main m-5">
                    <table class="table-responsive table table-row-bordered gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th class="text-center align-items-center">No</th>
                                <th class="text-center align-items-center">Aksi</th>
                                <th class="text-center align-items-center">No Rekam Medis</th>
                                <th class="text-center align-items-center">Nama</th>
                                <th class="text-center align-items-center">Usia</th>
                                <th class="text-center align-items-center">Tanggal Lahir</th>
                                <th class="text-center align-items-center">Jenis Kelamin</th>
                                <th class="text-center align-items-center">Asal Rumah Sakit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pasiens as $index => $p)
                            <tr>
                                <td class="text-center align-items-center">{{ $index + 1 }}</td>
                                <td class="text-center align-items-center">
                                    <a href="#" class="btn-primary btn btn-sm btn-light btn-flex btn-center btn-primary fs-5" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Aksi
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a wire:click.prevent="edit({{ $p->id }})" class="menu-link bg-warning text-dark px-3 w-100">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        @if(Auth::user()->roles->pluck('name')->first() == 'operator' || Auth::user()->roles->pluck('name')->first() == 'developer')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link bg-danger text-white px-3 w-100" wire:click.prevent="delete({{ $p->id }})">Hapus</a>
                                        </div>
                                        @endif
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                                <td class="text-center align-items-center">{{ $p->nomor_rekam_medis }}</td>
                                <td class="text-center align-items-center">{{ $p->nama }}</td>
                                <td class="text-center align-items-center">{{ $p->usia }}</td>
                                <td class="text-center align-items-center">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->format('d M Y') }}</td>
                                <td class="text-center align-items-center">{{ ucfirst($p->jenis_kelamin) }}</td>
                                <td class="text-center align-items-center">{{ $p->asal_rumah_sakit }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No Data Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- <div class="mt-4 d-flex justify-content-center">
                        {{ $pasiens->onEachSide(1)->links() }}
                </div> --}}
            </div>

            @include('livewire.pages.admin.masterdata.pasien.modal')
        </div>
    </div>
</div>
</div>
@push('script')
<script>
    $(function() {
        Livewire.on('show-modal', () => {
            var modalEl = document.getElementById('pasienModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal = new bootstrap.Modal(modalEl, {});
            myModal.show();
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('pasienModal');
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
                    Livewire.dispatch('deletePasienConfirmed');
                } else {
                    Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                }
            });
        });


    });

</script>
@endpush
