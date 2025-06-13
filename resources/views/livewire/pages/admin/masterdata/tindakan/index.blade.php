<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Treatment Management</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Treatment Management</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Treatments</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button class="btn btn-sm fw-bold btn-primary" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#tindakanModal">Add Treatment</button>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5">
                <div class="mb-5">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-solid w-250px" placeholder="Search Patient Name">
                </div>

                <div class="table-responsive">
                    <table class="table table-row-bordered gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6 text-muted">
                                <th>No</th>
                                <th>Patient</th>
                                <th>Operator</th>
                                <th>Assistant 1</th>
                                <th>Assistant 2</th>
                                <th>On Loop</th>
                                <th>Operation Date</th>
                                <th>Realization</th>
                                <th>Match</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tindakans as $index => $t)
                                <tr>
                                    <td>{{ $tindakans->firstItem() + $index }}</td>
                                    <td>{{ $t->pasien->nama ?? '-' }}</td>
                                    <td>{{ $t->operator->name ?? '-' }}</td>
                                    <td>{{ $t->asisten1->name ?? '-' }}</td>
                                    <td>{{ $t->asisten2->name ?? '-' }}</td>
                                    <td>{{ $t->onLoop->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($t->tanggal_operasi)->format('d M Y H:i') }}</td>
                                    <td>{{ $t->relealisasi_tindakan }}</td>
                                    <td>{{ $t->kesesuaian }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       wire:click.prevent="edit({{ $t->id }})"
                                                       data-bs-toggle="modal" data-bs-target="#tindakanModal">
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                       wire:click.prevent="deleteTindakan({{ $t->id }})"
                                                       onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $tindakans->onEachSide(1)->links() }}
                    </div>
                </div>

                @include('livewire.pages.admin.masterdata.tindakan.modal')
            </div>
        </div>
    </div>
</div>
