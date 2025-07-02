<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Foto Tindakan Management</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Foto Tindakan Management</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button class="btn btn-sm fw-bold btn-primary" wire:click="resetForm" data-bs-toggle="modal" data-bs-target="#fotoTindakanModal">Tambah Foto Tindakan</button>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th class="text-center align-items-center">No</th>
                                <th class="text-center align-items-center">Action</th>
                                <th class="text-center align-items-center">Tindakan</th>
                                <th class="text-center align-items-center">Foto</th>
                                <th class="text-center align-items-center">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fotoTindakans as $index => $foto)
                            <tr>
                                <td class="text-center align-items-center">{{ $index + 1 }}</td>
                                <td class="text-center align-items-center">
                                    <a wire:click="edit({{ $foto->id }})" class="btn btn-sm btn-light">Edit</a>
                                    <a wire:click="delete({{ $foto->id }})" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                                <td class="text-center align-items-center">{{ $foto->tindakan->name }}</td>
                                <td class="text-center align-items-center"><img src="{{ asset('storage/'.$foto->foto) }}" width="100"></td>
                                <td class="text-center align-items-center">{{ $foto->deskripsi }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Tidak Ditemukan!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $fotoTindakans->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Include -->
    @include('livewire.pages.admin.masterdata.fototindakan.modal')

</div>
