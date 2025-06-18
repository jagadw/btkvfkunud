<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Tambah Foto Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Tambah Foto Tindakan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted" href="{{route('tindakan') }}" wire:navigate>Tindakan</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted" href="{{route('tindakan') }}" wire:navigate>Foto Tindakan</li>
                    {{-- <li class="breadcrumb-item text-muted">Tambah Tindakan</li> --}}
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- <a class="btn btn-sm fw-bold btn-primary" href="{{route('add-tindakan')}}">Tambah Tindakan</a> --}}
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5">
                <div class="row g-9 mb-8">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12 mb-2" data-box="fotoTindakan" wire:ignore>
                                <input type="file" class="dropify form-control form-control-solid @error('foto') is-invalid @enderror" wire:model="foto" accept="image/jpeg,image/png" data-height="275" data-allowed-file-extensions="jpg jpeg png" data-max-file-size="4M" tabindex="-1" />
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="btn-group" data-name="fotoTindakan" role="group">
                                    {{-- <button type="button" class="btn btn-primary" wire:click="uploadFoto" title="Finalisasi File" tabindex="-1">
                                        <i class="fas fa-upload"></i>
                                    </button> --}}
                                    {{-- @if($fotoPreview)
                                    <button type="button" class="btn btn-info btn-preview" wire:click="previewFoto" title="Preview File" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" wire:click="deleteFoto" title="Delete File" tabindex="-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif --}}
                                </div>
                                {{-- @if($fotoPreview)
                                <div class="mt-2">
                                    <img src="{{ $fotoPreview }}" alt="Preview Foto" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>


                    <div class="row g-9 mb-8">
                        <div class="col-md-12">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" class="form-control form-control-solid @error('deskripsi') is-invalid @enderror" wire:model="deskripsi" placeholder="Deskripsi foto">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-light me-2" wire:click="resetForm">Batal</button>
                        <button class="btn btn-primary" wire:click="store">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script  src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script  src="{{ asset('assets/js/plugin/dropify/dropify.min.js') }}"></script>
    
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

            Livewire.on('confirm-delete', (message) => {
                Swal.fire({
                    title: message
                    , showCancelButton: true
                    , confirmButtonText: "Ya"
                    , cancelButtonText: "Tidak"
                    , icon: "warning"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteMahasiswaConfirmed');
                    } else {
                        Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                    }
                });
            });


        });

    </script>
    @endpush
