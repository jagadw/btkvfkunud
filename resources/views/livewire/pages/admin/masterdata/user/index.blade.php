<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen User</x-slot:title>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen User</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">User</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <div class="d-flex items-center">
                {{-- <input type="text" class="form-control form-control-solid" placeholder="Search User Name" id="search" autocomplete="off" wire:model.live.dobonce.300ms="search" /> --}}
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Secondary button-->
                {{-- <a href="#" class="btn btn-sm fw-bold btn-secondary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Rollover</a> --}}
                <!--end::Secondary button-->
                <!--begin::Primary button-->
                <button class="btn btn-sm fs-5 fw-bold btn-primary" wire:click="create()">Tambah User</button>
                <!--end::Primary button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->


    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">
                <div class="flex w-full">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-customer-table-filter="search" class=" form-control form-control-solid w-250px ps-12  border-primary border-3 text-primary" placeholder="Search User" wire:model.live.debounce.100ms="search" />
                    </div>

                </div>
                <div class="table-responsive">
                    <table id="kt_datatable_zero_configuration" class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th class="text-center align-items-center">No</th>
                                <th class="text-center align-items-center">Aksi</th>
                                <th class="text-center align-items-center">Name</th>
                                <th class="text-center align-items-center">Email</th>
                                <th class="text-center align-items-center">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data) < 1) <tr>
                                <td colspan="6" class="text-center">Data Tidak Ditemukan!</td>
                                </tr>
                                @else

                                @php $rowNumber = 1; @endphp
                                @foreach ($data as $user)
                                @php
                                $userRole = $user->getRoleNames()->first();
                                $currentUserRole = Auth::user()->roles->pluck('name')->first();
                                @endphp
                                @if ($userRole !== 'developer' || $currentUserRole === 'developer')
                                <tr wire:key="user-{{ $user->id }}">
                                    <td class="text-center align-items-center">{{ $rowNumber++ }}</td>
                                    <td class="text-center align-items-center">
                                        <a href="#" class="btn-primary btn btn-sm btn-light btn-flex btn-center btn-primary  fs-5" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a wire:click="edit({{ $user->id }})" class="menu-link bg-warning text-dark px-3 w-100">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link bg-danger text-white px-3 w-100" data-kt-ecommerce-product-filter="delete_row" wire:click="delete({{ $user->id }})">Hapus</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link bg-danger text-white px-3 w-100" data-kt-ecommerce-product-filter="delete_row" wire:click="nonAktif({{ $user->id }})">Non Aktifkan</a>
                                            </div>
                                            @if($userRole === 'dokter')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 w-100 bg-info text-white" data-kt-ecommerce-product-filter="delete_row" wire:click="aksesSemua({{ $user->id }})">{{ $user->akses_semua == 1 ? 'Tarik Akses' : 'Berikan Akses' }}</a>
                                            </div>
                                            @endif
                                            <!--end::Menu item-->
                                        </div>
                                    </td>
                                    <td class="text-center align-items-center">{{ $user->name }}</td>
                                    <td class="text-center align-items-center">{{ $user->email }}</td>
                                    <td class="text-center align-items-center">
                                        @if(strtolower($userRole) === 'operator')
                                        <span class="badge badge-success">{{ strtoupper($userRole) }}</span>
                                        @elseif(strtolower($userRole) === 'admin')
                                        <span class="badge badge-primary">{{ strtoupper($userRole) }}</span>
                                        @elseif(strtolower($userRole) === 'dokter')
                                        <span class="badge badge-info">{{ strtoupper($userRole) }}</span>
                                        @elseif(strtolower($userRole) === 'developer')
                                        <span class="badge badge-warning">{{ strtoupper($userRole) }}</span>
                                        @else
                                        <span class="badge badge-secondary">{{ strtoupper($userRole) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                @endif
                        </tbody>


                    </table>
                    {{-- <div class="mt-4 d-flex justify-content-center">
                        {{ $data->onEachSide(1)->links() }}
                </div> --}}
            </div>

            @include('livewire.pages.admin.masterdata.user.modal')
        </div>


    </div>
</div>
</div>
@push('scripts')
<script>
    $(function() {
        Livewire.on('show-modal', () => {
            var modalEl = document.getElementById('userModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (!existingModal) {
                var myModal = new bootstrap.Modal(modalEl, {});
                myModal.show();
            } else {
                existingModal.show();
            }
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('userModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
                modal.dispose();
            }
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
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
                title: message,
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                icon: "warning"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteUser');
                } else {
                    Swal.fire("Cancelled", "Delete Cancelled.", "info");
                }
            });
        });



    });

    document.addEventListener('livewire:navigated', () => {
        if ($('#selectRole').length) {
            const roleValue = $('#selectRole').val();
            if (roleValue === 'dpjp') {
                setTimeout(function() {
                    $('#selectDpjp').select2();
                }, 500);
            } else if (roleValue === 'dokter') {
                setTimeout(function() {
                    $('#selectMahasiswa').select2();
                }, 500);
            }
            $('#selectRole').on('change', function(e) {
                @this.set('selectedRole', e.target.value);
                setTimeout(function() {
                    if (e.target.value === 'dpjp') {
                        $('#selectDpjp').select2();
                    } else if( e.target.value === 'dokter') {
                        $('#selectMahasiswa').select2();
                    }
                }, 500);
                // $('#selectUser').select2();
            
            });
        }
    });

    
 
</script>

@endpush