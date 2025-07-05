    <div class="modal fade" tabindex="-1" id="userModal" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{$userId ? 'Edit' : 'Add'}} User</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    {{-- <form id="kt_modal_new_target_form" class="form" wire:submit.prevent="{{ isset($userId) ? 'update' : 'store' }}"
                    > --}}

                    <div class="row g-9 mb-8">
                        <div class="d-flex flex-column col-md-6 mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Username</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Username" id="name" autocomplete="off" wire:model="name" />
                        </div>
                        <div class="d-flex flex-column col-md-6 mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Email</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Specify a target Position for future usage and reference">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid @error('email') is-invalid @enderror" placeholder="Email" autocomplete="off" id="position" wire:model="email" />
                        </div>
                    </div>
                    <div class="row g-9 mb-8">
                        <div class="d-flex flex-column col-md-6 mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Role</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <div class="">
                                <select class="form-select @error('role') is-invalid @enderror" id="selectRole" data-placeholder="Select Role" wire:model="selectedRole">
                                    <option>Pilih Role</option>
                                    @foreach ($roles as $role)
                                    @if ($role->name !== 'developer' || (auth()->user() && auth()->user()->hasRole('developer')))
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-column col-md-6 mb-8 fv-row">
                            @if($selectedRole == 'dokter' && !$userId)
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Select Mahasiswa</span>
                            </label>
                            <div class="" wire:ignore>
                                <select data-control="select2" class="form-select" wire:model="selectedMahasiswa" id="selectMahasiswa" data-placeholder="Pilih Mahasiswa" data-dropdown-parent="#userModal" onchange="@this.set('selectedMahasiswa', this.value)">
                                    <option value=""></option>
                                    @foreach ($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @elseif($selectedRole == 'dpjp' && !$userId)
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Select DPJP</span>
                            </label>
                            <div class="" wire:ignore.self>
                                <select class="form-select" id="selectDpjp" wire:model="selectedDpjp" data-control="select2" data-placeholder="Pilih DPJP" data-dropdown-parent="#userModal" onchange="@this.set('selectedDpjp', this.value)">
                                    <option value=""></option>
                                    @foreach ($dpjps as $dpjp)
                                    <option value="{{ $dpjp->id }}">{{ $dpjp->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @elseif($userId || $selectedRole == null)
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Pilih DPJP atau Mahasiswa</span>
                            </label>
                            @endif


                        </div>

                        <div class="d-flex flex-column col-md-6 mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Password</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Specify a target Position for future usage and reference">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Passoword" autocomplete="off" id="position" wire:model="password" />
                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" wire:click="closeModal">Close</button>
                    <button class="btn btn-primary" wire:click="{{ isset($userId) ? 'update' : 'store' }}">{{ $userId ? 'Update' : 'Store' }}</button>

                </div>
            </div>
        </div>
    </div>