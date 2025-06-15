<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Tindakan</h1>
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
                    <li class="breadcrumb-item text-muted">Tambah Tindakan</li>
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
                {{-- PASIEN --}}
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6" wire:ignore>
                            <label>Nama Pasien</label>
                            <select class="form-select" wire:model="selectedPasien" data-control="select2" onchange="@this.set('selectedPasien', this.value)">
                                <option value="">-- Cari Pasien --</option>
                                <option value="manual">+ Tambah Pasien Baru</option>
                                @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->id }}">{{ $pasien->nama }} ({{ $pasien->nomor_rekam_medis }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($selectedPasien == 'manual')
                    <div class="card mt-3">
                        <div class="card-header"><strong>Data Pasien Baru</strong></div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label>Nama</label>
                                <input type="text" class="form-control" wire:model="nama">
                            </div>
                            <div class="col-md-3">
                                <label>Usia</label>
                                <input type="number" class="form-control" wire:model="usia">
                            </div>
                            <div class="col-md-3">
                                <label>No. Rekam Medis</label>
                                <input type="text" class="form-control" wire:model="nomor_rekam_medis">
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" wire:model="tanggal_lahir">
                            </div>
                            <div class="col-md-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-select" wire:model="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Tipe Jantung</label>
                                <input type="text" class="form-control" wire:model="tipe_jantung">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- KONFERENSI --}}
                <div class="mb-4">
                    <button class="btn btn-secondary w-100 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseConference">
                        Tambah Data Conference (Opsional)
                    </button>
                    <div class="collapse" id="collapseConference">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Diagnosa</label>
                                <input type="text" class="form-control" wire:model="diagnosa">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Conference</label>
                                <input type="datetime-local" class="form-control" wire:model="tanggal_conference">
                            </div>
                            <div class="col-md-4">
                                <label>Hasil Conference</label>
                                <input type="text" class="form-control" wire:model="hasil_conference">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TINDAKAN --}}
                <div class="mb-4">
                    <h4 class="fw-bold">Data Tindakan</h4>
                    <div class="row g-3">
                        <div class="col-md-4" wire:ignore>
                            <label>Operator</label>
                            <select class="form-select" data-control="select2" onchange="@this.set('operator_id',this.value)" wire:model="operator_id">
                                <option value="">Pilih Operator</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" wire:ignore>
                            <label>Asisten 1</label>
                            <select class="form-select" data-control="select2" onchange="@this.set('asisten1_id', this.value)" wire:model="asisten1_id">
                                <option value="">Pilih Asisten 1</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" wire:ignore>
                            <label>Asisten 2</label>
                            <select class="form-select" data-control="select2" onchange="@this.set('asisten2_id', this.value)" wire:model="asisten2_id">
                                <option value="">Pilih Asisten 2</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" wire:ignore>
                            <label>On Loop</label>
                            <select class="form-select" data-control="select2" onchange="@this.set('on_loop_id', this.value)" wire:model="on_loop_id">
                                <option value="">Pilih On Loop</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}">{{ $dokter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Operasi</label>
                            <input type="date" class="form-control" wire:model="tanggal_operasi">
                        </div>
                        <div class="col-md-4">
                            <label>Realisasi Tindakan</label>
                            <input type="text" class="form-control" wire:model="relealisasi_tindakan">
                        </div>
                        <div class="col-md-4">
                            <label>Kesesuaian</label>
                            <input type="text" class="form-control" wire:model="kesesuaian">
                        </div>
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
<script>
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
                    Swal.fire("Canceled", "Action Canceled.", "info");
                }
            });
        });


    });

</script>
@endpush
