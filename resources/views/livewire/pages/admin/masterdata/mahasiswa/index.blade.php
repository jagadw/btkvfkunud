<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Student Management</x-slot:title>

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Student Management</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Students</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button class="btn btn-sm fw-bold btn-primary" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#mahasiswaModal">Add Student</button>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5">

                <!-- Flash Message -->
                @if (session()->has('message'))
                    <div class="alert alert-success d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                            <i class="ki-outline ki-check-circle fs-2tx text-success"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-success">Success</h4>
                            <span>{{ session('message') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Search -->
                <div class="mb-5">
                    <input type="text" wire:model.debounce.500ms="search" wire:keydown="$refresh" class="form-control form-control-solid w-250px" placeholder="Search Student">
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-row-bordered gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>No</th>
                                <th>Name</th>
                                <th>Initials</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswas as $index => $student)
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       wire:click.prevent="edit({{ $student->id }})"
                                                       data-bs-toggle="modal" data-bs-target="#mahasiswaModal">
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                       onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation();"
                                                       wire:click.prevent="deleteMahasiswa({{ $student->id }})">
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $student->inisial_residen }}</td>
                                    <td>{{ $student->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $student->status === 'aktif' ? 'badge-light-success' : 'badge-light-danger' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $mahasiswas->firstItem() }} to {{ $mahasiswas->lastItem() }} of {{ $mahasiswas->total() }} records
                        </div>
                        {{ $mahasiswas->onEachSide(1)->links() }}
                    </div>
                </div>

                <!-- Modal Include -->
                @include('livewire.pages.admin.masterdata.mahasiswa.modal')

            </div>
        </div>
    </div>
</div>
